<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Kategori Model
 * 
 * Represents a category for items in the rental system.
 */
class Kategori extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kategori';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the items for this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

    /**
     * Get the total number of items in this category.
     *
     * @return int
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->barang()->count();
    }

    /**
     * Get the total stock of items in this category.
     *
     * @return int
     */
    public function getTotalStockAttribute(): int
    {
        return $this->barang()->sum('stok');
    }

    /**
     * Scope a query to only include categories with items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithItems($query)
    {
        return $query->has('barang');
    }

    /**
     * Scope a query to only include categories with available items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAvailableItems($query)
    {
        return $query->whereHas('barang', function ($q) {
            $q->where('stok', '>', 0);
        });
    }
}
