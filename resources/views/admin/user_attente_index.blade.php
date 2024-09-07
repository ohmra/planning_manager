@extends('modele')

@section('title', 'Utilisateur en attente')

@section('content')

    @unless(empty($users))
        <div class="col-sm-9 col-md-9 col-lg-9 mx-auto">
            <table class="table">
                <thead>
                <th colspan="4" class="text-center">Liste d'utilisateur en attente</th>
                <tr>
                    <th>Login</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Formation</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{$user->login}}</td>
                        <td>{{$user->nom}}</td>
                        <td>{{$user->prenom}}</td>
                        <td>
                            @unless(@empty($user->formation->intitule))
                                {{$user->formation->intitule}}
                            @endunless
                        </td>
                            <td>
                                <a class="btn btn-primary" href="{{route('user.accept', ['id' => $user->id])}}">accepter</a>
                                <a class="btn btn-danger" href="{{route('user.reject', ['id' => $user->id])}}">refuser</a>
                            </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else <p>No user found</p>
    @endunless
@endsection
