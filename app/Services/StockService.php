<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ingredient;
use App\Models\StockMovement;
use Illuminate\Validation\ValidationException;

class StockService
{
    //checking if we have enough stock before placing order if anything is short is will thorw error and stop
    public function verifyStockForOrder(Order $order): void
    {
        // This will collect any ingredients we don't have enough of
        $shortfalls = [];

        // Go through each item the customer ordered eg burger, pizza, etc.
        foreach ($order->orderItems as $orderItem) {

            // Load this menu item's ingredients from the recipe
            // e.g. Burger needs: Tomato, Cheese, Bread
            $menuItem = $orderItem->menuItem->load('ingredients');

            // Now check each ingredient in the recipe
            foreach ($menuItem->ingredients as $ingredient) {
                //checking how much about is needed. eg. burger needs 50g tomato, customer ordered 2 = 50g tomato * 2
                $amountNeeded = $ingredient->pivot->quantity_required * $orderItem->quantity;

                //checking if the required amount of stock is available
                if ($ingredient->current_stock < $amountNeeded) {
                    // if the required amount of stock is not available then add to shortfalls
                    $shortfalls[] = "{$ingredient->name}: needs {$amountNeeded} {$ingredient->unit}, "
                        . "only {$ingredient->current_stock} available";
                }
            }
        }

        //check where there are any shortfalls if yes then throw an exception error and stop the order
        if (!empty($shortfalls)) {
            throw ValidationException::withMessages([
                'stock' => $shortfalls
            ]);
        }
    }

    //function for stock deduction after the order is placed if there is no shortfalls
    public function deductStockForOrder(Order $order): void
    {
        // Go through each item the customer ordered eg burger, pizza, etc.
        foreach ($order->orderItems as $orderItem) {

            // Load this menu item with its recipe ingredients
            $menuItem = $orderItem->menuItem->load('ingredients');

            // Go through each ingredient in the recipe
            foreach ($menuItem->ingredients as $ingredient) {

                //calculation the amount of ingredients to deduct according to quantity order items
                $amountToDeduct = $ingredient->pivot->quantity_required * $orderItem->quantity;

                // decrement() reduces current_stock by $amountToDeduct in the database
                // e.g. current_stock was 5000g, after decrement it becomes 4950g
                $ingredient->decrement('current_stock', $amountToDeduct);

                //log for items used
                StockMovement::create([
                    'ingredient_id'  => $ingredient->id,
                    'order_id'       => $order->id,
                    'quantity'       => -$amountToDeduct, //negative means out of stock
                    'type'           => 'deduction',
                    'notes'          => "Used for Order #{$order->id}",
                ]);
            }
        }
    }

    //manually adding stock into the inventory
    public function addStock(Ingredient $ingredient, float $quantity, ?string $notes = null): void
    {
        // Add the quantity to current stock in the database. increment helper function that updates the ingredient set
        $ingredient->increment('current_stock', $quantity);

        // Log this addition in stock_movements
        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'order_id'      => null,
            'quantity'      => $quantity,        // positive means stock came IN
            'type'          => 'manual_add',
            'notes'         => $notes ?? 'Manual stock addition',
        ]);
    }

// to find where any ingredients are running low or not
    public function getLowStockIngredients()
    {
        //comparing current stock with minimum stock
        return Ingredient::whereColumn('current_stock', '<=', 'minimum_stock')->get();
    }
}
