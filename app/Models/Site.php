<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Endpoint;

class Site extends Model
{
    
    protected $fillable = [
        'scheme',
        'domain',
        'default',
        'notification_emails',
    ];


    protected $casts = [
        'default' => 'boolean',
        'notification_emails' => 'array',
    ];






    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function url()
    {
         return $this->scheme . '://' . $this->domain;
    }
 


    public function endpoints()
    {
        return $this->hasMany(Endpoint::class)
        ->withCount([
            'checks',
            'checks as successful_checks_count' => function ($query) {
                $query->whereBetween('response_code', [200, 299]);
            }
        ])
        ->latest();
    }
}
