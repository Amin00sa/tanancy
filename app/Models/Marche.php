<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marche extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'maitre_ouvrage',
        'ville',
        'montant',
        'date_service',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    
    protected $casts = [
        'id' => 'integer',
        'date_service' => 'date:Y-m-d'  
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }
}
