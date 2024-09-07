@extends('modele')

@section('title', 'List of Users')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('user.index')}}">Tout</a></li>
                    <li class="breadcrumb-item"><a href="{{route('enseignant.index')}}">Enseignants</a></li>
                    <li class="breadcrumb-item "><a href="{{route('etudiant.index')}}">Etudiants</a></li>
                </ol>
                <form method="post" action="{{route('user.search')}}">
                    <input type="text" class="form-control" name="recherche" placeholder="Login ou Nom ou Prénom">
                    <input type="submit" class="form-control" value="rechercher">
                    @csrf
                </form>
                <br>
                <div>
                    <a class="btn btn-primary btn-lg" href="{{route('user.create')}}">Creer un utilisateur</a>
                </div>

            </div>
            <div class="col-9">
                @unless(empty($users))
                    <div>
                        <table class="table table-striped">
                            <thead>
                            <th colspan="7" class="text-center">User List</th>
                            <tr>
                                <th>ID</th>
                                <th>Login</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Type</th>
                                <th>Formation</th>
                                <th>Cours</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->login}}</td>
                                    <td>{{$user->nom}}</td>
                                    <td>{{$user->prenom}}</td>
                                    <td>{{$user->type}}</td>
                                    <td>
                                        @unless(@empty($user->formation->intitule))
                                            {{$user->formation->intitule}}
                                        @endunless
                                    </td>
                                    <td>
                                    @unless(@empty($cours))
                                        @if($user->type == "enseignant")

                                                <form method="post" action="{{route('cours.enseignants', ['id_enseignant' => $user->id])}}">
                                                    <select name="cours_id" id="cours_id">
                                                        <option value="">----</option>
                                                        @foreach($cours as $cour)
                                                            <option value="{{$cour->id}}">{{$cour->intitule}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="submit" value="associer">
                                                    @csrf
                                                </form>
                                        @endif
                                    @endunless
                                    </td>

                                    <td><a class="btn btn-primary" href="{{route('user.modify', ['id' => $user->id])}}">modifier</a></td>
                                    <td><a class="btn btn-danger" href="{{route('user.delete', ['id' => $user->id])}}">supprimer</a></td>
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
