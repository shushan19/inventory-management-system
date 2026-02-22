@extends('layouts.app')
@section('title', 'Ingredients')

@section('content')

    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Ingredients / Stock</h1>
            <p class="text-gray-400 text-xs">Manage inventory levels and stock movements</p>
        </div>
        <a href="{{ route('ingredients.create') }}"
           class="bg-[#D4A373] hover:bg-[#c49263] text-white text-[12px] font-bold px-4 py-2 rounded-md transition shadow-sm shadow-orange-100">
            + Add Ingredient
        </a>
    </div>

    @if($lowStock->isNotEmpty())
        <div class="mb-4 px-4 py-2 bg-red-50 border border-red-100 text-red-700 rounded-lg text-[11px] font-medium flex items-center gap-2">
            <span><strong>Low Stock Alert:</strong> {{ $lowStock->pluck('name')->join(', ') }} below minimum.</span>
        </div>
    @endif

    <div class="mt-4 overflow-hidden rounded-lg border border-gray-100 shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="bg-[#DBC1AC] text-[#4A3728]">
                <th class="px-5 py-3 font-bold text-[12px] tracking-tight uppercase">Ingredient</th>
                <th class="px-5 py-3 font-bold text-[12px] tracking-tight uppercase">Unit</th>
                <th class="px-5 py-3 font-bold text-[12px] tracking-tight uppercase">Current Stock</th>
                <th class="px-5 py-3 font-bold text-[12px] tracking-tight uppercase">Min. Level</th>
                <th class="px-5 py-3 font-bold text-[12px] tracking-tight uppercase">Status</th>
                <th class="px-5 py-3 font-bold text-[12px] tracking-tight uppercase text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="text-sm bg-white divide-y divide-gray-50">
            @forelse($ingredients as $ingredient)
                <tr class="hover:bg-[#F2E3D5] transition-colors duration-150 {{ $ingredient->isLowStock() ? 'bg-red-50/30' : '' }}">
                    <td class="px-5 py-3 font-bold text-[#2D2D2D]">{{ $ingredient->name }}</td>
                    <td class="px-5 py-3 text-[#7A828A] text-xs font-semibold uppercase">{{ $ingredient->unit }}</td>
                    <td class="px-5 py-3 font-bold {{ $ingredient->isLowStock() ? 'text-red-600' : 'text-gray-700' }}">
                        {{ $ingredient->current_stock }}
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $ingredient->minimum_stock }}</td>
                    <td class="px-5 py-3">
                        @if($ingredient->isLowStock())
                            <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Low</span>
                        @else
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase">OK</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex justify-end gap-3 items-center">

                            <button onclick="document.getElementById('stockForm{{ $ingredient->id }}').classList.toggle('hidden')"
                                    title="Stock In" class="text-green-600 hover:text-green-800 transition">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            </button>

                            <a href="{{ route('ingredients.movements', $ingredient) }}" title="History" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="history" class="w-4 h-4"></i>
                            </a>

                            <a href="{{ route('ingredients.edit', $ingredient) }}" title="Edit" class="text-[#D4A373] hover:text-[#c49263]">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>

                            <form action="{{ route('ingredients.destroy', $ingredient) }}" method="POST"
                                  onsubmit="return confirm('Delete ingredient?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>


                        <div id="stockForm{{ $ingredient->id }}" class="hidden mt-2 pt-2 border-t border-gray-100/50">
                            <form action="{{ route('ingredients.add-stock', $ingredient) }}" method="POST"
                                  class="flex gap-2 items-center justify-end">
                                @csrf
                                <input type="number" name="quantity" step="0.001" min="0.001"
                                       placeholder="Qty"
                                       class="bg-gray-50 border border-gray-200 rounded px-2 py-1 text-[11px] w-20 focus:outline-none focus:ring-1 focus:ring-[#D4A373]"
                                       required>
                                <input type="text" name="notes"
                                       placeholder="Note"
                                       class="bg-gray-50 border border-gray-200 rounded px-2 py-1 text-[11px] w-24 focus:outline-none focus:ring-1 focus:ring-[#D4A373]">
                                <button class="bg-[#D4A373] text-white text-[10px] font-bold px-2 py-1 rounded">
                                    Add
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic text-xs">No ingredients found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($ingredients->hasPages())
        <div class="mt-4">
            {{ $ingredients->links() }}
        </div>
    @endif

@endsection
