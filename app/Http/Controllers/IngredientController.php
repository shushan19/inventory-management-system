<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Services\StockService;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    //calling to Stock service for calculating low stocks and adding stocks
    public function __construct(private StockService $stockService) {}

    public function index()
    {
        $ingredients = Ingredient::latest()->paginate(15);
        $lowStock    = $this->stockService->getLowStockIngredients();

        return view('ingredients.index', compact('ingredients', 'lowStock'));
    }

    public function create()
    {
        return view('ingredients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'unit'          => 'required|in:kg,gram,piece,liter,ml',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        Ingredient::create($data);

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient added successfully.');
    }

    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'unit'          => 'required|in:kg,gram,piece,liter,ml',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        $ingredient->update($data);

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient updated.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient deleted.');
    }
    public function addStock(Request $request, Ingredient $ingredient)
    {
        $data = $request->validate([
            'quantity' => 'required|numeric|min:0.001',
            'notes'    => 'nullable|string|max:255',
        ]);

        $this->stockService->addStock($ingredient, $data['quantity'], $data['notes'] ?? null);

        return back()->with('success', "Added {$data['quantity']} {$ingredient->unit} to {$ingredient->name}.");
    }

    public function movements(Ingredient $ingredient)
    {
        $movements = $ingredient->stockMovements()
            ->with('order')
            ->latest()
            ->paginate(20);

        return view('ingredients.movements', compact('ingredient', 'movements'));
    }
}
