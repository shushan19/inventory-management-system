@extends('layouts.app')
@section('title', 'Edit Menu Item')

@section('content')

    <div class="max-w-xl">
        <div class="mb-4">
            <h1 class="text-xl font-bold text-gray-900">Edit Menu Item</h1>
            <p class="text-gray-400 text-xs">Update pricing, availability, and details for this item</p>
        </div>

        <div class="bg-white border border-orange-100 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-50 bg-gray-50/30">
                <h3 class="text-sm font-bold text-gray-800">Item Details</h3>
            </div>

            <form action="{{ route('menu-items.update', $menuItem) }}" method="POST"
                  enctype="multipart/form-data" class="px-5 py-5 space-y-4">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">Category</label>
                        <select name="category_id"
                                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373] transition-all">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $menuItem->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">Name</label>
                        <input type="text" name="name"
                               value="{{ old('name', $menuItem->name) }}"
                               class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373] transition-all">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">Price (Rs.)</label>
                        <input type="number" name="price"
                               value="{{ old('price', $menuItem->price) }}"
                               step="0.01" min="0"
                               class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373] transition-all">
                    </div>

                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="is_available" id="available"
                                   value="1" {{ old('is_available', $menuItem->is_available) ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#D4A373] border-gray-300 rounded focus:ring-[#D4A373]">
                            <span class="text-[12px] font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Available for ordering</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373] transition-all">{{ old('description', $menuItem->description) }}</textarea>
                </div>

                <div class="pt-1">
                    <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">Update Image</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-[11px] text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0
                              file:text-[11px] file:font-bold file:bg-gray-100 file:text-gray-600 hover:file:bg-[#F2E3D5] hover:file:text-[#4A3728] transition-all">
                    @if($menuItem->image)
                        <div class="mt-2 flex items-center gap-2 text-[10px] text-[#D4A373] font-bold bg-orange-50/50 px-2 py-1 rounded inline-flex">
                            <i data-lucide="image" class="w-3 h-3"></i>
                            Current: {{ Str::limit($menuItem->image, 30) }}
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-50 mt-2">
                    <button type="submit"
                            class="bg-[#D4A373] hover:bg-[#c49263] text-white text-[12px] font-bold px-5 py-2 rounded-md transition-all shadow-sm">
                        Update Item
                    </button>
                    <a href="{{ route('menu-items.index') }}"
                       class="bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 text-[12px] font-bold px-5 py-2 rounded-md transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
