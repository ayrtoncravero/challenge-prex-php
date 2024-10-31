<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'endpoint',
        'request_body',
        'response_code',
        'response_body',
        'ip_address',
    ];
}
