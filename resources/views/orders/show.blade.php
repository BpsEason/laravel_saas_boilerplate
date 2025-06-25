@extends('layouts.app')

@section('content')
<div class="container" id="order-show-page" data-order-id="{{ $order_id }}">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">訂單詳細資訊</h1>
        <a href="{{ route('orders.index') }}" class="action-button bg-gray-600 hover:bg-gray-700">返回訂單列表</a>
    </div>

    <div class="card" id="order-details-container">
        {{-- Order details and items will be loaded here via JavaScript --}}
        <p class="text-center py-4">載入中...</p>
    </div>
</div>
@endsection
