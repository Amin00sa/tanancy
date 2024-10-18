<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'numero',
        'client_id',
        'marche_id',
        'objet',
        'total',
        'total_text',
        'taux_tva',
        'date',
        'status',
        'proof_file',
        'original_file_name'
    ];

    protected $casts = [
        'id' => 'integer',
        'client_id' => 'integer',
        'marche_id' => 'integer',
        'taux_tva' => 'integer',
        'date' => 'date:Y-m-d',
        'total' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function marche(): BelongsTo
    {
        return $this->belongsTo(Marche::class);
    }
    public function products(): HasMany
    {
        return $this->hasMany(VenteProduct::class);
    }
}
