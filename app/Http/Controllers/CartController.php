<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $user_id = Auth::id();
        $carts = Cart::where('user_id', $user_id)
            ->whereNull('order_id')
            ->get();
        return $this->showAll($carts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $data = $this->validate($request, [
            'product_id' => 'required|numeric',
        ]);

        $user_id = Auth::id();

        $alreadyExistInCart = Cart::where('user_id', $user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!is_null($alreadyExistInCart)) {
            $alreadyExistInCart->increment('quantity');
            return $this->showOne($alreadyExistInCart, 201);
        } else {
            $cart = new Cart();
            $cart->user_id = $user_id;
            $cart->product_id = $data['product_id'];
            $cart->quantity = Cart::DEFAULT_QUANTITY;
            $cart->user_ip = $request->ip();
            $cart->save();
            return $this->showOne($cart, 201);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function update(Request $request, Cart $cart)
    {
        $data = $this->validate($request, [
            'quantity' => 'required|numeric',
        ]);

        $cart->quantity = $data['quantity'];
        $cart->save();
        return $this->showOne($cart, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Cart $cart
     * @return JsonResponse
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return $this->showOne($cart);
    }
}
