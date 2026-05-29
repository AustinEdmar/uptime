<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Site;


class Endpoint extends Model
{
    protected $fillable = [
        'location',
        'frequency',
        'is_down',
        'last_downtime_notification_sent_at',
        'next_check',
    ];

    public $dates = [
        'next_check',
        'last_downtime_notification_sent_at',
    ];


    public function url()
    {
        return $this->site->url() . $this->location;
    }



    public function uptimePercentage(): ?float
    {
        if (!isset($this->successful_checks_count) || $this->checks_count === 0) {
            return null;
        }

        return round(
            ($this->successful_checks_count / $this->checks_count) * 100,
            2
        );
    }


    public function site()
    {
        return $this->belongsTo(Site::class);
    }


    public function checks()
    {
        return $this->hasMany(Check::class);
    }


    public function check()
    {
        return $this->hasOne(Check::class)->latestOfMany();
    }
}
