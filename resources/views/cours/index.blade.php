@extends('modele')

@section('title', 'Liste Cours')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div>
                    <br>
                    <form class="form-group" method='post' action="{{route('cours.search')}}">
                        <input type="text" class="form-control" name="intitule" placeholder="intitule">
                        <input type="submit"  class="form-control" value="rechercher">
                        @csrf
                    </form>
                </div>

                <div>
                    <br>
                    <form class="form-group" method="post" action="{{route('cours.enseignants_index')}}">
                    <label for="id">Liste de cours par enseignant</label>
                    <select class="form-control" name="id" id="id">
                        @foreach($enseignants as $enseignant)

                            <option value="{{$enseignant->id}}">{{$enseignant->nom}} {{$enseignant->prenom}}</option>
                        @endforeach
                    </select>
                    <input type="submit" class="form-control" value="voir">
                    @csrf
                    </form>
                </div>

                <p>
                    <a href="#creerCours" class="btn btn-primary btn-block" data-bs-toggle="collapse">Créer un cours</a>
                </p>
                <div class="collapse" id="creerCours">
                    <div class="card card-signin my-5">
                        <div class="card-body">
                            <h5 class="card-title text-center">Créer un cours</h5>
                            <form class="form-signin" method='post' action="{{route('cours.store')}}">
                                <div class="form-label-group">
                                    <label for="nom">Intitule du cours</label>
                                    <input type="text" class="form-control" id="nom" name="intitule" value="{{old('intitule')}}" placeholder="intitule">
                                </div>
                                <div class="form-label-group">
                                    <label for="user_id">Enseignant</label>
                                    <select class="form-control" name="user_id" id="user_id">
                                        @foreach($enseignants as $enseignant)
                                            @if(old('user_id') == $enseignant->id)
                                                <option value="{{$enseignant->id}}">{{$enseignant->nom}} {{$enseignant->prenom}} selected</option>
                                            @else
                                            <option value="{{$enseignant->id}}">{{$enseignant->nom}} {{$enseignant->prenom}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-label-group">
                                    <label for="formation_id">Formation</label>
                                    <select class="form-control" name="formation_id" id="formation_id">
                                        @foreach($formations as $formation)
                                            @if(old('formation_id') == $formation->id)
                                                <option value="{{$formation->id}}" selected>{{$formation->intitule}}</option>
                                            @else
                                                <option value="{{$formation->id}}">{{$formation->intitule}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <input type="submit" class="btn btn-primary btn-lg btn-block text-uppercase" value="creer">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-9">
                @unless(empty($cours))
                    <div>
                        <table class="table">
                            <thead>
                            <th colspan="4" class="text-center">Liste des cours</th>
                            <tr>
                                <th>ID</th>
                                <th>Intitule</th>
                                <th>Formation</th>
                                <th>Enseignant</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($cours as $cour)
                                <tr>
                                    <td>{{$cour->id}}</td>
                                    <td>{{$cour->intitule}}</td>
                                    <td>{{$cour->formation->intitule}}</td>
                                    <td>
                                        <form method="post" action="{{route('enseignants.cours', ['id_cours' => $cour->id])}}">
                                            <select name="enseignant_id" id="enseignant_id">
                                                <option value="{{$cour->userEnseignant->id}}">{{$cour->userEnseignant->nom}} {{$cour->userEnseignant->prenom}}</option>
                                                @foreach($enseignants as $enseignant)
                                                    <option value="{{$enseignant->id}}">{{$enseignant->nom}} {{$enseignant->prenom}}</option>
                                                @endforeach
                                            </select>
                                            <input type="submit" value="associer">
                                            @csrf
                                        </form>
                                    </td>

                                    <td><a class="btn btn-primary" href="{{route('cours.modify', ['id' => $cour->id])}}">modifier</a></td>
                                    <td><a class="btn btn-danger" href="{{route('cours.delete', ['id' => $cour->id])}}">supprimer</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else <p>No user found</p>
                @endunless
            </div>
        </div>
    </div>
@endsection
