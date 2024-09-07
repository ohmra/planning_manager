@extends('modele')

@section('title', 'Modification de Cours')

@section('content')
    <div class="col-md-6 mx-auto">
        <div class="card card-signin my-5">
            <div class="card-body">
                <h5 class="card-title text-center">Modifier un cours</h5>
                <form class="form-signin" method='post' action="{{route('cours.update', ['id' => $cours->id])}}">
                    <div class="form-label-group">
                        <label for="nom">Intitule du cours</label>
                        <input type="text" class="form-control" value="{{$cours->intitule}}" id="nom" name="intitule" placeholder="intitule">
                    </div>
                    <div class="form-label-group">
                        <label for="user_id">Enseignant</label>
                        <select class="form-control" name="user_id" id="user_id">
                            <option value="{{$cours->userEnseignant->id}}">{{$cours->userEnseignant->nom}} {{$cours->userEnseignant->prenom}}</option>
                            @foreach($enseignants as $enseignant)
                                <option value="{{$enseignant->id}}">{{$enseignant->nom}} {{$enseignant->prenom}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-label-group">
                        <label for="formation_id">Formation</label>
                        <select class="form-control" name="formation_id" id="formation_id">
                            <option value="{{$cours->formation->id}}">{{$cours->formation->intitule}}</option>
                            @foreach($formations as $formation)
                                <option value="{{$formation->id}}">{{$formation->intitule}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary btn-lg btn-block text-uppercase" value="Modifier">
                    @csrf
                </form>
            </div>
        </div>
    </div>



@endsection
