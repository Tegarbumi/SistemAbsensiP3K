<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = [
        'nama',
        'jam_masuk',
        'jam_pulang',
        'toleransi_terlambat',
        'senin',
        'selasa',
        'rabu',
        'kamis',
        'jumat',
        'sabtu',
        'minggu',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'senin' => 'boolean',
            'selasa' => 'boolean',
            'rabu' => 'boolean',
            'kamis' => 'boolean',
            'jumat' => 'boolean',
            'sabtu' => 'boolean',
            'minggu' => 'boolean',
            'status' => 'boolean',
        ];
    }
}
