@extends('layouts.app')

@section('content')
<div class="form-card">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">登入您的帳號</h2>

    <form id="login-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">電子郵件地址</label>
            <input type="email" id="email" name="email" class="form-input" required autocomplete="email" autofocus>
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">密碼</label>
            <input type="password" id="password" name="password" class="form-input" required autocomplete="current-password">
            @error('password')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="form-checkbox text-blue-600 rounded">
                <span class="ml-2 text-sm text-gray-600">記住我</span>
            </label>
            {{-- <a href="{{ route('password.request') }}" class="inline-block align-baseline text-sm text-blue-500 hover:text-blue-800">
                忘記密碼？
            </a> --}}
        </div>

        <div>
            <button type="submit" class="form-button">
                登入
            </button>
        </div>

        <p class="text-center text-sm text-gray-600 mt-6">
            還沒有帳號？ <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-800">註冊一個</a>
        </p>
    </form>
</div>
@endsection
