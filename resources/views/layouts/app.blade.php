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
        <a href="{{ url('/dashboard') }}">Dashboards</a>
        <a href="{{ route('inventory.index') }}">Inventario</a>
        <a href="{{ route('dishes.index') }}">Platillos</a>
        <a href="{{ route('orders.index') }}">Pedidos</a>
        <a href="{{ route('reports') }}">Reportes</a>
        <a href="{{ route('suppliers.index') }}">Proveedores</a>
        <a href="{{ route('logout') }}">Salir</a>
    </nav>
    <hr>
    <h1>@yield('title')</h1>
    @if(session('user_name')) <div>Bienvenido: {{ session('user_name') }} ({{ session('user_role') }})</div> @endif
    @yield('content')
</body>
</html>
