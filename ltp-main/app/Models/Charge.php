<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charge extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'numero',
        'name',
        'motif',
        'montant',
    ];

    public function beneficiaire(): BelongsTo
    {
        return $this->belongsTo(Charge::class);
    }
}
