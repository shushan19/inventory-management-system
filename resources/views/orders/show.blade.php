@extends('layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')

    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-gray-400 text-xs">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <a href="{{ route('orders.index') }}"
           class="bg-white border border-gray-200 text-gray-500 hover:text-gray-700 text-[12px] font-bold px-4 py-2 rounded-md transition shadow-sm">
            ← Back to List
        </a>
    </div>

    @php
        $colors = [
            'pending'   => 'bg-gray-100 text-gray-500',
            'preparing' => 'bg-orange-100 text-[#D4A373]',
            'delivered' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-50 text-red-600',
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-orange-100 rounded-xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3 border-b border-gray-50 bg-gray-50/30">
                    <span class="text-sm font-bold text-gray-800 uppercase tracking-tight">Items Summary</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $colors[$order->status] ?? '' }}">
                        {{ $order->status }}
                    </span>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="bg-[#DBC1AC] text-[#4A3728]">
                        <th class="px-5 py-2 font-bold text-[11px] uppercase tracking-tight">Item</th>
                        <th class="px-5 py-2 font-bold text-[11px] uppercase tracking-tight text-center">Qty</th>
                        <th class="px-5 py-2 font-bold text-[11px] uppercase tracking-tight">Unit Price</th>
                        <th class="px-5 py-2 font-bold text-[11px] uppercase tracking-tight text-right">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                    @foreach($order->orderItems as $item)
                        <tr class="hover:bg-[#F2E3D5] transition-colors duration-150">
                            <td class="px-5 py-3 font-bold text-[#2D2D2D]">{{ $item->menuItem->name }}</td>
                            <td class="px-5 py-3 text-center text-gray-600 font-medium">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-gray-500">Rs. {{ number_format($item->price, 2) }}</td>
                            <td class="px-5 py-3 font-bold text-gray-800 text-right">Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50/50">
                        <td colspan="3" class="px-5 py-3 text-right font-bold text-gray-500 uppercase text-[11px] tracking-widest">Total Amount</td>
                        <td class="px-5 py-3 font-black text-[#D4A373] text-right text-lg">Rs. {{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            @if($order->stockMovements->isNotEmpty())
                <div class="bg-white border border-orange-100 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-50 bg-green-50/50">
                        <span class="text-[11px] font-bold text-green-700 uppercase tracking-tight">Inventory Impact Details</span>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="text-[10px] uppercase text-gray-400 border-b border-gray-50">
                            <th class="px-5 py-2 font-bold">Ingredient</th>
                            <th class="px-5 py-2 font-bold text-right">Deducted Quantity</th>
                        </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                        @foreach($order->stockMovements as $mv)
                            <tr>
                                <td class="px-5 py-2.5 text-gray-700 font-medium">{{ $mv->ingredient->name }}</td>
                                <td class="px-5 py-2.5 text-red-600 font-bold text-right text-xs">
                                    - {{ abs($mv->quantity) }} {{ $mv->ingredient->unit }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-orange-100 rounded-xl shadow-sm p-5">
                <h2 class="text-[11px] font-bold text-gray-600 mb-4 uppercase tracking-tight">Process Order</h2>

                @if(!$order->hasBeenDelivered() && $order->status !== 'cancelled')
                    <form action="{{ route('orders.update-status', $order) }}" method="POST" class="space-y-4">
                        @csrf @method('PATCH')

                        <select name="status"
                                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373]">
                            @foreach(['pending', 'preparing', 'delivered', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>

                        <button class="w-full bg-[#D4A373] hover:bg-[#c49263] text-white text-[12px] font-bold px-4 py-2.5 rounded-md transition shadow-sm">
                            Update Order Status
                        </button>
                    </form>

                    <div class="mt-4 p-3 bg-orange-50/50 border border-orange-100 rounded-lg text-[10px] text-orange-800 leading-relaxed italic">
                        Stock levels are updated automatically upon selecting Delivered status.
                    </div>
                @else
                    <div class="text-center py-2">
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Current Status</div>
                        <div class="text-sm font-bold text-gray-800 uppercase">{{ $order->status }}</div>
                        @if($order->delivered_at)
                            <div class="text-[10px] text-gray-400 mt-1 italic">Processed on {{ $order->delivered_at->format('d M, H:i') }}</div>
                        @endif
                    </div>
                @endif
            </div>

            @if($order->notes)
                <div class="bg-white border border-orange-100 rounded-xl shadow-sm p-5">
                    <h2 class="text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-tight">Order Notes</h2>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

    </div>
@endsection
