<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'position', 'nation_id', 'club_id'];

    public function club()
    {
        return $this->belongsTo('App\Models\Club');
    }

    public function nation()
    {
        return $this->belongsTo('App\Models\Country', 'nation_id');
    }
}
