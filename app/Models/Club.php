<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'abbrName'];

    public function players()
    {
        return $this->hasMany('App\Models\Player', 'club_id');
    }
}
