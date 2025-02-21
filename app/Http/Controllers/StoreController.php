<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreRequest;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::all();

        return response()->json($stores, 200);
    }

    public function store(StoreRequest $request)
    {
        $store = $request->validated();

        $store = Store::create([
            'user_id' => Auth::id(),
            'name' => $store['name'],
        ]);

        return response()->json($store, 201);
    }

    public function show($id)
    {
        $store = Store::find($id);

        if (!$store) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);
        }

        return response()->json($store, 200);
    }

    public function update(StoreRequest $request, $id)
    {
        $store = Store::find($id);

        Gate::authorize('update', $store);

        if (!$store) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);
        }

        $store->update($request->all());

        return response()->json($store, 200);
    }

    public function destroy($id)
    {
        $store = Store::find($id);

        Gate::authorize('delete', $store);

        if (!$store) {
            return response()->json(['message' => 'Tienda no encontrada'], 404);
        }

        $store->delete();

        return response()->json(['message' => 'Tienda eliminada'], 200);
    }
}
