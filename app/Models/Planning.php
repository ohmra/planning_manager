<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;
    public $timestamps = false;

    function cours(){
        return $this->belongsTo(Cours::class);
    }
}
