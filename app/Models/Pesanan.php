<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Pesanan Model
 * 
 * Represents an order in the rental system.
 */
class Pesanan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pesanan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pemesan',
        'no_telepon',
        'alamat',
        'tanggal_acara',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_acara' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the details for this order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    /**
     * Get the total from the last detail record.
     *
     * @return float
     */
    public function getTotalDariDetailAttribute(): float
    {
        return optional($this->details->last())->total ?? 0;
    }

    /**
     * Get the formatted total from the last detail record.
     *
     * @return string
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_dari_detail, 0, ',', '.');
    }

    /**
     * Scope a query to only include completed orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Selesai');
    }

    /**
     * Scope a query to only include orders by customer name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCustomer($query, $name)
    {
        return $query->where('nama_pemesan', 'like', "%$name%");
    }
}
