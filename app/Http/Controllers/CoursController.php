<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursController extends Controller
{
    //liste des cours
    public function index(){
        $cours = Cours::all();
        $user = User::where('type', 'enseignant')->get();
        $formations = Formation::all();
        return view ('cours.index', ['cours' => $cours, 'enseignants' => $user, 'formations' => $formations]);
    }

    //recherhcer un cours
    public function search(Request $request){
        $request->validate([
            'intitule' => 'required|string|max:40',
        ]);
        $enseignants = User::where('type', 'enseignant')->get();
        $cours = Cours::where('intitule', 'like', '%'.$request->input('intitule').'%')->get();
        $formations = Formation::all();
        return view('cours.index', ['cours' => $cours, 'enseignants' => $enseignants, 'formations' => $formations]);
    }

    //creer un cours
    public function create(){
        $this->authorize('modify', Cours::class);
        $enseignants = User::where('type', 'enseignant')->get();
        $formations = Formation::all();
        return view('cours.create', ['enseignants' => $enseignants, 'formations' => $formations]);
    }

    //enregistrer le cours créé
    public function store(Request $request){
        $this->authorize('modify', Cours::class);
        $request->validate([
           'intitule' => 'required|string|max:20',
            'user_id' => 'required|integer',
            'formation_id' => 'required|integer',
        ]);
        $cours = new Cours();
        $cours->intitule = $request->input('intitule');

        $enseignant = User::FindOrFail($request->input('user_id'));
        $formation = Formation::FindOrFail($request->input('formation_id'));

        $cours->userEnseignant()->associate($enseignant);
        $cours->formation()->associate($formation);
        $cours->save();
        $request->session()->flash('etat', 'Un cours a été créé');
        return redirect()->route('cours.index');
    }

    //modifier un cours
    public function modify($id){
        $this->authorize('modify', Cours::class);
        $cours = Cours::findOrFail($id);
        $enseignants = User::where('type', 'enseignant')->get();
        $formations = Formation::all();
        return view('cours.modify', ['cours' => $cours, 'enseignants' => $enseignants,
                                            'formations' => $formations]);
    }

    //enregistrer les modifications du cours
    public function update(Request $request, $id){
        $this->authorize('modify', Cours::class);
        $request->validate([
            'intitule' => 'required|string|max:50',
            'user_id' => 'required|integer',
            'formation_id' => 'required|integer',
        ]);
        $cours = Cours::findOrFail($id);
        $formation = Formation::FindOrFail($request->input('formation_id'));
        //si on change la formation du cours, les etudiant qui ne sont pas dans la
        //nouvelle formation n'auront plus accès au cours
        if($cours->formation != $formation){
            $cours->usersEtudiant()->detach();
        }
        $cours->intitule = $request->input('intitule');

        $enseignant = User::FindOrFail($request->input('user_id'));
        $cours->userEnseignant()->associate($enseignant);
        $cours->formation()->associate($formation);
        $cours->save();
        $request->session()->flash('etat', 'Un cours a été modifié');
        return redirect()->route('cours.index');
    }

    //supprimer un cours, supprime aussi les plannings associé au cours
    public function delete($id){
        $this->authorize('modify', Cours::class);
        $cours = Cours::findOrFail($id);
        $plannings = $cours->plannings;
        foreach($plannings as $planning){
            $planning->cours()->dissociate();
            $planning->delete();
        }
        $cours->formation()->dissociate();
        $cours->usersEtudiant()->detach();
        $cours->userEnseignant()->dissociate();
        $cours->delete();
        session()->flash('etat', 'Un cours a été supprimé');
        return back();
    }

    //Associer un cours avec un enseignant
    public function CoursEnseignants(Request $request, $idCours){
        $this->authorize('isAdmin', Cours::class);
        $request->validate([
            'enseignant_id' => 'required',
        ]);
        $cours = Cours::FindOrFail($idCours);
        $idEnseignant = $request->input('enseignant_id');
        $enseignant = User::FindOrFail($idEnseignant);
        if(isset($cours->userEnseignant)){
            $cours->userEnseignant()->dissociate();
        }
        $cours->userEnseignant()->associate($enseignant);
        $cours->save();
        $request->session()->flash('etat', 'Un Cours a été associé avec un enseignant');
        return redirect()->route('cours.index');
    }

    //affiche les cours de l'enseignant demandé (POUR L'ADMIN)
    public function coursEnseignantIndex(Request $request){
        $this->authorize('isAdmin', Cours::class);
        $request->validate([
            'id' => 'required|integer'
        ]);
        $enseignant = User::FindOrFail($request->input('id'));
        $enseignants = User::where('type', 'enseignant')->get();
        $cours = $enseignant->coursEnseignant;
        //return view('cours.index_cours_enseignant', ['cours' => $cours, 'enseignant' => $enseignant]);
        $formations = Formation::all();
        return view('cours.index', ['cours' => $cours, 'enseignants' => $enseignants, 'formations' => $formations]);
    }

    //affiche les cours de l'enseignant connecté (Pas admin, l'enseingnant doit être connecté)
    public function CoursEnseignant(){
        $user = Auth::user();
        $cours = $user->coursEnseignant;
        return view('cours.index_cours_enseignant', ['cours' => $cours, 'enseignant' => $user]);
    }

}
