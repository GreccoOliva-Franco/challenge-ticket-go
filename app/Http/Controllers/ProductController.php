<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetProductsRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Product;

class ProductController extends Controller
{
    public function getList(GetProductsRequest $request)
    {
        $validated = $request->validated();

        $query = Product::query();

        if (array_key_exists('vendor_id', $validated)) {
            $query->where('vendor_id', $validated['vendor_id']);
        }
        if (array_key_exists('name', $validated)) {
            $query->where('name', 'like', "%".$validated['name']."%");
        }

        $products = $query->simplePaginate(
            $validated['per_page'],
            ['*'],
            'page',
            $validated['page'],
        );

        return new ProductCollection($products);
    }
}
