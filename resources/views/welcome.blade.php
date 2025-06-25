@extends('layouts.app')

@section('content')
<div class="text-center py-16">
    <h1 class="text-5xl font-bold text-gray-800 mb-6">歡迎來到 Laravel SaaS 樣板！</h1>
    <p class="text-xl text-gray-600 mb-8">
        一個專為多租戶應用程式設計的強大基礎。
    </p>
    <div class="space-x-4">
        <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-300">
            登入
        </a>
        <a href="{{ route('register') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-300">
            開始使用 (註冊新租戶)
        </a>
    </div>
</div>
@endsection
