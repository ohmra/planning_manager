<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtudiantController extends Controller
{
    //affiche la liste des cours de la formation de l'etudiant
    public function indexCoursFormation(){
        $etudiant = Auth::user();
        $formation = $etudiant->formation;
        $cours = $formation->cours;
        return view('formation.index_cours', ['cours' => $cours]);
    }

    //inscrit l'etudiant à un cours de la formation
    public function inscription($id){
        $this->authorize('isEtudiant', Cours::class);
        $user = Auth::user();
        $cours = Cours::FindOrFail($id);
        $cours->usersEtudiant()->attach($user);
        session()->flash('etat', 'Votre inscription à un cours a bien été faite');
        return back();
    }


    //désinscrit l'etudiant à un cours de la formation
    public function desinscription($id){
        $this->authorize('isEtudiant', Cours::class);
        $user = Auth::user();
        $cours = Cours::FindOrFail($id);
        $cours->usersEtudiant()->detach($user);
        session()->flash('etat', 'Votre desinscription à un cours a bien été faite');
        return back();
    }

    //affiche la liste des cours auxquels l'etudiants est inscrit
    public function inscritIndex(){
        $this->authorize('isEtudiant', Cours::class);
        $user = Auth::user();
        $cours = $user->coursEtudiant;
        return view('formation.index_cours', ['cours' => $cours]);
    }

    //recherche un cours
    public function search(Request $request){
        $request->validate([
            'intitule' => 'required|string|max:40',
        ]);
        $user = Auth::user();
        $formation = $user->formation;
        $cours = $formation->cours()->where('intitule', 'like', '%' . $request->input('intitule') . '%')->get();
        return view('formation.index_cours', ['cours' => $cours]);
    }
}
