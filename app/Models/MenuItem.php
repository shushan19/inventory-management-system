<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable =
        [
            'category_id',
            'name',
            'price',
            'image',
            'is_available',
        ];
    protected $casts =
        [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients(): BelongsToMany{
        return $this->belongsToMany(Ingredient::class,'recipes')
            ->withPivot('quantity_required')
            ->withTimestamps();
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function checkStockAvailability(int $quantity): array
    {
        $shortfalls = [];

        foreach ($this->ingredients as $ingredient) {
            $required = $ingredient->pivot->quantity_required * $quantity;

            if ($ingredient->current_stock < $required) {
                $shortfalls[] = [
                    'ingredient' => $ingredient->name,
                    'required' => $required,
                    'available' => $ingredient->current_stock,
                    'unit' => $ingredient->unit,
                ];
            }
        }
        return $shortfalls;
    }
}
