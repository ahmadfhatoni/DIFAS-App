<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Barang Model
 * 
 * Represents an item/product in the rental system.
 */
class Barang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'barang';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'foto',
        'nama',
        'kategori_id',
        'harga_sewa',
        'stok',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga_sewa' => 'decimal:2',
        'stok' => 'integer',
    ];

    /**
     * Get the category that owns the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Get the order details for this item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    /**
     * Check if item is available for rent (has stock).
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->stok > 0;
    }

    /**
     * Get formatted price.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_sewa, 0, ',', '.');
    }

    /**
     * Scope a query to only include available items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope a query to only include items by category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $kategoriId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }
}