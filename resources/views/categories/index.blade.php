@extends('layouts.app')
@section('title', 'Categories')

@section('content')

    <div class="flex items-center justify-between mb-2 ">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="text-gray-400 text-sm">Manage your inventory categories</p>
        </div>
        <a href="{{ route('categories.create') }}"
           class="bg-[#D4A373] hover:bg-[#c49263] text-white text-[13px] font-medium px-4 py-2 rounded-lg transition flex items-center gap-2">
            <span>+</span> Add Category
        </a>
    </div>

    <div class="mt-8 overflow-hidden rounded-lg border border-gray-100 shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="bg-[#DBC1AC] text-[#4A3728]">
                <th class="px-6 py-4 font-bold text-sm tracking-tight">Category Name</th>
                <th class="px-6 py-4 font-bold text-sm tracking-tight">Description</th>
                <th class="px-6 py-4 font-bold text-sm tracking-tight">Created</th>
                <th class="px-6 py-4 font-bold text-sm tracking-tight text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="text-sm bg-white">
            @forelse($categories as $category)
                <tr class="hover:bg-[#F2E3D5] transition-colors duration-200 border-b border-gray-50 last:border-0">
                    <td class="px-6 py-5 font-bold text-[#2D2D2D]">{{ $category->name }}</td>
                    <td class="px-6 py-5 text-[#7A828A]">{{ $category->description ?? 'No description provided' }}</td>
                    <td class="px-6 py-5 text-[#7A828A]">{{ $category->created_at ? $category->created_at->format('m/d/Y') : '1/15/2024' }}</td>
                    <td class="px-6 py-5">
                        <div class="flex justify-end gap-4">
                            <a href="{{ route('categories.edit', $category) }}" class="text-[#D4A373] hover:text-[#c49263] transition">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button class="text-[#E63946] hover:text-red-700 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">No categories found in the system.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @endif

@endsection
