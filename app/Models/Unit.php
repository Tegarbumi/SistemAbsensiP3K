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
        'is_dinas_lapangan',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function officeLocations()
    {
        return $this->hasMany(OfficeLocation::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_dinas_lapangan' => 'boolean',
        ];
    }
}
