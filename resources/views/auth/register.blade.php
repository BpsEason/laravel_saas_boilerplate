@extends('layouts.app')

@section('content')
<div class="form-card">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">註冊新租戶與帳號</h2>

    <form id="register-form" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="tenant_name" class="block text-gray-700 text-sm font-bold mb-2">租戶名稱</label>
            <input type="text" id="tenant_name" name="tenant_name" class="form-input" value="{{ old('tenant_name') }}" required autocomplete="organization" autofocus>
            @error('tenant_name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="tenant_domain" class="block text-gray-700 text-sm font-bold mb-2">租戶域名 (例如: mycompany.localhost:8000)</label>
            <input type="text" id="tenant_domain" name="tenant_domain" class="form-input" value="{{ old('tenant_domain') }}" required autocomplete="url">
            @error('tenant_domain')
                <p class="error-message">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">請確保此域名能指向您的應用程式 (例如：修改 hosts 檔案或使用 DNS)</p>
        </div>

        <hr class="my-6 border-gray-200">

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">您的名稱</label>
            <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required autocomplete="name">
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">電子郵件地址</label>
            <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">密碼</label>
            <input type="password" id="password" name="password" class="form-input" required autocomplete="new-password">
            @error('password')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">確認密碼</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required autocomplete="new-password">
        </div>

        <div>
            <button type="submit" class="form-button">
                註冊
            </button>
        </div>

        <p class="text-center text-sm text-gray-600 mt-6">
            已經有帳號？ <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-800">登入</a>
        </p>
    </form>
</div>
@endsection
