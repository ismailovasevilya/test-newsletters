<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'status',
        'immediate_send',
        'days_before_birthday',
        'time_to_send'
    ];

    public function clients() {
        return $this->belongsToMany(\App\Models\Client::class, 'newsletter_logs')
            ->withPivot('status');
    }
}
