@extends('layouts.app')
@section('title', 'Edit Category')

@section('content')

    <div class="max-w-xl">
        <div class="mb-4">
            <h1 class="text-xl font-bold text-gray-900">Edit Category</h1>
            <p class="text-gray-400 text-xs">Update category details</p>
        </div>

        <div class="bg-white border border-orange-100 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-50 bg-gray-50/30">
                <h3 class="text-sm font-bold text-gray-800">Category Details</h3>
            </div>

            <form action="{{ route('categories.update', $category) }}" method="POST" class="px-5 py-5 space-y-4">
                @csrf @method('PUT')

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">
                        Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $category->name) }}"
                           class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373] transition-all
                              {{ $errors->has('name') ? 'border-red-200 bg-red-50/20' : '' }}">
                    @error('name')
                    <p class="text-red-500 text-[10px] mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373] transition-all">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-50">
                    <button type="submit"
                            class="bg-[#D4A373] hover:bg-[#c49263] text-white text-[12px] font-bold px-5 py-2 rounded-md transition-all">
                        Update
                    </button>
                    <a href="{{ route('categories.index') }}"
                       class="bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 text-[12px] font-bold px-5 py-2 rounded-md transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
