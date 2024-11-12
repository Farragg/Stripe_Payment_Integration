<?php

namespace App\Interfaces\Product;

interface ProductRepositoryInterface
{
    public function index();
    public function show($product);
    public function create();
    public function store($request);
    public function edit($id);
    public function update($request, $id);
    public function destroy($id);
    public function productsExpensive();

}
