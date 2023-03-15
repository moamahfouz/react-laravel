<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductResource;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $products = auth()->user()->products;

        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = auth()->user()->products()->create([
            'name' => $request->name,
            'detail' => $request->detail,
            'price' => $request->price,
        ]);

        if ($request->files) {
            foreach ($request->files as $file) {
                $product->attachments()->create([
                    'path' => $this->uploadFile($file, 'products', 'pro'),
                ]);
            }
        }

        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product): \Illuminate\Http\JsonResponse
    {
        if (!$this->checkProductOwner($product)) {
            return $this->sendError('You are not authorized to update this product.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if (!$this->checkProductOwner($product)) {
            return $this->sendError('You are not authorized to update this product.');
        }

        $product->name = $request->name;
        $product->detail = $request->detail;
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product): \Illuminate\Http\JsonResponse
    {
        if (!$this->checkProductOwner($product)) {
            return $this->sendError('You are not authorized to update this product.');
        }

        $product->attachments()->delete();
        $product->delete();
        return $this->sendResponse([], 'Product deleted successfully.');
    }

    /**
     * General rules for validation
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'detail' => 'required|string|max:255',
            'price' => 'required',
        ];
    }

    /**
     * Check if the product belongs to the user
     *
     * @param Product $product
     * @return bool
     */
    protected function checkProductOwner(Product $product): bool
    {
        return $product->user_id == auth()->user()->id;
    }
}
