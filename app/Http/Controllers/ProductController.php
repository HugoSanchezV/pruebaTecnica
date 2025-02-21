<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    public function store(ProductRequest $request)
    {
        $fields = $request->validated();

        $store = Store::findOrFail($fields['store_id']);
        Gate::authorize('create', [Product::class, $store]);

        $product = Product::create($fields);

        return response()->json([$product], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        $fields = $request->validated();

        Gate::authorize('update', $product);

        $product->update($fields);

        return response()->json($product, 200);
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        $product->delete();

        return response()->json(['message' => 'Producto eliminado'], 200);
    }
}
