<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart ? $cart->items()->with(['variant.product.primaryImage'])->get() : collect();
        $subtotal = $cart ? $cart->getSubtotal() : 0;

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'color' => 'required|string',
            'size' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $variant = ProductVariant::where('product_id', $request->product_id)
            ->where('color', $request->color)
            ->where('size', $request->size)
            ->active()
            ->first();

        if (!$variant) {
            return response()->json(['message' => 'Selected variant is not available.'], 404);
        }

        if ($variant->stock < $request->quantity) {
            return response()->json(['message' => 'Not enough stock available.'], 400);
        }

        $cart = $this->getOrCreateCart();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($cartItem) {
            if ($cartItem->quantity + $request->quantity > $variant->stock) {
                return response()->json(['message' => 'Cannot add more than available stock.'], 400);
            }
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'message' => 'Item added to cart.',
            'cart_count' => $cart->getItemCount()
        ]);
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cartItem = CartItem::with('variant')->findOrFail($id);

        if ($cartItem->cart_id !== $this->getCart()?->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->quantity > $cartItem->variant->stock) {
            return response()->json(['message' => 'Not enough stock available.'], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'message' => 'Cart updated.',
            'cart_count' => $cartItem->cart->getItemCount(),
            'subtotal' => $cartItem->cart->getSubtotal()
        ]);
    }

    /**
     * Toggle selection of cart item.
     */
    public function toggleSelection(Request $request, $id)
    {
        $request->validate(['is_selected' => 'required|boolean']);
        
        $cartItem = CartItem::findOrFail($id);
        
        if ($cartItem->cart_id !== $this->getCart()?->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->update(['is_selected' => $request->is_selected]);

        return response()->json([
            'message' => 'Selection updated.',
            'subtotal' => $cartItem->cart->getSubtotal()
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        if ($cartItem->cart_id !== $this->getCart()?->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cart = $cartItem->cart;
        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart.',
            'cart_count' => $cart->getItemCount(),
            'subtotal' => $cart->getSubtotal()
        ]);
    }

    /**
     * Get current cart item count.
     */
    public function count()
    {
        $cart = $this->getCart();
        return response()->json(['count' => $cart ? $cart->getItemCount() : 0]);
    }

    /**
     * Helper: Get current active cart.
     */
    private function getCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->first();
        }

        $sessionId = Session::getId();
        return Cart::where('session_id', $sessionId)->first();
    }

    /**
     * Helper: Get or create cart.
     */
    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }
}
