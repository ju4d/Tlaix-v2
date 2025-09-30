<!doctype html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tlaix - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2c3e50',
                        secondary: '#ecf0f1',
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full bg-gray-50 font-sans">
    <!-- Navigation -->
    <nav class="bg-primary shadow-lg">
        <div class="px-4 py-4">
            <div class="flex items-center space-x-8">
                <a href="{{ url('/dashboard') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                    Dashboards
                </a>
                <a href="{{ route('inventory.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                    Inventario
                </a>
                <a href="{{ route('dishes.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                    Platillos
                </a>
                <a href="{{ route('orders.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                    Pedidos
                </a>
                <a href="{{ route('reports') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                    Reportes
                </a>
                <a href="{{ route('suppliers.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                    Proveedores
                </a>
                <a href="{{ route('logout') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-red-500 hover:bg-opacity-20 transition duration-300 ml-auto">
                    Salir
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-primary mb-4">@yield('title')</h1>
            @if(session('user_name')) 
                <div class="bg-secondary text-primary px-4 py-3 rounded-lg font-medium mb-6">
                    Bienvenido: {{ session('user_name') }} ({{ session('user_role') }})
                </div> 
            @endif
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            @yield('content')
        </div>
    </main>
</body>
</html>
