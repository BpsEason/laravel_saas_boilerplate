<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <nav class="navbar">
            <div class="container mx-auto flex justify-between items-center">
                <a href="{{ url('/') }}" class="navbar-brand">
                    {{ config('app.name', 'Laravel SaaS') }}
                </a>

                <div class="nav-links flex items-center">
                    @auth
                        {{-- Tenant-aware navigation for authenticated users --}}
                        <a href="{{ route('dashboard') }}">儀表板</a>
                        <a href="{{ route('products.index') }}">產品</a>
                        <a href="{{ route('orders.index') }}">訂單</a>
                    @endauth
                </div>

                <div class="auth-buttons flex items-center">
                    @guest
                        <a href="{{ route('login') }}" class="mr-4">登入</a>
                        <a href="{{ route('register') }}">註冊</a>
                    @else
                        <span class="text-gray-700 mr-4 user-info">{{ Auth::user()->name }}</span>
                        <button id="logout-button">登出</button>
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="container mx-auto py-8">
            @yield('content')
        </main>
    </div>

    {{-- Global Toast Message Container --}}
    <div id="toast-container"></div>
</body>
</html>
