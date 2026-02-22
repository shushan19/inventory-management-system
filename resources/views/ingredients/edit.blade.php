@extends('layouts.app')
@section('title', 'Edit Ingredient')

@section('content')

    <div class="max-w-2xl">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Ingredient</h1>
            <p class="text-gray-400 text-sm">Update inventory details for your ingredient</p>
        </div>

        <div class="bg-white border border-orange-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/30">
                <h3 class="font-bold text-gray-800">Ingredient Details</h3>
                <p class="text-xs text-gray-400 mt-1">Modify name and stock alert thresholds.</p>
            </div>

            <form action="{{ route('ingredients.update', $ingredient) }}" method="POST" class="p-8 space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-gray-700 mb-2">
                            INGREDIENT NAME <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name"
                               value="{{ old('name', $ingredient->name) }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-[#D4A373] transition-all
                                  {{ $errors->has('name') ? 'border-red-200 bg-red-50/30' : '' }}">
                        @error('name')
                        <p class="text-red-500 text-[11px] mt-2 font-medium uppercase tracking-tighter">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-gray-400 mb-2">UNIT</label>
                        <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm text-gray-400">
                            {{ strtoupper($ingredient->unit) }}
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 italic">Cannot be changed after creation.</p>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-gray-400 mb-2">CURRENT STOCK</label>
                        <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm text-gray-500 font-bold">
                            {{ $ingredient->current_stock }} <span class="text-[10px] font-normal text-gray-400">{{ $ingredient->unit }}</span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 italic">Update via "Stock In" on list page.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-gray-700 mb-2 uppercase">
                            Minimum Stock Alert Level
                        </label>
                        <input type="number" name="minimum_stock"
                               value="{{ old('minimum_stock', $ingredient->minimum_stock) }}"
                               step="0.001" min="0"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-[#D4A373] transition-all">
                        <p class="text-[11px] text-gray-400 mt-2 font-medium italic">You will receive a notification when stock falls below this level.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-6 border-t border-gray-50 mt-8">
                    <button type="submit"
                            class="bg-[#D4A373] hover:bg-[#c49263] text-white text-[13px] font-bold px-8 py-3 rounded-lg transition-all shadow-sm shadow-orange-100">
                        Update Ingredient
                    </button>
                    <a href="{{ route('ingredients.index') }}"
                       class="bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 text-[13px] font-bold px-8 py-3 rounded-lg transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
