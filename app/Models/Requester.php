<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requester extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function requests(){
        return $this->hasMany(Request::class);
    }

    public function lastRequest(){
        return $this->hasOne(Request::class)->latest();
    }
}
