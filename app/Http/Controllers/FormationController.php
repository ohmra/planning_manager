<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    //affiche la liste des formations
    public function index(){
        $formations = Formation::all();
        return view('formation.index', ['formations' => $formations]);
    }

    //enregistre une nouvelle formation
    public function store(Request $request){
        $request->validate([
            'intitule' => 'required|string|max:20'
        ]);

        $formation = new Formation();
        $formation->intitule = $request->input('intitule');
        $formation->save();
        $request->session()->flash('etat', 'Une formation a été ajoutée');
        return redirect()->route('formation.index');
    }

    //enregistrer la modification de la formation
    public function update(Request $request, $id){
        $request->validate([
            'intitule' => 'required|string|max:50'
        ]);
        $formation = Formation::FindOrFail($id);
        $formation->intitule = $request->input('intitule');
        $formation->save();
        $request->session()->flash('etat', 'Une formation a été modifiée');
        return redirect()->route('formation.index');
    }

    //supprimer une formation
    //supprime aussi les cours, les plannings, les étudiants associé
    public function delete($id){
        $formation = Formation::FindOrFail($id);
        foreach($formation->cours as $cours){
            foreach($cours->plannings as $planning){    //on supprime les plannings du cours
                $planning->cours()->dissociate();
                $planning->delete();
            }
            $cours->usersEtudiant()->detach();
            $cours->plannings()->delete();
            $cours->userEnseignant()->dissociate();  //on supprime les cours de l'enseignant
            $cours->formation()->dissociate();
            $cours->delete();
        }

        foreach($formation->users as $user){
            $user->formation()->dissociate();
            $user->delete();
        }

        $formation->delete();
        session()->flash('etat', 'Une formation a été supprimée');
        return redirect()->route('formation.index');
    }
}
