@extends('layouts.app')
@section('title', 'New Order')

@section('content')

    <div class="max-w-2xl">
        <div class="mb-4">
            <h1 class="text-xl font-bold text-gray-900">New Order</h1>
            <p class="text-gray-400 text-xs">Create a new guest transaction</p>
        </div>

        <div class="bg-white border border-orange-100 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-50 bg-gray-50/30">
                <h3 class="text-sm font-bold text-gray-800">Order Details</h3>
            </div>

            <form action="{{ route('orders.store') }}" method="POST" class="px-5 py-5 space-y-4" id="orderForm">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-tight">Order Items</label>

                    <div id="items-container" class="space-y-2">
                        <div class="item-row flex gap-2 items-center">
                            <select name="items[0][menu_item_id]"
                                    class="flex-1 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373] transition-all"
                                    required>
                                <option value="">Select menu item...</option>
                                @foreach($menuItems->groupBy('category.name') as $categoryName => $items)
                                    <optgroup label="{{ $categoryName }}">
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }} — Rs.{{ number_format($item->price, 2) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                            <input type="number" name="items[0][quantity]"
                                   value="1" min="1"
                                   class="w-16 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-center focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373]"
                                   required>

                            <button type="button" disabled
                                    class="w-8 h-8 flex items-center justify-center rounded-md bg-gray-50 text-gray-300 cursor-not-allowed">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" id="addItem"
                            class="mt-3 text-[11px] text-[#D4A373] hover:text-[#c49263] font-bold uppercase tracking-wider flex items-center gap-1 transition">
                        <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Add another item
                    </button>
                </div>

                <div class="pt-2">
                    <label class="block text-[11px] font-bold text-gray-600 mb-1 uppercase tracking-tight">Notes (optional)</label>
                    <textarea name="notes" rows="2"
                              placeholder="Special instructions..."
                              class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373] transition-all"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-50 mt-2">
                    <button type="submit"
                            class="bg-[#D4A373] hover:bg-[#c49263] text-white text-[12px] font-bold px-6 py-2 rounded-md transition-all shadow-sm">
                        Place Order
                    </button>
                    <a href="{{ route('orders.index') }}"
                       class="bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 text-[12px] font-bold px-6 py-2 rounded-md transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let rowIndex = 1;

        // This variable pre-renders the options to avoid repeated Blade loops in JS
        const optionsHtml = `
            @foreach($menuItems->groupBy('category.name') as $categoryName => $items)
        <optgroup label="{{ $categoryName }}">
                    @foreach($items as $item)
        <option value="{{ $item->id }}">{{ $item->name }} — Rs.{{ number_format($item->price, 2) }}</option>
                    @endforeach
        </optgroup>
@endforeach
        `;

        document.getElementById('addItem').addEventListener('click', function () {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'item-row flex gap-2 items-center';

            row.innerHTML = `
                <select name="items[${rowIndex}][menu_item_id]"
                        class="flex-1 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373] transition-all"
                        required>
                    <option value="">Select menu item...</option>
                    ${optionsHtml}
                </select>
                <input type="number" name="items[${rowIndex}][quantity]"
                       value="1" min="1"
                       class="w-16 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-center focus:outline-none focus:ring-1 focus:ring-[#D4A373] focus:border-[#D4A373]"
                       required>
                <button type="button" class="remove-btn w-8 h-8 flex items-center justify-center rounded-md bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition-all">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            `;

            container.appendChild(row);

            // Re-initialize Lucide icons for the new row
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Attach remove handler
            row.querySelector('.remove-btn').addEventListener('click', () => row.remove());
            rowIndex++;
        });
    </script>
@endpush
