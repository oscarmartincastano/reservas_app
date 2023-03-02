<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'cif',
        'cp',
        'city',
        'province',
        'notes',
        'instalacion_id',
    ];

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
