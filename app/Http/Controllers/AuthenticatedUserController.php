<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedUserController extends Controller
{
    public function loginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'login' => 'required|string|max:30',
            'mdp' => 'required|string|max:60',
        ]);
        $credentials = ['login' => $request->input('login'),
            'password' => $request->input('mdp')];
        if(Auth::attempt($credentials)){
            //On verifie si le compte a déjà été vérifié par l'admin ou pas
            if(Auth::user()->type == null){
                Auth::logout();
                session()->flash('etat', "Votre compte n'a pas encore été vérifié");
                return back();
            }
            $request->session()->regenerate();
            $request->session()->flash('etat', 'Utilisateur connecté');
            if(Auth::user()->type == 'admin')
                return redirect()->route('home_admin');
            return redirect()->intended('/acceuil');
        }

        return back()->withErrors([
            'login' => "Nom d'utilisateur et/ou mot de passe incorrecte"
        ]);
    }

    public function logout(){
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        session()->flash('etat', 'Utilisateur déconnecté');
        return redirect()->route('home');
    }

    public function home_admin(){
        $attente = User::where('type', null)->get();
        return view('home_admin', ['user' => Auth::user(), 'attente' => $attente]);
    }

    //enregistrer les modifications de mot de passe
    public function updatePassword(Request $request){
        $request->validate([
            'mdp' => 'required|string|max:60',
            'new_mdp' => 'required|string|min:6|max:60|confirmed'
        ]);
        $user = Auth::user();
        if(Hash::check($request->input('mdp'), $user->mdp)){
            $user->mdp = Hash::make($request->input('new_mdp'));
            $user->save();
            $request->session()->flash('etat', 'Le mot de passe a bien été mis à jour');

            return redirect()->route('acceuil');
        }

        return back()->withErrors([
            'mdp' => 'Le mot de passe est incorrecte'
        ]);
    }

    //enregistrer le changement de nom
    public function updateName(Request $request){
        $request->validate([
            'nom' => 'required|string|max:40',
            'prenom' => 'required|string|max:40',
        ]);
        $user = Auth::user();
        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->save();
        $request->session()->flash('etat', 'Nom/Prenom modifié');
        return redirect()->route('acceuil');
    }

    public function acceuil(){
        $user = Auth::user();
        return view('acceuil', ['user' => $user]);
    }
}
