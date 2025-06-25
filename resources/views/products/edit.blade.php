@extends('layouts.app')

@section('content')
<div class="form-card" id="product-edit-page" data-product-id="{{ $product_id }}">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">編輯產品</h2>

    <form id="product-form" method="POST">
        @csrf
        @method('PUT') {{-- For PUT/PATCH requests --}}

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">產品名稱</label>
            <input type="text" id="product-name" name="name" class="form-input" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">產品描述</label>
            <textarea id="product-description" name="description" class="form-input"></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">價格</label>
            <input type="number" id="product-price" name="price" class="form-input" step="0.01" min="0" required>
        </div>

        <div class="mb-6">
            <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">庫存</label>
            <input type="number" id="product-stock" name="stock" class="form-input" min="0" required>
        </div>

        <div>
            <button type="submit" class="form-button">
                更新產品
            </button>
        </div>
    </form>
</div>
@endsection
