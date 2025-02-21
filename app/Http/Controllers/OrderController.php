<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //


    public function order()
    {
        $user = Auth::user();
        if ($user->role == 0) {

            $orders = Order::with('user', 'product')->where('user_id', $user->id)->get();

            return response($orders, 200);
        } else {
            $stores = Store::with(['products.orders'])
            ->where('user_id', $user->id)
            ->orderBy('id') 
            ->get();

            return response($stores, 200);
        }
    }
}
