@extends('layouts.app')

@section('content')
<div class="container" id="orders-list-page">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">訂單列表</h1>
        <a href="{{ route('orders.create') }}" id="create-order-button" class="action-button bg-blue-600 hover:bg-blue-700">建立新訂單</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>顧客名稱</th>
                <th>總金額</th>
                <th>狀態</th>
                <th>建立日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            {{-- Orders will be loaded here via JavaScript --}}
            <tr><td colspan="6" class="text-center py-4">載入中...</td></tr>
        </tbody>
    </table>
    {{-- Pagination links would go here if server-side rendered --}}
</div>
@endsection
