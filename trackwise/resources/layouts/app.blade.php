<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finance Tracker &mdash; @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-700">
                Finance Tracker
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600">
                    Dashboard
                </a>
                <a href="{{ route('transactions.index') }}" class="text-gray-600 hover:text-blue-600">
                    Transactions
                </a>
                <a href="{{ route('transactions.create') }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm">
                    + Add New
                </a>
                <span class="text-gray-500 text-sm">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-500 text-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 m-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

</body>
</html>