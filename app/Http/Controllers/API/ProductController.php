<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Throwable;

class ProductController extends Controller
{
    /**
     * List api
     *
     * @param integer $page
     * @param integer $sort
     * @return \Illuminate\Http\Response
     */
    public function list(int $page = 1, int $sort = 1)
    {
        $products = Product::getList(['sort' => $sort])
            ->paginate(
                config('custom.default_pagination'),
                ['products.*'],
                'page',
                $page
            );
        $response = ProductResource::collection($products);

        return response()->respondSuccess(['products' => $response, 'last_page' => $products->lastPage()], 'Okay.');
    }

    /**
     * Create api
     *
     * @param \App\Http\Requests\ProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ProductRequest $request)
    {
        try {
            $data = $request->validated();
            Product::create($data);

            return response()->respondSuccess([], 'Product saved successfully.');
        } catch (Throwable $th) {
            return response()->respondInternalServerError([], $th->getMessage());
        }
    }

    /**
     * Update api
     *
     * @param \App\Http\Requests\ProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            $product->name = $data['name'];
            $product->price = $data['price'];
            $product->save();

            return response()->respondSuccess([], 'Product updated successfully.');
        } catch (Throwable $th) {
            return response()->respondInternalServerError([], $th->getMessage());
        }
    }

    /**
     * Delete api
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function delete(Product $product)
    {
        try {
            $product->delete();

            return response()->respondSuccess([], 'Product deleted successfully.');
        } catch (Throwable $th) {
            return response()->respondInternalServerError([], $th->getMessage());
        }
    }
}
