@extends('layouts.app')
@section('title', 'Recipe: ' . $menuItem->name)

@section('content')

    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Recipe: {{ $menuItem->name }}</h1>
            <p class="text-gray-400 text-xs">Define ingredients used per serving</p>
        </div>
        <a href="{{ route('menu-items.index') }}"
           class="bg-white border border-gray-200 text-gray-500 hover:text-gray-700 text-[12px] font-bold px-4 py-2 rounded-md transition shadow-sm">
            ← Back to Menu
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 overflow-hidden rounded-lg border border-gray-100 shadow-sm bg-white self-start">
            <div class="bg-[#DBC1AC] text-[#4A3728] px-5 py-3 text-[11px] font-bold uppercase tracking-tight">
                Current Ingredients
            </div>

            @if($menuItem->ingredients->isEmpty())
                <div class="px-5 py-12 text-center">
                    <p class="text-gray-400 text-xs italic">No ingredients added to this recipe yet.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="text-[10px] uppercase text-gray-400 bg-gray-50/50 border-b border-gray-50">
                        <th class="px-5 py-2 font-bold">Ingredient</th>
                        <th class="px-5 py-2 font-bold text-center">Qty / Serving</th>
                        <th class="px-5 py-2 font-bold">Unit</th>
                        <th class="px-5 py-2 font-bold text-right pr-8">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                    @foreach($menuItem->ingredients as $ingredient)
                        <tr class="hover:bg-[#F2E3D5] transition-colors duration-150">
                            <td class="px-5 py-3 font-bold text-[#2D2D2D]">{{ $ingredient->name }}</td>
                            <td class="px-5 py-3">
                                <form action="{{ route('recipes.update', [$menuItem, $ingredient]) }}" method="POST"
                                      class="flex gap-2 items-center justify-center">
                                    @csrf @method('PATCH')
                                    <input type="number" name="quantity_required"
                                           value="{{ $ingredient->pivot->quantity_required }}"
                                           step="0.001" min="0.001"
                                           class="bg-white border border-gray-200 rounded px-2 py-1 text-[11px] w-20 focus:outline-none focus:ring-1 focus:ring-[#D4A373]">
                                    <button class="text-[#D4A373] hover:text-[#c49263]" title="Save Changes">
                                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-gray-400 text-xs font-semibold uppercase">{{ $ingredient->unit }}</td>
                            <td class="px-5 py-3 text-right pr-8">
                                <form action="{{ route('recipes.destroy', [$menuItem, $ingredient]) }}" method="POST"
                                      onsubmit="return confirm('Remove this ingredient?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 transition">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="bg-white border border-orange-100 rounded-xl shadow-sm overflow-hidden self-start">
            <div class="px-5 py-3 border-b border-gray-50 bg-gray-50/30">
                <h3 class="text-sm font-bold text-gray-800">Add Ingredient</h3>
            </div>

            <form action="{{ route('recipes.store', $menuItem) }}" method="POST" class="px-5 py-5 space-y-4">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">Ingredient</label>
                    <select name="ingredient_id"
                            class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373]"
                            required>
                        <option value="">Select ingredient...</option>
                        @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">
                        Qty per 1 serving
                    </label>
                    <input type="number" name="quantity_required"
                           step="0.001" min="0.001"
                           placeholder="e.g. 0.5"
                           class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373]"
                           required>
                    <p class="text-[10px] text-gray-400 mt-2 italic leading-tight">Amount deducted from stock when this item is ordered.</p>
                </div>

                <button type="submit"
                        class="w-full bg-[#D4A373] hover:bg-[#c49263] text-white text-[12px] font-bold px-5 py-2.5 rounded-md transition-all shadow-sm shadow-orange-100">
                    + Add to Recipe
                </button>
            </form>
        </div>

    </div>
@endsection
