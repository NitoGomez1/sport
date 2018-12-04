<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::oldest('name')->paginate();

        return response()->json($brands, Response::HTTP_OK);
    }

    /**
     * @param CreateBrandRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBrandRequest $request)
    {
        $brand = Brand::create($request->only('name'));

        return response()->json($brand, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Brand $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return response()->json([
            'data' => $brand,
        ], 200);
    }

    /**
     * @param UpdateBrandRequest $request
     * @param Brand $brand
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->only('name'));

        return response()->json(['data' => $brand], Response::HTTP_OK);
    }

    /**
     * @param Brand $brand
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json(['data' => $brand], Response::HTTP_OK);
    }
}
