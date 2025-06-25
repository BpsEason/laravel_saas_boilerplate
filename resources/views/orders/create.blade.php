@extends('layouts.app')

@section('content')
<div class="form-card">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">建立新訂單</h2>

    <form id="order-form" method="POST">
        @csrf

        <div class="mb-4">
            <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">顧客名稱</label>
            <input type="text" id="customer-name" name="customer_name" class="form-input" required>
        </div>

        <div class="mb-4">
            <label for="product-id" class="block text-gray-700 text-sm font-bold mb-2">選擇產品</label>
            <select id="product-id" name="product_id" class="form-input" required>
                {{-- Products will be loaded here by JavaScript --}}
                <option value="">載入產品中...</option>
            </select>
        </div>

        <div class="mb-6">
            <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">數量</label>
            <input type="number" id="quantity" name="quantity" class="form-input" min="1" required>
        </div>

        <div>
            <button type="submit" class="form-button">
                建立訂單
            </button>
        </div>
    </form>
</div>
@endsection
