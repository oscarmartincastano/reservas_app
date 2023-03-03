<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class InvoiceLine extends Pivot
{
    protected $table = 'invoice_line';

    protected $fillable = [
        'concept',
        'base',
        'invoice_id',
        'service_type_id',
    ];

    protected $appends = ['iva', 'total'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function getIvaAttribute()
    {
        return $this->base * $this->service_type->iva / 100;
    }

    public function getTotalAttribute()
    {
        return $this->base + $this->iva;
    }
}
