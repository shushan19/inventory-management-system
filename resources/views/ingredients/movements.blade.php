@extends('layouts.app')
@section('title', 'Stock History')

@section('content')

    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Stock History: {{ $ingredient->name }}</h1>
            <p class="text-gray-400 text-xs">Full audit log of stock entries and deductions</p>
        </div>
        <a href="{{ route('ingredients.index') }}"
           class="bg-white border border-gray-200 text-gray-500 hover:text-gray-700 text-[12px] font-bold px-4 py-2 rounded-md transition shadow-sm">
            ← Back to List
        </a>
    </div>

    <div class="mb-6">
        <div class="inline-block bg-white border border-orange-100 rounded-xl shadow-sm px-5 py-3">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Current Inventory</div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold {{ $ingredient->isLowStock() ? 'text-red-500' : 'text-[#D4A373]' }}">
                    {{ $ingredient->current_stock }}
                </span>
                <span class="text-xs font-semibold text-gray-400 uppercase">{{ $ingredient->unit }}</span>
            </div>
            @if($ingredient->isLowStock())
                <div class="text-[10px] text-red-400 mt-1 font-medium italic">⚠️ Below minimum ({{ $ingredient->minimum_stock }})</div>
            @endif
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-100 shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="bg-[#DBC1AC] text-[#4A3728]">
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Date & Time</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Type</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Quantity</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Reference</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight text-right">Notes</th>
            </tr>
            </thead>
            <tbody class="text-sm bg-white divide-y divide-gray-50">
            @forelse($movements as $m)
                <tr class="hover:bg-[#F2E3D5] transition-colors duration-150">
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $m->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-5 py-3">
                        @if($m->type === 'manual_add')
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Stock In</span>
                        @else
                            <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Deduction</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 font-bold {{ $m->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }} <span class="text-[10px] font-normal opacity-70">{{ $ingredient->unit }}</span>
                    </td>
                    <td class="px-5 py-3">
                        @if($m->order)
                            <a href="{{ route('orders.show', $m->order) }}"
                               class="text-[#D4A373] hover:underline font-bold text-xs">
                                ORD-{{ str_pad($m->order_id, 3, '0', STR_PAD_LEFT) }}
                            </a>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs text-right italic">{{ $m->notes ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic text-xs">No movement history recorded yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($movements->hasPages())
        <div class="mt-4">
            {{ $movements->links() }}
        </div>
    @endif

@endsection
