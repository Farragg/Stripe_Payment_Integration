<?php

namespace App\Repository\Product;

use App\Http\Resources\Product\ProductResource;
use App\Interfaces\Product\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class ProductRepository implements ProductRepositoryInterface
{

    public function index()
    {
        $products  = Product::paginate(10);

        return ProductResource::collection($products);
//        return view('products.index', compact('products'));
    }

    public function show($product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store($request)
    {
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'Product Created Successfully');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update($request, $id)
    {
        Gate::authorize('update', $id);

        $product = Product::findOrFail($id);

        // fill the fields with the new changes
        $product->fill($request->all());

        // check for changes and update only if there are changes
        if ($product->isDirty()) {
            $product->save();
        }

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        Gate::authorize('delete', $id);

        Product::findOrFail($id)->delete();

        return redirect('/product')->with('danger', 'Product deleted');
    }

    public function productsExpensive()
    {
        $amount = 100;
        $products = Product::where('price', '>', $amount)->get();
        return view('products.index', compact('products'));
    }
}
