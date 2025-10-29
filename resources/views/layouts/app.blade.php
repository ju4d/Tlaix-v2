<!doctype html>
<html lang="es" class="h-full">
<head>
    <link rel="icon" type="image/png" href="{{ asset('Tlaix.PNG') }}">
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
    <!-- Navigation (oculta en páginas de autenticación) -->
    @unless(request()->routeIs('login') || request()->routeIs('register') || request()->is('login') || request()->is('register'))
    <div class="h-20"><!-- Spacer for fixed nav --></div>
    <nav class="bg-primary shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-8 whitespace-nowrap">
                    @php $role = strtolower(trim((string) session('user_role'))); @endphp
                    @if($role == 'admin')
                        <div class="relative" x-data="{ openDash: false }">
                            <button @click="openDash = !openDash" @mouseenter="openDash = true" @mouseleave="setTimeout(() => { if (!document.querySelector('#submenu-dash:hover')) openDash = false }, 100)" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300 focus:outline-none flex items-center">
                                Dashboard
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div id="submenu-dash" x-show="openDash" @mouseenter="openDash = true" @mouseleave="openDash = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50" style="display: none;">
                                <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white transition rounded-md">Dashboards</a>
                                <a href="{{ route('reports') }}" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white transition rounded-md">Reportes</a>
                                <a href="{{ route('waste.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white transition rounded-md">Mermas</a>
                            </div>
                        </div>
                        <a href="{{ route('inventory.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">Inventario</a>
                        <a href="{{ route('dishes.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">Platillos</a>
                        <a href="{{ route('orders.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">Pedidos</a>
                        <a href="{{ route('suppliers.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">Proveedores</a>
                        <a href="{{ route('users.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">Usuarios</a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @mouseenter="open = true" @mouseleave="setTimeout(() => { if (!document.querySelector('#submenu-areas:hover')) open = false }, 100)" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300 focus:outline-none flex items-center">
                                Áreas
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div id="submenu-areas" x-show="open" @mouseenter="open = true" @mouseleave="open = false" class="absolute left-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50" style="display: none;">
                                <a href="{{ route('mesero.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white transition rounded-md">Mesero</a>
                                <a href="{{ route('cocina.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-primary hover:text-white transition rounded-md">Cocina</a>
                            </div>
                        </div>
                    @elseif($role == 'mesero')
                        <a href="{{ route('mesero.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">Mesero</a>
                    @elseif($role == 'chef' || $role == 'chip')
                        <a href="{{ route('cocina.index') }}" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">Cocina</a>
                    @endif
                    <!-- Botón de cuenta para todos los roles -->
                    <div class="relative" x-data="{ openAccount: false }">
                        <button @click="openAccount = !openAccount" @mouseenter="openAccount = true" @mouseleave="setTimeout(() => { if (!document.querySelector('#submenu-account:hover')) openAccount = false }, 100)" class="text-white font-semibold text-lg px-6 py-3 rounded-lg hover:bg-primary hover:bg-opacity-10 transition duration-300 focus:outline-none flex items-center">
                            Cuenta
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div id="submenu-account" x-show="openAccount" @mouseenter="openAccount = true" @mouseleave="openAccount = false" class="absolute left-0 mt-2 w-32 bg-white rounded shadow-lg z-50" style="display: none;">
                            <span class="block px-4 py-2 text-gray-800">{{ session('user_name') }}</span>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-red-600 hover:bg-primary hover:text-white transition rounded-md">Cerrar sesión</a>
                        </div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                </div>
                <img src="{{ asset('Tlaix.PNG') }}" class="h-14 w-14" >
            </div>
        </div>
    </nav>
    @endunless

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 {{ request()->routeIs('login') || request()->routeIs('register') || request()->is('login') || request()->is('register') ? 'py-16' : 'mt-24 pb-8' }}">
        @unless(request()->routeIs('login') || request()->routeIs('register') || request()->is('login') || request()->is('register'))
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
        @else
        <!-- Contenido de autenticación sin navegación -->
        @yield('content')
        @endunless
    </main>
</body>
</html>
