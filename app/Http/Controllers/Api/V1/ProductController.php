<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

/**
 * @group Product Management
 *
 * 管理租戶下的產品資訊。
 *
 * 這些 API 需要認證並在租戶上下文中運行。
 */
class ProductController extends Controller
{
    /**
     * 獲取所有產品
     *
     * 獲取當前認證用戶所擁有的所有產品。
     *
     * @authenticated
     * @response 200 {
     * "status": "success",
     * "data": [
     * {
     * "id": 1,
     * "user_id": 1,
     * "tenant_id": 1,
     * "name": "Widget A",
     * "description": "Awesome widget",
     * "price": "99.99",
     * "stock": 100,
     * "created_at": "2023-01-01 12:00:00",
     * "updated_at": "2023-01-01 12:00:00"
     * }
     * ]
     * }
     */
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->paginate(10);
        return ProductResource::collection($products);
    }

    /**
     * 創建新產品
     *
     * 為當前認證用戶在當前租戶下創建一個新產品。
     *
     * @authenticated
     * @bodyParam name string required 產品名稱. Example: New Gadget
     * @bodyParam description string 產品描述. Example: A fantastic new electronic gadget.
     * @bodyParam price numeric required 產品價格. Example: 199.99
     * @bodyParam stock integer required 產品庫存. Example: 50
     *
     * @response 201 {
     * "status": "success",
     * "data": {
     * "id": 2,
     * "user_id": 1,
     * "tenant_id": 1,
     * "name": "New Gadget",
     * "description": "A fantastic new electronic gadget.",
     * "price": "199.99",
     * "stock": 50,
     * "created_at": "2023-01-02 10:00:00",
     * "updated_at": "2023-01-02 10:00:00"
     * }
     * }
     * @response 422 {
     * "message": "The given data was invalid.",
     * "errors": {
     * "name": ["The name field is required."]
     * }
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Auth::user()->products()->create($request->all());
        
        return new ProductResource($product);
    }

    /**
     * 顯示特定產品
     *
     * 顯示指定 ID 的產品詳細資料。產品必須屬於當前認證用戶和租戶。
     *
     * @authenticated
     * @urlParam product integer required 產品的 ID. Example: 1
     * @response 200 {
     * "status": "success",
     * "data": {
     * "id": 1,
     * "user_id": 1,
     * "tenant_id": 1,
     * "name": "Widget A",
     * "description": "Awesome widget",
     * "price": "99.99",
     * "stock": 100,
     * "created_at": "2023-01-01 12:00:00",
     * "updated_at": "2023-01-01 12:00:00"
     * }
     * }
     * @response 403 {
     * "message": "You are not authorized to view this product."
     * }
     * @response 404 {
     * "message": "No query results for model [App\\Models\\Product] 100"
     * }
     */
    public function show(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'product' => [__('validation.custom.product.unauthorized')],
            ])->status(403);
        }
        return new ProductResource($product);
    }

    /**
     * 更新產品
     *
     * 更新指定 ID 產品的資訊。產品必須屬於當前認證用戶和租戶。
     *
     * @authenticated
     * @urlParam product integer required 產品的 ID. Example: 1
     * @bodyParam name string 產品名稱. Example: Updated Gadget Name
     * @bodyParam price numeric 產品價格. Example: 250.00
     * @bodyParam stock integer 產品庫存. Example: 45
     *
     * @response 200 {
     * "status": "success",
     * "data": {
     * "id": 1,
     * "user_id": 1,
     * "tenant_id": 1,
     * "name": "Updated Gadget Name",
     * "description": "Awesome widget",
     * "price": "250.00",
     * "stock": 45,
     * "created_at": "2023-01-01 12:00:00",
     * "updated_at": "2023-01-02 15:30:00"
     * }
     * }
     * @response 403 {
     * "message": "You are not authorized to update this product."
     * }
     * @response 422 {
     * "message": "The given data was invalid.",
     * "errors": {
     * "price": ["The price field must be a number."]
     * }
     * }
     */
    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'product' => [__('validation.custom.product.unauthorized_update')],
            ])->status(403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $product->update($request->all());

        return new ProductResource($product);
    }

    /**
     * 刪除產品
     *
     * 刪除指定 ID 的產品。產品必須屬於當前認證用戶和租戶。
     *
     * @authenticated
     * @urlParam product integer required 產品的 ID. Example: 1
     * @response 200 {
     * "status": "success",
     * "message": "Product deleted successfully."
     * }
     * @response 403 {
     * "message": "You are not authorized to delete this product."
     * }
     * @response 404 {
     * "message": "No query results for model [App\\Models\\Product] 100"
     * }
     */
    public function destroy(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'product' => [__('validation.custom.product.unauthorized_delete')],
            ])->status(403);
        }

        $product->delete();

        return response()->json(['status' => 'success', 'message' => __('messages.product_deleted')]);
    }
}
