<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return Product::all();
    }

    public function store(ProductRequest $request)
    {
        $data = $request->all();

        if ($file = $request->file('photo')) {
            Storage::disk('public')->put($path = "products/{$file->getClientOriginalName()}", $file);

            $data['photo'] = $path;
        }

        $product = Product::query()->create(array_merge($data, [
            'user_id' => auth()->id()
        ]));

        return $product;
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->all();

        if ($file = $request->file('photo')) {
            Storage::disk('public')->put($path = "products/{$file->getClientOriginalName()}", $file);

            $data['photo'] = $path;
        }

        $product->update(array_merge($data, [
            'user_id' => auth()->id()
        ]));

        return $product;
    }

    public function destroy(Product $product)
    {
        return $product->delete();
    }
}
