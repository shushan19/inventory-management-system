@extends('layouts.app')
@section('title', 'Menu Items')

@section('content')

    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Menu Items</h1>
            <p class="text-gray-400 text-xs">Manage your restaurant menu and prices</p>
        </div>
        <a href="{{ route('menu-items.create') }}"
           class="bg-[#D4A373] hover:bg-[#c49263] text-white text-[12px] font-bold px-4 py-2 rounded-md transition shadow-sm shadow-orange-100">
            + Add Menu Item
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-gray-100 shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="bg-[#DBC1AC] text-[#4A3728]">
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Item Name</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Category</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight">Price</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight text-center">Available</th>
                <th class="px-5 py-3 font-bold text-[11px] uppercase tracking-tight text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="text-sm bg-white divide-y divide-gray-50">
            @forelse($menuItems as $item)
                <tr class="hover:bg-[#F2E3D5] transition-colors duration-150">
                    <td class="px-5 py-3 font-bold text-[#2D2D2D]">{{ $item->name }}</td>
                    <td class="px-5 py-3">
                        <span class="text-gray-500 text-xs bg-gray-50 px-2 py-0.5 rounded border border-gray-100">
                            {{ $item->category->name }}
                        </span>
                    </td>
                    <td class="px-5 py-3 font-bold text-gray-700">
                        Rs. {{ number_format($item->price, 2) }}
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if($item->is_available)
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Yes</span>
                        @else
                            <span class="bg-gray-100 text-gray-400 text-[10px] font-bold px-2 py-0.5 rounded uppercase">No</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex justify-end gap-3 items-center">

                            <a href="{{ route('recipes.show', $item) }}" title="Recipe" class="text-gray-400 hover:text-indigo-600 transition">
                                <i data-lucide="book-open" class="w-4 h-4"></i>
                            </a>

                            <a href="{{ route('menu-items.edit', $item) }}" title="Edit" class="text-[#D4A373] hover:text-[#c49263] transition">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>

                            <form action="{{ route('menu-items.destroy', $item) }}" method="POST"
                                  onsubmit="return confirm('Delete this menu item?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic text-xs">No menu items found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($menuItems->hasPages())
        <div class="mt-4">
            {{ $menuItems->links() }}
        </div>
    @endif

@endsection
