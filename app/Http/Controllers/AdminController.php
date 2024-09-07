<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    //liste de tous les utilisateurs
    public function indexUser(){
        $user = User::all();
        $cours = Cours::all();
        return view('admin.user_index', ['users' => $user, 'cours' => $cours]);
    }

    //liste des utilisateur en attente
    public function AttenteIndex(){
        $users = User::where('type', null)->get();
        return view('admin.user_attente_index', ['users' => $users]);
    }

    //liste des enseignants
    public function enseignantIndex(){
        $enseignants = User::where('type', 'enseignant')->get();
        $cours = Cours::all();
        return view('admin.user_index', ['users' => $enseignants, 'cours' => $cours]);
    }

    //liste des enseignants
    public function etudiantIndex(){
        $etudiants = User::where('type', 'etudiant')->get();
        return view('admin.user_index', ['users' => $etudiants]);
    }

    //accepter un utilisateur en attente
    public function acceptUser($id){
        $user = User::FindOrFail($id);
        if(isset($user->formation))
            $user->type = 'etudiant';
        else
            $user->type = 'enseignant';
        $user->save();
        session()->flash('etat', 'Un utilisateur a été accepté');
        return back();

    }

    //refuser un utilisateur en attente
    public function rejectUser($id){
        $user = User::FindOrFail($id);
        $user->delete();
        session()->flash('etat', 'Un utilisateur a été rejeté');
        return back();
    }

    //creer un utilisateur
    public function createUser(){
        $formations = Formation::all();
        return view('admin.create_user', ['formations' => $formations]);
    }

    //Enregistrer l'utilisateur créé
    public function storeUser(Request $request){
        $request->validate([
           'nom' => 'required|max:40|string',
           'prenom' => 'required|max:40|string',
           'login' => 'required|max:30|string|unique:users',
           'mdp' => 'required|min:6|max:60|confirmed',
            'formation_id' => 'required',
            'type' => 'required'
        ]);
        //Si le createur a choisi le type etudiant mais n'a pas choisi de formation
        if($request->input('type') == 'etudiant' && $request->input('formation_id') == 'null'){
            return back()->withErrors([
                'formation_id' => 'Veuillez choisir une formation pour le type etudiant'
            ]);
        }

        $user = new User();
        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->login = $request->input('login');
        $user->mdp = Hash::make($request->input('mdp'));
        $user->type = $request->input('type');

        if($user->type == 'etudiant')
            $user->formation_id = $request->input('formation_id');
        //si on créer un enseignant ou un un admin, on n'utilise pas l'input formation_id mais on le met
        //directement à null
        else
            $user->formation_id = null;
        $user->save();

        $request->session()->flash('etat', 'Un utilisateur a été créé');
        return redirect()->route('home_admin');
    }

    //modifier un utiliateur
    public function modifyUser($id){
        $user = User::FindOrFail($id);
        $formations = Formation::all();
        return view('admin.modify_user', ['user' => $user, 'formations' => $formations]);
    }

    //enregistrer les modification de l'utilisateur
    public function updateUser(Request $request, $id){
        $request->validate([
            'nom' => 'required|max:40|string',
            'prenom' => 'required|max:40|string',
            'login' => 'nullable|max:30|string|unique:users',
            'mdp' => 'nullable|min:6|max:60|confirmed',
            'formation_id' => 'required',
            'type' => 'required'
        ]);

        //Si le createur a choisi le type etudiant mais n'a pas choisi de formation
        if($request->input('type') == 'etudiant' && $request->input('formation_id') == 'null'){
            return back()->withErrors([
                'formation_id' => 'Veuillez choisir une formation pour le type etudiant'
            ]);
        }

        $user = User::FindOrFail($id);

        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $nom = $request->input('login');
        $mdp = $request->input('mdp');
        if(isset($nom))             //on modifie le nom seulement si l'admin l'as modifié
            $user->login = $request->input('login');
        if(isset($mdp))             //on modifie le mdp seulement si l'admin l'as modifié
            $user->mdp = Hash::make($request->input('mdp'));

        //si on modifie le type d'un enseignant, on detruit les cours et plannings associé
        if($user->type == 'enseignant' && $request->input('type') != 'enseignant'){
            foreach($user->coursEnseignant as $cours){
                foreach($cours->plannings as $planning){    //on supprime les plannings du cours
                    $planning->cours()->dissociate();
                    $planning->delete();
                }
                $cours->userEnseignant()->dissociate();  //on supprime les cours de l'enseignant
                $cours->formation()->dissociate();
                $cours->usersEtudiant()->detach();
                $cours->delete();
            }
        }

        $user->type = $request->input('type');

        if($user->type == 'etudiant') {     //si on change la formation de l'etudiant, on detache tout les cours de l'ancienne formation
            if($user->formation_id != $request->input('formation_id')){
                $user->coursEtudiant()->detach();
            }
            $user->formation_id = $request->input('formation_id');
        }

        //si on créer un enseignant ou un un admin, on n'utilise pas l'input formation_id mais on le met
        //directement à null
        else
            $user->formation_id = null;

        $user->save();
        $request->session()->flash('etat', 'Un utilisateur a été modifié');

        return redirect()->route('user.index');
    }

    //supprimer un utilisateur, si c'est un enseignant supprime aussi les cours et les plannings associés aux cours
    //si c'est un etudiants, dissocie les relations entre les cours et la formation de l'etudiant
    public function deleteUser($id){
        $user = User::FindOrFail($id);
        if($user->type == 'enseignant') {
            foreach($user->coursEnseignant as $cours){
                foreach($cours->plannings as $planning){    //on supprime les plannings du cours
                    $planning->cours()->dissociate();
                    $planning->delete();
                }
                $cours->userEnseignant()->dissociate();  //on supprime les cours de l'enseignant
                $cours->formation()->dissociate();
                $cours->usersEtudiant()->detach();
                $cours->delete();
            }
        }
        else if($user->type == 'etudiant'){
            $cours = $user->coursEtudiant;
            foreach($cours as $cour)
                $cour->usersEtudiant()->detach();

            $user->formation()->dissociate();
        }
        $user->delete();
        session()->flash('etat', 'Un utilisateur a été supprimé');
        return redirect()->route('user.index');
    }

    //associer un cours avec un enseignant
    public function CoursEnseignants(Request $request, $idEnseignant){
        $this->authorize('isAdmin', Cours::class);
        $request->validate([
            'cours_id' => 'required',
        ]);
        $enseignant = User::FindOrFail($idEnseignant);
        $idCours = $request->input('cours_id');
        $cours = Cours::FindOrFail($idCours);
        if(isset($cours->userEnseignant)){
            $cours->userEnseignant()->dissociate();
        }
        $cours->userEnseignant()->associate($enseignant);
        $cours->save();
        $request->session()->flash('etat', 'Un Enseignant a été associé avec un cours');
        return redirect()->route('enseignant.index');
    }

    //rechercher un utilisateur
    public function userSearch(Request $request){
        $request->validate([
            'recherche' => 'required|string|max:40',
        ]);
        $cours = Cours::all();
        $users = User::where('nom', 'like', '%'.$request->input('recherche').'%')
                    ->orWhere('login', 'like', '%'.$request->input('recherche').'%')
                    ->orWhere('prenom', 'like', '%'.$request->input('recherche').'%')->get();
        return view('admin.user_index', ['users' => $users, 'cours' => $cours]);
    }
}
