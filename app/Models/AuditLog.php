<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'details',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(?int $userId, string $action, ?string $modelType = null, ?int $modelId = null, ?array $details = null, ?string $ip = null, ?string $userAgent = null): self
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'details' => $details,
            'ip_address' => $ip ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
        ]);
    }
}
