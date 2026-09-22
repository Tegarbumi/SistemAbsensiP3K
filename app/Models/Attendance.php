<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'unit_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'latitude_masuk',
        'longitude_masuk',
        'accuracy_masuk',
        'distance_masuk',
        'latitude_pulang',
        'longitude_pulang',
        'accuracy_pulang',
        'distance_pulang',
        'selfie_masuk',
        'selfie_pulang',
        'status',
        'keterangan',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'latitude_masuk' => 'float',
            'longitude_masuk' => 'float',
            'accuracy_masuk' => 'float',
            'distance_masuk' => 'float',
            'latitude_pulang' => 'float',
            'longitude_pulang' => 'float',
            'accuracy_pulang' => 'float',
            'distance_pulang' => 'float',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function locations()
    {
        return $this->hasMany(AttendanceLocation::class);
    }
}
