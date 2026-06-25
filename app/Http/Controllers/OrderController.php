<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Show user orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Place order and get Midtrans payment token.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'full_address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'courier' => 'required|string',
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with(['items' => function($q) {
            $q->where('is_selected', true)->with('variant.product');
        }])->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        try {
            DB::beginTransaction();

            $subtotal = $cart->getSubtotal();
            $total = $subtotal + $request->shipping_cost;

            $shippingAddress = [
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'full_address' => $request->full_address,
                'province' => $request->province,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
            ];

            // Create Order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $request->shipping_cost,
                'total' => $total,
                'shipping_address' => $shippingAddress,
                'courier' => strtoupper($request->courier) . ' - ' . $request->shipping_service,
                'ordered_at' => now(),
            ]);

            // Create Order Items and decrease stock
            $itemDetails = [];
            foreach ($cart->items as $item) {
                // Decrement stock (will throw exception if insufficient)
                $item->variant->decrementStock($item->quantity);

                $price = $item->variant->getFinalPrice();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->variant->product->name,
                    'product_size' => $item->variant->size,
                    'product_color' => $item->variant->color,
                    'product_image' => $item->variant->product->primaryImageUrl,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $price * $item->quantity,
                ]);

                // Format for Midtrans
                $itemDetails[] = [
                    'id' => $item->variant->sku,
                    'price' => (int) $price,
                    'quantity' => $item->quantity,
                    'name' => mb_strimwidth($item->variant->product->name . ' (' . $item->variant->size . ')', 0, 50, "..."),
                ];
            }

            // Add shipping to midtrans items
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $request->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost (' . strtoupper($request->courier) . ')',
            ];

            $customerDetails = [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => $request->phone,
                'shipping_address' => [
                    'first_name' => $request->recipient_name,
                    'phone' => $request->phone,
                    'address' => $request->full_address,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'country_code' => 'IDN'
                ]
            ];

            // Get Midtrans Snap Token
            $snapToken = $this->midtrans->getSnapToken($order, $customerDetails, $itemDetails);
            
            $order->update(['payment_token' => $snapToken]);

            // Clear only selected items from Cart
            $cart->items()->where('is_selected', true)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'token' => $snapToken,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to place order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show order success page.
     */
    public function show($id)
    {
        $order = Order::with('items')->where('user_id', Auth::id())->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Midtrans Webhook Notification Handler (Public)
     */
    public function notification(Request $request)
    {
        $serverKey = \App\Models\Setting::get('midtrans_server_key');
        
        // Verify signature key
        $signatureKey = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $request->order_id)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        DB::beginTransaction();
        try {
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->payment_status = 'unpaid';
                } else if ($fraudStatus == 'accept') {
                    $order->payment_status = 'paid';
                    $order->status = 'processing';
                }
            } else if ($transactionStatus == 'settlement') {
                $order->payment_status = 'paid';
                $order->status = 'processing';
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $order->payment_status = 'failed';
                $order->status = 'cancelled';
                
                // Restock items
                foreach ($order->items as $item) {
                    $variant = \App\Models\ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                    }
                }
            } else if ($transactionStatus == 'pending') {
                $order->payment_status = 'unpaid';
            }
            
            $order->payment_method = $request->payment_type;
            $order->save();
            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating order'], 500);
        }
    }
}
