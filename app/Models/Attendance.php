<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
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
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function locations()
    {
        return $this->hasMany(AttendanceLocation::class);
    }
}
