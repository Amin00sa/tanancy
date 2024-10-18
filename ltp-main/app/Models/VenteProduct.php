<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenteProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'vente_id',
        'designation',
        'quantity',
        'unity',
        'priceperunity',
        'subtotal',
    ];

    protected $casts = [
        'id' => 'integer',
        'vente_id' => 'integer',
        'priceperunity' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function vente(): BelongsTo
    {
        return $this->belongsTo(Vente::class);
    }
}
