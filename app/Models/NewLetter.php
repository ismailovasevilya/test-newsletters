<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'status',
        'schedule',
        'schedule_at',
        'send_at_birthday'
    ];
}
