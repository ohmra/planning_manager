<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function modify($user){
        return $user->type != 'etudiant';
    }

    public function isAdmin($user){
        return $user->type == 'admin';
    }

    public function isEtudiant($user){
        return $user->type == 'etudiant';
    }
}
