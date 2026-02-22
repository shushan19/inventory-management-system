<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $mainCourse = Category::create(['name' => 'Main Course', 'description' => 'Main dishes']);
        $drinks     = Category::create(['name' => 'Drinks',      'description' => 'Beverages']);
        $desserts   = Category::create(['name' => 'Desserts',    'description' => 'Sweet treats']);

        // Ingredients
        $tomato  = Ingredient::create(['name' => 'Tomato',        'unit' => 'gram',  'current_stock' => 5000, 'minimum_stock' => 500]);
        $cheese  = Ingredient::create(['name' => 'Cheese',        'unit' => 'gram',  'current_stock' => 3000, 'minimum_stock' => 300]);
        $bread   = Ingredient::create(['name' => 'Bread Bun',     'unit' => 'piece', 'current_stock' => 50,   'minimum_stock' => 10]);
        $chicken = Ingredient::create(['name' => 'Chicken',       'unit' => 'gram',  'current_stock' => 4000, 'minimum_stock' => 500]);
        $pasta   = Ingredient::create(['name' => 'Pasta',         'unit' => 'gram',  'current_stock' => 3000, 'minimum_stock' => 500]);
        $cream   = Ingredient::create(['name' => 'Cream',         'unit' => 'ml',    'current_stock' => 2000, 'minimum_stock' => 200]);
        $dough   = Ingredient::create(['name' => 'Pizza Dough',   'unit' => 'gram',  'current_stock' => 4000, 'minimum_stock' => 400]);
        $sauce   = Ingredient::create(['name' => 'Tomato Sauce',  'unit' => 'ml',    'current_stock' => 3000, 'minimum_stock' => 300]);
        $sugar   = Ingredient::create(['name' => 'Sugar',         'unit' => 'gram',  'current_stock' => 2000, 'minimum_stock' => 200]);
        $milk    = Ingredient::create(['name' => 'Milk',          'unit' => 'ml',    'current_stock' => 5000, 'minimum_stock' => 500]);

        // Menu Items (prices in Nepali Rupees)
        $burger = MenuItem::create([
            'category_id' => $mainCourse->id,
            'name'        => 'Classic Burger',
            'price'       => 350.00,
            'description' => 'Juicy beef patty with fresh toppings',
        ]);

        $pizza = MenuItem::create([
            'category_id' => $mainCourse->id,
            'name'        => 'Margherita Pizza',
            'price'       => 450.00,
            'description' => 'Classic tomato and cheese pizza',
        ]);

        $pastaDish = MenuItem::create([
            'category_id' => $mainCourse->id,
            'name'        => 'Creamy Pasta',
            'price'       => 380.00,
            'description' => 'Pasta in rich cream sauce',
        ]);

        $latte = MenuItem::create([
            'category_id' => $drinks->id,
            'name'        => 'Café Latte',
            'price'       => 150.00,
        ]);

        // Recipes
        $burger->ingredients()->attach([
            $tomato->id => ['quantity_required' => 50],  // 50 grams of tomato
            $cheese->id => ['quantity_required' => 30],  // 30 grams of cheese
            $bread->id  => ['quantity_required' => 1],   // 1 bun
        ]);

        $pizza->ingredients()->attach([
            $dough->id  => ['quantity_required' => 200], // 200g dough
            $sauce->id  => ['quantity_required' => 80],  // 80ml sauce
            $cheese->id => ['quantity_required' => 100], // 100g cheese
            $tomato->id => ['quantity_required' => 60],  // 60g tomato
        ]);

        $pastaDish->ingredients()->attach([
            $pasta->id  => ['quantity_required' => 150], // 150g pasta
            $cream->id  => ['quantity_required' => 100], // 100ml cream
            $cheese->id => ['quantity_required' => 30],  // 30g cheese
        ]);

        $latte->ingredients()->attach([
            $milk->id => ['quantity_required' => 200],   // 200ml milk
        ]);
    }
}
