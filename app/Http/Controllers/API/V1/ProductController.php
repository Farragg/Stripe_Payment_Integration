<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Interfaces\Product\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $product;
    public function __construct(ProductRepositoryInterface $product)
    {
        $this->product = $product;
    }

    public function index()
    {
        return $this->product->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->product->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        return $this->product->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->product->show($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->product->edit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        return $this->product->update($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return $this->product->destroy($product);
    }

    public function productsExpensive()
    {
        return $this->product->productsExpensive();
    }
}
