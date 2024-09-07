@extends('modele')
@section('title', 'Plannings par cours')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-4 border-right">
                <div class="container" id="cal">
                    <div class="calendar">
                        <div class="month">
                            <i class="fas fa-angle-left prev"></i>
                            <div class="date">
                                <h1></h1>
                                <p></p>
                            </div>
                            <i class="fas fa-angle-right next"></i>
                        </div>
                        <div class="weekdays">
                            <div>Sun</div>
                            <div>Mon</div>
                            <div>Tue</div>
                            <div>Wed</div>
                            <div>Thu</div>
                            <div>Fri</div>
                            <div>Sat</div>
                        </div>
                        <div class="days"></div>
                    </div>
                </div>
                <form method="get" action="{{route('affichage.cours_filtre')}}">
                    <h1><label for="cours_id"> Filtrer par cours : </label></h1>
                    <select name="cours_id" class="form-control" id="cours_id">
                        @foreach($cours as $cour)
                            <option value="{{$cour->id}}">{{$cour->intitule}}</option>
                        @endforeach
                        <input type="submit" class="btn btn-primary btn-lg" value="filtrer">
                    </select>
                    @csrf
                </form>
                @if(Auth::user()->type != 'etudiant')
                    <br>
                    <a href="#PlanningForm" class="btn btn-success btn-block" data-bs-toggle="collapse">Créer un planning pour ce cours</a>
                    <div class="collapse" id="PlanningForm">
                        <form method="post" action="{{route('planning.store', ['cours_id' => $coursID])}}">
                            Creer un planning pour ce cours : <br>
                            <input type="date" class="form-control" name="date"> <br>
                            <label for="debut" class="form-label-group">Debut</label>
                            <input type="time" id="debut" class="form-control" name="debut">
                            <label for="fin" class="form-label-group">Fin</label>
                            <input type="time" id="fin" class="form-control" name="fin">
                            <input type="submit" class="form-control" value="creer">
                            @csrf
                        </form>
                    </div>
                @endif

            </div>
            <div class="col-6 mx-auto">
                <div class="container-fluid">
                    @foreach($plannings as $planning)
                        @if(substr($planning->date_debut, 0, 10) != session()->get('debut'))
                            <br>
                            <div class="row border border-dark rounded-pill">
                                <div class="col-12 text-center">
                                    <h1>{{substr($planning->date_debut, 0, 10)}}</h1>
                                </div>
                            </div>
                            {{session()->put('debut', substr($planning->date_debut, 0, 10))}}
                        @endif
                        <div class="row rounded-pill" style="background-color: lightblue">
                            <div class="col-md-3">
                                <div class="border-right">
                                    {{substr($planning->date_debut, 11, 5)}} <br>
                                    {{substr($planning->date_fin, 11, 5)}}
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                {{$planning->cours->intitule}}
                            </div>
                            @if(Auth::user()->type != 'etudiant')
                                <div class="col-md-3">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#showPlanning{{$loop->iteration}}">
                                        Voir
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="showPlanning{{$loop->iteration}}" tabindex="-1" aria-labelledby="showPlanninglabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="showPlanninglabel">Planning pour le cours {{$planning->cours->intitule}}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" action="{{route('planning.update', ['id' => $planning->id])}}">
                                                        <label for="modifyDate" class="form-label-group">Date</label>
                                                        <input type="date" class="form-control" id="modifyDate" name="date" value="{{substr($planning->date_debut, 0, 10)}}">
                                                        <label for="modifyDebut" class="form-label-group">Debut</label>
                                                        <input type="time" class="form-control" name="debut" id="modifyDebut" value="{{substr($planning->date_debut, 11, 5)}}">
                                                        <label for="modifyFin" class="form-label-group">Fin</label>
                                                        <input type="time" class="form-control" id="modifyFin" name="fin" value="{{substr($planning->date_fin, 11, 5)}}">
                                                        <input class="btn btn-success form-control" type="submit" value="modifier">
                                                        @csrf
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <a class="btn btn-danger" href="{{route('planning.delete', ['id' => $planning->id])}}">supprimer</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <br>
                    {{session()->forget('debut')}}
                </div>
            </div>
        </div>
    </div>

@endsection
