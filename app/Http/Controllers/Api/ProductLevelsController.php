<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductLevel\CreateProductLevelRequest;
use App\Http\Requests\ProductLevel\UpdateProductLevelRequest;
use App\Models\ProductLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ProductLevelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levels = ProductLevel::oldest('name')->paginate();

        return response()->json(['data' => $levels], Response::HTTP_OK);
    }

    /**
     * @param CreateProductLevelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProductLevelRequest $request)
    {
        $level = ProductLevel::create($request->only('name'));

        return response()->json($level, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  ProductLevel $productLevel
     * @return \Illuminate\Http\Response
     */
    public function show(ProductLevel $productLevel)
    {
        return response()->json(['data' => $productLevel], Response::HTTP_OK);
    }

    /**
     * @param UpdateProductLevelRequest $request
     * @param ProductLevel $productLevel
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductLevelRequest $request, ProductLevel $productLevel)
    {
        $productLevel->update($request->only('name'));

        return response()->json(['data' => $productLevel], Response::HTTP_OK);
    }

    /**
     * @param ProductLevel $productLevel
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(ProductLevel $productLevel)
    {
        $productLevel->delete();

        return response()->json(['data' => $productLevel], Response::HTTP_OK);
    }
}
