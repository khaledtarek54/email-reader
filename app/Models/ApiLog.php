<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiLog extends Model
{
    use HasFactory;

    protected $table = 'api_logs';

    // Define the mass-assignable attributes
    protected $fillable = [
        'endpoint',
        'method',
        'request_payload',
        'response_payload',
        'status_code',
    ];


}
