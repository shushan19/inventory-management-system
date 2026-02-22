<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISM - Inventory & Stock Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #FDFCFB; }
        .sidebar-item.active { background-color: #D4A373; color: white; }
        .sidebar-item:not(.active) { color: #4A4A4A; }
    </style>
</head>
<body class="min-h-screen text-gray-800">

<div class="flex min-h-screen">
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col fixed h-full shadow-sm">
        <div class="p-6">
            <div class="text-[#C48B58] font-bold text-xl">ISM</div>
            <div class="text-gray-400 text-xs font-medium">Inventory & Stock Manager</div>
        </div>

        <nav class="flex flex-col px-3 gap-1 mt-4">
            <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
            </a>
            <a href="{{ route('categories.index') }}" class="sidebar-item {{ request()->routeIs('categories.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all hover:bg-orange-50">
                <i data-lucide="layers" class="w-5 h-5"></i> Categories
            </a>
            <a href="{{ route('ingredients.index') }}" class="sidebar-item {{ request()->routeIs('ingredients.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all hover:bg-orange-50">
                <i data-lucide="leaf" class="w-5 h-5"></i> Ingredients
            </a>
            <a href="{{ route('menu-items.index') }}" class="sidebar-item {{ request()->routeIs('menu-items.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all hover:bg-orange-50">
                <i data-lucide="utensils-cross" class="w-5 h-5"></i> Menu Items
            </a>
            <a href="{{ route('orders.index') }}" class="sidebar-item {{ request()->routeIs('orders.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all hover:bg-orange-50">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i> Orders
            </a>
        </nav>

        <div class="mt-auto p-6 text-[10px] text-gray-400 border-t border-gray-50">
            © 2024 ISM System
        </div>
    </aside>

    <main class="ml-64 flex-1">

        <section class="px-10 pb-10 pt-10">
            @yield('content')
        </section>
    </main>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
@stack('scripts')
</body>
</html>
