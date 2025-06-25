<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @group Order Management
 *
 * 管理租戶下的訂單資訊。
 *
 * 這些 API 需要認證並在租戶上下文中運行。
 */
class OrderController extends Controller
{
    /**
     * 獲取所有訂單
     *
     * 獲取當前認證用戶所擁有的所有訂單，包含訂單項目及相關產品資料。
     *
     * @authenticated
     * @response 200 {
     * "status": "success",
     * "data": [
     * {
     * "id": 1,
     * "user_id": 1,
     * "tenant_id": 1,
     * "customer_name": "Customer One",
     * "total_amount": "150.00",
     * "status": "pending",
     * "created_at": "2023-01-01 12:00:00",
     * "updated_at": "2023-01-01 12:00:00",
     * "items": [
     * {
     * "id": 1,
     * "product_id": 101,
     * "product_name": "Product X",
     * "quantity": 1,
     * "price_per_unit": "100.00",
     * "subtotal": "100.00"
     * }
     * ]
     * }
     * ]
     * }
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->paginate(10);
        return OrderResource::collection($orders);
    }

    /**
     * 創建新訂單
     *
     * 為當前認證用戶在當前租戶下創建一個新訂單。此操作會自動扣減產品庫存。
     *
     * @authenticated
     * @bodyParam customer_name string required 顧客名稱. Example: Alice Smith
     * @bodyParam items array required 訂單項目列表.
     * @bodyParam items.*.product_id integer required 產品 ID. Example: 1
     * @bodyParam items.*.quantity integer required 訂購數量. Example: 2
     *
     * @response 201 {
     * "status": "success",
     * "data": {
     * "id": 2,
     * "user_id": 1,
     * "tenant_id": 1,
     * "customer_name": "Alice Smith",
     * "total_amount": "200.00",
     * "status": "pending",
     * "created_at": "2023-01-02 10:00:00",
     * "updated_at": "2023-01-02 10:00:00",
     * "items": [
     * {
     * "id": 3,
     * "product_id": 1,
     * "product_name": "Product X",
     * "quantity": 2,
     * "price_per_unit": "100.00",
     * "subtotal": "200.00"
     * }
     * ]
     * }
     * }
     * @response 400 {
     * "message": "The given data was invalid.",
     * "errors": {
     * "items": ["Not enough stock for product 'Product Name'. Available: 5, Requested: 10"]
     * }
     * }
     * @response 422 {
     * "message": "The given data was invalid.",
     * "errors": {
     * "customer_name": ["The customer name field is required."]
     * }
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = 0;
        $orderItemsData = [];

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                if (!$product || $product->user_id !== Auth::id()) {
                    throw ValidationException::withMessages([
                        'items' => [__('validation.custom.order.product_not_found_or_accessible', ['product_id' => $item['product_id']])],
                    ])->status(400);
                }

                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => [__('validation.custom.order.not_enough_stock', ['product_name' => $product->name, 'available' => $product->stock, 'requested' => $item['quantity']])],
                    ])->status(400);
                }

                $product->decrement('stock', $item['quantity']);
                $totalAmount += $product->price * $item['quantity'];
                
                $orderItemsData[] = new OrderItem([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $product->price,
                ]);
            }

            $order = Auth::user()->orders()->create([
                'customer_name' => $request->customer_name,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            $order->items()->saveMany($orderItemsData);

            DB::commit();

            return new OrderResource($order->load('items.product'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 顯示特定訂單
     *
     * 顯示指定 ID 的訂單詳細資料，包含訂單項目及相關產品資料。訂單必須屬於當前認證用戶和租戶。
     *
     * @authenticated
     * @urlParam order integer required 訂單的 ID. Example: 1
     * @response 200 {
     * "status": "success",
     * "data": {
     * "id": 1,
     * "user_id": 1,
     * "tenant_id": 1,
     * "customer_name": "Customer One",
     * "total_amount": "150.00",
     * "status": "pending",
     * "created_at": "2023-01-01 12:00:00",
     * "updated_at": "2023-01-01 12:00:00",
     * "items": [
     * {
     * "id": 1,
     * "product_id": 101,
     * "product_name": "Product X",
     * "quantity": 1,
     * "price_per_unit": "100.00",
     * "subtotal": "100.00"
     * }
     * ]
     * }
     * }
     * @response 403 {
     * "message": "You are not authorized to view this order."
     * }
     * @response 404 {
     * "message": "No query results for model [App\\Models\\Order] 100"
     * }
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'order' => [__('validation.custom.order.unauthorized')],
            ])->status(403);
        }
        return new OrderResource($order->load('items.product'));
    }

    /**
     * 更新訂單
     *
     * 更新指定 ID 訂單的資訊 (例如狀態或顧客名稱)。訂單必須屬於當前認證用戶和租戶。
     *
     * @authenticated
     * @urlParam order integer required 訂單的 ID. Example: 1
     * @bodyParam customer_name string 顧客名稱. Example: Updated Customer Name
     * @bodyParam status string 訂單狀態. 可選值: pending, confirmed, shipped, completed, cancelled. Example: confirmed
     *
     * @response 200 {
     * "status": "success",
     * "data": {
     * "id": 1,
     * "user_id": 1,
     * "tenant_id": 1,
     * "customer_name": "Updated Customer Name",
     * "total_amount": "150.00",
     * "status": "confirmed",
     * "created_at": "2023-01-01 12:00:00",
     * "updated_at": "2023-01-02 16:00:00",
     * "items": []
     * }
     * }
     * @response 403 {
     * "message": "You are not authorized to update this order."
     * }
     * @response 422 {
     * "message": "The given data was invalid.",
     * "errors": {
     * "status": ["The selected status is invalid."]
     * }
     * }
     */
    public function update(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'order' => [__('validation.custom.order.unauthorized_update')],
            ])->status(403);
        }

        $request->validate([
            'customer_name' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:pending,confirmed,shipped,completed,cancelled',
        ]);

        $order->update($request->all());

        return new OrderResource($order->load('items.product'));
    }

    /**
     * 刪除訂單
     *
     * 刪除指定 ID 的訂單。此操作會將訂單中的產品庫存恢復。訂單必須屬於當前認證用戶和租戶。
     *
     * @authenticated
     * @urlParam order integer required 訂單的 ID. Example: 1
     * @response 200 {
     * "status": "success",
     * "message": "Order deleted successfully."
     * }
     * @response 403 {
     * "message": "You are not authorized to delete this order."
     * }
     * @response 404 {
     * "message": "No query results for model [App\\Models\\Order] 100"
     * }
     */
    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'order' => [__('validation.custom.order.unauthorized_delete')],
            ])->status(403);
        }

        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            $order->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json(['status' => 'success', 'message' => __('messages.order_deleted')]);
    }
}
