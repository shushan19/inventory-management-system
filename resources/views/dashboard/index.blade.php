@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <header class="flex justify-between items-center px-10 py-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-400 text-sm">Welcome to your ISM System</p>
        </div>
        <div class="flex items-center gap-4 text-gray-500">
            <i data-lucide="settings" class="w-5 h-5 cursor-pointer hover:text-gray-800"></i>
            <i data-lucide="user-circle" class="w-6 h-6 cursor-pointer hover:text-gray-800"></i>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white border border-orange-100 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-900 font-semibold text-sm mb-4">Total Orders</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
                <span class="text-[10px] font-bold text-green-500 bg-green-50 px-1.5 py-0.5 rounded">+12%</span>
            </div>
            <p class="text-gray-400 text-[11px] mt-1">All time orders</p>
        </div>

        <div class="bg-white border border-orange-100 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-900 font-semibold text-sm mb-4">Completed Orders</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold text-gray-900">{{ $deliveredToday }}</p>
                <span class="text-[10px] font-bold text-green-500 bg-green-50 px-1.5 py-0.5 rounded">+8%</span>
            </div>
            <p class="text-gray-400 text-[11px] mt-1">Successfully delivered</p>
        </div>

        <div class="bg-white border border-orange-100 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-900 font-semibold text-sm mb-4">Total Revenue</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold text-gray-900">Rs.{{ number_format($totalRevenue, 2) }}</p>
                <span class="text-[10px] font-bold text-green-500 bg-green-50 px-1.5 py-0.5 rounded">+15%</span>
            </div>
            <p class="text-gray-400 text-[11px] mt-1">From completed orders</p>
        </div>

        <div class="bg-white border border-orange-100 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-900 font-semibold text-sm mb-4">Low Stock Items</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold text-gray-900">{{ $lowStockCount }}</p>
                <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded">-5%</span>
            </div>
            <p class="text-gray-400 text-[11px] mt-1">Items below minimum</p>
        </div>

    </div>

    <div class="space-y-6">

        <div class="bg-white border border-orange-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="font-bold text-gray-900 text-lg">Recent Orders</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="text-[11px] uppercase tracking-wider text-gray-400 bg-gray-50/50">
                        <th class="px-6 py-3 font-medium">Order #</th>
                        <th class="px-6 py-3 font-medium">Customer</th>
                        <th class="px-6 py-3 font-medium text-right">Amount</th>
                        <th class="px-6 py-3 font-medium text-center">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Date</th>
                    </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-orange-50/20 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-700">ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 text-gray-500">John Doe</td> <td class="px-6 py-4 text-right font-semibold text-gray-900">Rs. {{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-[#D1FAE5] text-[#065F46] uppercase">Completed</span>
                            </td>
                            <td class="px-6 py-4 text-right text-gray-400">2/20/2024</td> </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">No recent orders found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border border-orange-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="font-bold text-gray-900 text-lg">Low Stock Items</h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @forelse($lowStockItems as $item)
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl">
                            <span class="text-sm text-gray-700 font-medium">{{ $item->name }}</span>
                            <span class="text-sm font-bold text-red-500 bg-red-50 px-2 py-1 rounded">{{ $item->current_stock }}</span>
                        </div>
                    @empty
                        <div class="col-span-full py-4 text-center text-gray-400 text-sm italic">Items below minimum will appear here.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection
