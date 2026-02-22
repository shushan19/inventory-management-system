<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(private StockService $stockService) {}

    public function index()
    {
        $orders = Order::with('orderItems.menuItem')->latest()->paginate(15);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $menuItems = MenuItem::where('is_available', true)->with('category')->get();
        return view('orders.create', compact('menuItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'notes'              => 'nullable|string|max:500',
            'items'              => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($data) { //in case if anything fails while creating order
            // order is created
            $order = Order::create([
                'status'       => 'pending',
                'total_amount' => 0,
                'notes'        => $data['notes'] ?? null,
            ]);

            $totalAmount = 0;

            foreach ($data['items'] as $itemData) {
                $menuItem = MenuItem::with('ingredients')->findOrFail($itemData['menu_item_id']);

                // Check stock availability per item
                $shortfalls = $menuItem->checkStockAvailability($itemData['quantity']);
                //if there is any shortfalls then it will throw exception error and return database to state before the order was created
                if (!empty($shortfalls)) {
                    throw ValidationException::withMessages([
                        'stock' => array_map(fn($s) =>
                        "{$menuItem->name}: {$s['ingredient']} needs {$s['required']}{$s['unit']}, only {$s['available']}{$s['unit']} available",
                            $shortfalls
                        ),
                    ]);
                }

                $order->orderItems()->create([
                    'menu_item_id' => $menuItem->id,
                    'quantity'     => $itemData['quantity'],
                    'price'        => $menuItem->price,
                ]);

                $totalAmount += $menuItem->price * $itemData['quantity'];
            }

            $order->update(['total_amount' => $totalAmount]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully.');
        });
    }

    public function show(Order $order)
    {
        $order->load('orderItems.menuItem.ingredients', 'stockMovements.ingredient');
        return view('orders.show', compact('order'));
    }
     // update order status.
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,preparing,delivered,cancelled',
        ]);

    //checking whether that order is already delivered or not
        if ($order->hasBeenDelivered()) {
            return back()->with('error', 'Order has already been delivered.');
        }

        return DB::transaction(function () use ($data, $order) {
            $order->update([
                'status'       => $data['status'],
                'delivered_at' => $data['status'] === 'delivered' ? now() : null,
            ]);

          //deduct the ingredients if the order status is delivered using StockService
            if ($data['status'] === 'delivered') {
                $order->load('orderItems.menuItem.ingredients');
                $this->stockService->deductStockForOrder($order);
            }

            return back()->with('success', "Order status updated to {$data['status']}.");
        });
    }
}
