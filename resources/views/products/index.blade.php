@extends('layouts.app')

@section('content')
<div class="container" id="products-list-page">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">產品列表</h1>
        <a href="{{ route('products.create') }}" id="add-product-button" class="action-button bg-blue-600 hover:bg-blue-700">新增產品</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>名稱</th>
                <th>描述</th>
                <th>價格</th>
                <th>庫存</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            {{-- Products will be loaded here via JavaScript --}}
            <tr><td colspan="6" class="text-center py-4">載入中...</td></tr>
        </tbody>
    </table>
    {{-- Pagination links would go here if server-side rendered --}}
</div>
@endsection
