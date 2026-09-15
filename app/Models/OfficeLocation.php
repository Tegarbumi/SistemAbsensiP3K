<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    protected $fillable = [
        'unit_id',
        'nama',
        'alamat',
        'latitude',
        'longitude',
        'radius_meter',
        'status',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'radius_meter' => 'integer',
            'status' => 'boolean',
        ];
    }
}
