<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Address;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with(['items' => function($q) {
            $q->where('is_selected', true)->with('variant.product.primaryImage');
        }])->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Please select at least one item to checkout.');
        }

        $addresses = Address::where('user_id', Auth::id())->get();
        $provinces = $this->rajaOngkir->getProvinces();
        $subtotal = $cart->getSubtotal();
        
        // Calculate total weight in grams
        $totalWeight = $cart->items->sum(function($item) {
            return ($item->variant->product->weight_grams ?? 300) * $item->quantity;
        });

        return view('checkout.index', compact('cart', 'addresses', 'provinces', 'subtotal', 'totalWeight'));
    }

    /**
     * API: Get cities by province
     */
    public function getCities($provinceId)
    {
        $cities = $this->rajaOngkir->getCities($provinceId);
        return response()->json($cities);
    }

    /**
     * API: Calculate shipping cost
     */
    public function getShippingCost(Request $request)
    {
        $request->validate([
            'destination_city_id' => 'required',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|in:jne,pos,tiki'
        ]);

        $costs = $this->rajaOngkir->getCost(
            $request->destination_city_id,
            $request->weight,
            $request->courier
        );

        return response()->json($costs);
    }
}
