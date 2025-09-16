<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tlaix - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/modern-normalize/modern-normalize.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>body{font-family: Arial; padding:20px;} nav a{margin-right:10px;}</style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav>
        <a href="{{ url('/dashboard') }}">Dashboard</a>
        <a href="{{ route('inventory.index') }}">Inventory</a>
        <a href="{{ route('dishes.index') }}">Dishes</a>
        <a href="{{ route('orders.index') }}">Orders</a>
        <a href="{{ route('reports') }}">Reports</a>
        <a href="{{ route('logout') }}">Logout</a>
    </nav>
    <hr>
    <h1>@yield('title')</h1>
    @if(session('user_name')) <div>Welcome: {{ session('user_name') }} ({{ session('user_role') }})</div> @endif
    @yield('content')
</body>
</html>
