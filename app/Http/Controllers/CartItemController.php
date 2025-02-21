<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    //
    public function index()
    {
        $cartItems = Auth::user()->cartItems;
        return response()->json($cartItems, 200);
    }

    public function addToCart(CartItemRequest $request)
    {

        $request->validated();
        $user = Auth::user();
        $product = Product::find($request->product_id);

        if (!$product || $product->stock < 1) {
            return response()->json(['message' => 'Stock insuficiente'], 400);
        }

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();


        if ($cartItem) {
            if ($cartItem->quantity < $product->stock) {
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                return response()->json(['message' => 'No puedes agregar más de lo que hay en stock'], 400);
            }
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => 1,
                'status' => 'pendiente'
            ]);
        }

        return response()->json(['message' => 'Producto agregado al carrito']);
    }

    public function removeFromCart(CartItemRequest $request)
    {
        $request->validated();
        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'El producto no está en el carrito'], 404);
        }

        if ($cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $cartItem->save();
        } else {
            $cartItem->delete();
        }

        return response()->json(['message' => 'Producto removido del carrito']);
    }
}
