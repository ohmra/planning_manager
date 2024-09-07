<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Planning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanningController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'cours_id' => 'required|integer',
            'date' => 'required',
            'debut' => 'required',
            'fin' => 'required'
        ]);
        $this->authorize('modify', Planning::class);
        $cours = Cours::findOrFail($request->input('cours_id'));
        $debut = now();
        $debut->setDateTimeFrom($request->input('date')." ".$request->input('debut'))->format('Y-m-d H:i:s');
        $fin = now();
        $fin->setDateTimeFrom($request->input('date')." ".$request->input('fin'))->format('Y-m-d H:i:s');
        if($debut->gt($fin)){
            return back()->withErrors([
                'time' => "L'heure de debut doit être avant l'heure de fin"
            ]);
        }
        $planning = new Planning();
        $planning->cours()->associate($cours);
        $planning->date_debut = $debut;
        $planning->date_fin = $fin;
        $planning->save();
        $request->session()->flash('etat', 'Un planning a été ajouté');
        return back();
    }

    public function update(Request $request, $id){
        $request->validate([
            'date' => 'required',
            'debut' => 'required',
            'fin' => 'required'
        ]);
        $this->authorize('modify', Planning::class);
        $planning = Planning::FindOrFail($id);
        $debut = now();
        $debut->setDateTimeFrom($request->input('date'). ' '.$request->input('debut'))->format('Y-m-d H:i:s');
        $fin = now();
        $fin->setDateTimeFrom($request->input('date'). ' '.$request->input('fin'))->format('Y-m-d H:i:s');
        if($debut->gt($fin)){
            return back()->withErrors([
                'time' => 'La date de debut doit être avant la date de fin'
            ]);
        }
        $cours = $planning->cours;
        $planning->date_debut = $debut;
        $planning->date_fin = $fin;
        $planning->save();
        $request->session()->flash('etat', 'Un planning de '.$cours->intitule.' a été modifié du
                                    '.$planning->date_debut.' au '.$planning->date_fin);
        return back();
    }

    public function delete($id){
        $this->authorize('modify', Planning::class);
        $planning = Planning::FindOrFail($id);
        $planning->cours()->dissociate();
        $planning->delete();
        session()->flash('etat', 'Un planning a été supprimé');
        return back();
    }

    public function viewPlanning($id){
        $this->authorize('modify', Planning::class);
        $planning = Planning::FindOrFail($id);
        return view('planning.voir_planning', ['cours' => Auth::user()->coursEnseignant, 'planning' => $planning]);
    }

    public function affichage(){
        $user = Auth::user();

        if($user->type == 'etudiant'){
            $cours = $user->coursEtudiant;
            $cours_id = $user->coursEtudiant()->pluck('id');
            $plannings = Planning::whereIn('cours_id', $cours_id)->orderBy('date_debut')->paginate(10);
            return view('planning.affichage', ['plannings' => $plannings, 'cours' => $cours]);
        }
        else if($user->type == 'enseignant'){
            $cours = $user->coursEnseignant;
            $cours_id = $user->coursEnseignant()->pluck('id');
            $plannings = Planning::whereIn('cours_id', $cours_id)->orderBy('date_debut')->paginate(10);
            return view('planning.affichage', ['plannings' => $plannings, 'cours' => $cours]);
        }
        else{
            $cours = Cours::all();
            $plannings = Planning::orderBy('date_debut')->paginate(10);
            return view('planning.affichage', ['plannings' => $plannings, 'cours' => $cours]);
        }
    }

    public function filtreCours(Request $request){
        $request->validate([
            'cours_id' => 'required|integer'
        ]);
        $coursId = $request->input('cours_id');
        $plannings = Planning::where('cours_id', $coursId)->orderBy('date_debut')->get();
        $user = Auth::user();
        if($user->type == 'enseignant') {
            $cours = $user->coursEnseignant;
            return view('planning.affichage_cours', ['plannings' => $plannings, 'cours' => $cours, 'coursID' => $coursId]);
        }
        else if($user->type == 'etudiant') {
            $cours = $user->coursEtudiant;
            return view('planning.affichage_cours', ['plannings' => $plannings, 'cours' => $cours, 'coursID' => $coursId]);
        }
        else{
            $cours = Cours::all();
            return view('planning.affichage_cours', ['plannings' => $plannings, 'cours' => $cours, 'coursID' => $coursId]);
        }

    }

    public function filtreSemaine(Request $request){
        $request->validate([
            'semaine' => 'required|string',
        ]);
        $time = Carbon::now();
        $time->setDateTimeFrom($request->input('semaine'));
        $debut = $time->startOfWeek()->format('Y-m-d H:i');
        $fin = $time->endOfWeek()->format('Y-m-d H:i');

        $date = now();
        $date->setDateTimeFrom($debut)->subDay();
        $prevWeek = Carbon::now();
        $prevWeek->setDateTimeFrom($time)->format('Y-m-d H:i');
        $prevWeek->startOfWeek()->subDays(7);
        $nextWeek = Carbon::now();
        $nextWeek->setDateTimeFrom($time)->format('Y-m-d H:i');
        $nextWeek->addDay();

        $user = Auth::user();
        if($user->type == 'etudiant'){
            $cours = $user->coursEtudiant;
            $cours_id = $user->coursEtudiant()->pluck('id');
            $plannings = Planning::whereIn('cours_id', $cours_id)
                                    ->whereBetween('date_debut', [$debut, $fin])->orderBy('date_debut')->get();
            return view('planning.affichage_semaine', ['plannings' => $plannings, 'date' => $date, 'cours' => $cours,
                                                            'debut' => $debut, 'next' => $nextWeek, 'prev' => $prevWeek]);
        }
        else if($user->type == 'enseignant'){
            $cours = $user->coursEnseignant;
            $cours_id = $user->coursEnseignant()->pluck('id');
            $plannings = Planning::whereIn('cours_id', $cours_id)
                ->whereBetween('date_debut', [$debut, $fin])->orderBy('date_debut')->get();
            return view('planning.affichage_semaine', ['plannings' => $plannings, 'date' => $date, 'cours' => $cours,
                                                                'debut' => $debut, 'next' => $nextWeek, 'prev' => $prevWeek]);
        }
        else{
            $cours = Cours::all();
            $plannings = Planning::whereBetween('date_debut', [$debut, $fin])->orderBy('date_debut')->get();
            return view('planning.affichage_semaine', ['plannings' => $plannings, 'date' => $date, 'cours' => $cours,
                'debut' => $debut, 'next' => $nextWeek, 'prev' => $prevWeek]);
        }


    }
}
