<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'status',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function officeLocations()
    {
        return $this->hasMany(OfficeLocation::class);
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
}
