<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
  protected $fillable =
      [
          'name',
            'unit',
          'current_stock',
          'minimum_stock',

      ];
    protected $casts=
        [
            'current_stock'=>'decimal:3',
            'minimum_stock'=>'decimal:3',
        ];
    protected function menuItems () : BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'recipes')
            ->withPivot('quantity_required')
            ->withTimestamps();
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }
}
