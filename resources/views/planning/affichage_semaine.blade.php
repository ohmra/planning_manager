@extends('modele')
@section('title', 'Plannings par semaine')

@section('content')
<div class="container-fuild">
    <div class="row">
        <div class="col-md-12 text-center">
            <form method="get" action="{{route('planning.filtre_semaine')}}">
                <label for="semaine">Filtrer par semaine : </label>
                <input type="week" name="semaine" id="semaine">
                <input type="submit" value="filtrer">
                @csrf
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-11">
            <a class="btn btn-secondary btn-sm" href="{{route('planning.filtre_semaine', ['semaine' => $prev->toDateString()])}}">semaine précédente</a> -
            <a class="btn btn-secondary btn-sm" href="{{route('planning.filtre_semaine', ['semaine' => $next->toDateString()])}}">semaine suivante</a>
        </div>

        <div class="col-md-1 float-right">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#noteModal">
                Note
            </button>

            <!-- Modal -->
            <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="exampleModalLabe" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabe">Recherche par semaine</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                Le formulaire de recherche par semaine n'est pas compatible avec Firefox ou Safari. Pour aller à une semaine donnée il faut donc naviguer via les bouttons 'semaine précédente' et 'semaine suivante'.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <th colspan="7" class="text-center">Semaine du {{$debut}}</th>
                    <tr>
                        <th>LUNDI</th>
                        <th>MARDI</th>
                        <th>MERCREDI</th>
                        <th>JEUDI</th>
                        <th>VENDREDI</th>
                        <th>SAMEDI</th>
                        <th>DIMANCHE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @unless(empty($plannings))
                            @for($i = 0; $i<=6; $i++)
                                <td>
                                    <div class="container-fluid">
                                        <div class="row border-bottom">
                                            <div class="col-md-12 text-center">
                                                {{$date->addDay()->toDateString()}}
                                            </div>
                                        </div>
                                        <br>
                                        @foreach($plannings as $planning)
                                            @if(substr($planning->date_debut, 0, 10) == $date->toDateString())
                                                <div class="row rounded-pill" style="background-color: lightblue">
                                                    <div class="col-md-3">
                                                        <div class="badge border-right text-wrap">
                                                            {{substr($planning->date_debut, 11, 5)}}
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
                                                <br>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            @endfor
                        @endunless
                    </tr>
                </tbody>
                @if(Auth::user()->type != 'etudiant')
                    <tfoot>
                        @for($i = 0; $i<=6; $i++)
                            <td>
                                <p>
                                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#planning{{$i}}" aria-expanded="false" aria-controls="collapseExample">
                                        Ajouter un planning
                                    </button>
                                </p>
                                <div class="collapse" id="planning{{$i}}">
                                    <form method="post" action="{{route('planning.store', ['date' => $date->startOfWeek()->addDays($i)->toDateString()])}}">
                                        <select name="cours_id" class="form-control" id="cours_id">
                                            @foreach($cours as $cour)
                                                <option value="{{$cour->id}}">{{$cour->intitule}}</option>
                                            @endforeach
                                        </select>
                                        <label for="debut" class="form-label">Debut</label>
                                         <input type="time" class="form-control" id="debut" name="debut">
                                        <label for="fin" class="form-label">Fin</label>
                                        <input type="time" id="fin" class="form-control" name="fin">
                                        <input type="submit" class="btn btn-success" value="ajouter">
                                        @csrf
                                    </form>
                                </div>
                            </td>
                        @endfor
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
