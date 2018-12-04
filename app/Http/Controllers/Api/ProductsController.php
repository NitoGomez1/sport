<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::oldest('name')->paginate();

        return response()->json(['data' => $products], Response::HTTP_OK);
    }

    public function store(CreateProductRequest $request)
    {
        $product = Product::create($request->all());

        return response()->json($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json(['data' => $product], Response::HTTP_OK);
    }

    /**
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        return response()->json(['data' => $product], Response::HTTP_OK);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['data' => $product], Response::HTTP_OK);
    }
}
