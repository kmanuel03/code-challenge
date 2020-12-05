<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    public function eventDates() {
        return $this->hasMany(EventDates::class, 'event_id');
    }
}
