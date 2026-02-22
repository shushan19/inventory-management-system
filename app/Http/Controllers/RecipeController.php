<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function show(MenuItem $menuItem)
    {
        //load the ingredients that belong to this menu item
        $menuItem->load('ingredients');

        //get all ingredients so the user can pick from them in the form
        $ingredients = Ingredient::orderBy('name')->get();

        return view('recipes.show', compact('menuItem', 'ingredients'));
    }

    public function store(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'ingredient_id'     => 'required|exists:ingredients,id',
            'quantity_required' => 'required|numeric|min:0.001',
        ]);

        // Check if this ingredient is already in the recipe
        $existing = Recipe::where('menu_item_id', $menuItem->id)
            ->where('ingredient_id', $request->ingredient_id)
            ->first();

        if ($existing) {
            //if recipe already exits then just update quantity
            $existing->update([
                'quantity_required' => $request->quantity_required,
            ]);
        } else {
           //if the ingredient is newer than create new recipe
            Recipe::create([
                'menu_item_id'      => $menuItem->id,
                'ingredient_id'     => $request->ingredient_id,
                'quantity_required' => $request->quantity_required,
            ]);
        }

        return back()->with('success', 'Recipe ingredient saved.');
    }

    public function update(Request $request, MenuItem $menuItem, Ingredient $ingredient)
    {
        $request->validate([
            'quantity_required' => 'required|numeric|min:0.001',
        ]);

        // find the recipe row for this menu item and ingredients
        Recipe::where('menu_item_id', $menuItem->id)
            ->where('ingredient_id', $ingredient->id)
            ->update([
                'quantity_required' => $request->quantity_required,
            ]);

        return back()->with('success', 'Quantity updated.');
    }
    public function destroy(MenuItem $menuItem, Ingredient $ingredient)
    {
        //delete the recipe row that links this ingredient to this menu item
        Recipe::where('menu_item_id', $menuItem->id)
            ->where('ingredient_id', $ingredient->id)
            ->delete();

        return back()->with('success', 'Ingredient removed from recipe.');
    }
}
