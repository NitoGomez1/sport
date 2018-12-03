<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use Illuminate\Http\Request;
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
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'unique:brands'],
        ]);

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
     * @param Request $request
     * @param Brand $brand
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Brand $brand)
    {
        $this->validate($request, [
            'name' => ['unique:brands,name,' . $brand->id],
        ]);

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
