<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'payload',
        'otp_hash',
        'otp_expires_at',
        'consumed_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'otp_expires_at' => 'datetime',
            'consumed_at' => 'datetime',
        ];
    }

}
