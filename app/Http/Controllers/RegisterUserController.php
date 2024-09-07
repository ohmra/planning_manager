<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public function registerForm(){
        $formations = Formation::all();
        return view('auth.register', ['formations' => $formations]);
    }

    public function register(Request $request){
        $request->validate([
            'nom' => 'required|string|max:40',
            'prenom' => 'required|string|max:40',
            'login' => 'required|string|max:30|unique:users',
            'mdp' => 'required|string|confirmed|min:6|max:60',
            'formation_id' => 'required'
        ]);

        $user = new User();
        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->login = $request->input('login');
        $user->mdp = Hash::make($request->input('mdp'));

        $form_id = $request->input('formation_id');

        if($form_id != 'enseignant') {
            $formation = Formation::findOrFail($form_id);
            $formation->users()->save($user);
        }

        $user->save();
        $request->session()->flash('etat', 'Nouvel utilisateur ajouté ! Veuillez attendre la confirmation');

        return redirect()->route('home');
    }
}
