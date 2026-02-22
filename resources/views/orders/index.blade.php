@extends('layouts.app')
@section('title', 'Orders')

@section('content')

    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Orders</h1>
            <p class="text-gray-400 text-xs">Track and manage customer transactions</p>
        </div>
        <a href="{{ route('orders.create') }}"
           class="bg-[#D4A373] hover:bg-[#c49263] text-white text-[12px] font-bold px-4 py-2 rounded-md transition shadow-sm shadow-orange-100">
            + New Order
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-gray-100 shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="bg-[#DBC1AC] text-[#4A3728]">
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Order #</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Items</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Total</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Status</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Date</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="text-sm bg-white divide-y divide-gray-50">
            @forelse($orders as $order)
                @php
                    $colors = [
                        'pending'   => 'bg-gray-100 text-gray-500',
                        'preparing' => 'bg-orange-100 text-[#D4A373]',
                        'delivered' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-50 text-red-600',
                    ];
                @endphp
                <tr class="hover:bg-[#F2E3D5] transition-colors duration-150">
                    <td class="px-5 py-3 font-bold text-[#2D2D2D]">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">
                        <span class="font-semibold text-gray-700">{{ $order->orderItems->count() }}</span> items
                    </td>
                    <td class="px-5 py-3 font-bold text-gray-800">Rs. {{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">
                        {{ $order->created_at->format('d M, H:i') }}
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('orders.show', $order) }}"
                           class="inline-flex items-center gap-1 text-[#D4A373] hover:text-[#c49263] font-bold text-[11px] uppercase tracking-wider transition">
                            Details <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic text-xs">No orders recorded yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif

@endsection
