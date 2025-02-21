<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartItemRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function payCart()
    {
        try {
            $user_id = Auth::id();

            DB::transaction(function () use ($user_id) {
                $cartItems = CartItem::with('product')
                    ->where('user_id', $user_id)
                    ->lockForUpdate()
                    ->get();


                foreach ($cartItems as $item) {

                    if ($item->quantity > $item['product']['stock']) {
                        throw new \Exception("No hay suficiente stock para el producto: " . $item['product']['name']);
                    }

                    $total = $item->quantity * $item['product']['price'];

                    Order::create([
                        'product_id' => $item['product']['id'],
                        'user_id' => $user_id,
                        'quantity' => $item->quantity,
                        'total' => $total
                    ]);

                    $item['product']->decrement('stock', $item->quantity);

                    $item->delete();
                }
            });

            return response(["Se ha realiado el pago"], 200);
        } catch (\Exception $e) {
            return response(["Error al realizar la operacion {$e->getMessage()}"], 500);
        }
    }
}
