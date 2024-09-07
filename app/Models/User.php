<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public $timestamps = false;
    protected $hidden = ['mdp'];
    protected $fillable = ['login', 'mdp', 'type'];
    public function getAuthPassword(){
        return $this->mdp;
    }

    public function hasType(){
        return $this->type != null;
    }

    public function isAdmin(){
        return $this->type == 'admin';
    }

    function formation(){
        return $this->belongsTo(Formation::class);
    }

    function coursEnseignant(){
        return $this->hasMany(Cours::class);
    }

    function coursEtudiant(){
        return $this->belongsToMany(Cours::class, 'cours_users');
    }
}
