<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    public static $LOGO_PATH = 'storage/sponsor_logos/';

    protected $fillable = [
        'name',
        'website',
        'logo',
        'instalacion_id',
    ];

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function getLogoAttribute($value)
    {
        return $value ? asset(self::$LOGO_PATH . $value) : null;
    }
}
