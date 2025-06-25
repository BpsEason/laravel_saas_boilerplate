@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 welcome-heading">儀表板</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="card products-overview-card">
            <h2 class="card-title">產品概覽</h2>
            <p class="card-content">
                查看和管理您的所有產品。
            </p>
            <a href="{{ route('products.index') }}" class="action-button mt-4 inline-block">前往產品列表</a>
        </div>

        <div class="card orders-overview-card">
            <h2 class="card-title">訂單概覽</h2>
            <p class="card-content">
                管理和追蹤所有客戶訂單。
            </p>
            <a href="{{ route('orders.index') }}" class="action-button mt-4 inline-block">前往訂單列表</a>
        </div>

        {{-- You can add more cards here for other features --}}
        <div class="card">
            <h2 class="card-title">API 文件</h2>
            <p class="card-content">
                探索並測試您的 RESTful API 端點。
            </p>
            <a href="/api/docs" target="_blank" class="action-button mt-4 inline-block">查看 API 文件</a>
        </div>
    </div>
</div>
@endsection
