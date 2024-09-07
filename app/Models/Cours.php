<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;
    protected $table = 'cours';

    function formation(){
        return $this->belongsTo(Formation::class);
    }

    function userEnseignant(){
        return $this->belongsTo(User::class, 'user_id');
    }

    function usersEtudiant(){
        return $this->belongsToMany(User::class, 'cours_users');
    }

    function plannings(){
        return $this->HasMany(Planning::class);
    }
}
