<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public static $FILE_PATH = 'invoices/';

    protected $fillable = [
        'supplier_id',
        'bank_id',
        'number',
        'date',
        'paid',
        'paid_at',
        'instalacion_id',
        'file'
    ];

    protected  $dates = [
        'date',
        'paid_at',
    ];

    protected $appends = [
        'subtotal',
        'iva',
        'total',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function invoiceLines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->invoiceLines->sum('base');
    }

    public function getIvaAttribute()
    {
        return $this->invoiceLines->sum('iva');
    }

    public function getTotalAttribute()
    {
        return $this->invoiceLines->sum('total');
    }

    public function getFilePathAttribute()
    {
        return 'storage/' . self::$FILE_PATH  . $this->file;
    }
}
