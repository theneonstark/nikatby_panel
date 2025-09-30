<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'api_name',
        'request_id',
        'reference_id',
        'request_payload',
        'response_data',
        'status',
        'error_message',
        'ip_address',
        'execution_time'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request_payload' => 'array',
        'response_data' => 'array',
        'execution_time' => 'float',
    ];

    /**
     * Get the user that made the API call.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}