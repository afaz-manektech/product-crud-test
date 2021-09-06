<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(ProductRequest $request)
    {
        $data = $request->all();

        if ($file = $request->file('photo')) {
            Storage::disk('public')->put($path = "products/{$file->getClientOriginalName()}", $file);

            $data['photo'] = $path;
        }

        $product = Product::query()->create($data);

        return $product;
    }
}
