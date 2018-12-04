<?php

namespace App\Http\Controllers\Api;

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'unique:product_levels'],
        ]);

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
     * @param Request $request
     * @param ProductLevel $productLevel
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, ProductLevel $productLevel)
    {
        $this->validate($request, [
            'name' => ['unique:product_levels,name,' . $productLevel->id],
        ]);

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
