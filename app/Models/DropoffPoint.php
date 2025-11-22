<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropoffPoint extends Model
{
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
