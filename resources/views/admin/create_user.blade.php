@extends('modele')

@section('title', 'Creation Utilisateur')

@section('error')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Créer un utilisateur</h5>
                        <div class="errors">
                            @if ($errors->any())
                                <div class="errors">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <form class="form-signin" method='post' action="{{route('user.store')}}">
                            <div class="form-label-group">
                                <label for="nom">Nom</label>
                                <input type="text" name="nom" value="{{old('nom')}}" id="nom" class="form-control" placeholder="Nom" required autofocus>
                            </div>

                            <div class="form-label-group">
                                <label for="prenom">Prenom</label>
                                <input type="text" name="prenom" value="{{old('prenom')}}" id="prenom" class="form-control" placeholder="Prenom" required>
                            </div>
                            <div class="form-label-group">
                                <label for="inputid">Login</label>
                                <input type="text" name="login" value="{{old('login')}}" id="inputid" class="form-control" placeholder="Login" required>
                            </div>
                            <div class="form-label-group">
                                <label for="inputPassword">Password</label>
                                <input type="password" name="mdp" id="inputPassword" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="form-label-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="mdp_confirmation" id="password_confirmation" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Type de l'utilisateur</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="admin" >Admin</option>
                                    <option value="enseignant" >Enseignant</option>
                                    <option value="etudiant" >Etudiant</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="formation">Formation</label>
                                <select name="formation_id" class="form-control" id="formation">
                                    <option value="null" name="null">ENSEIGNANT/ADMIN</option>
                                    @foreach($formations as $formation)
                                        <option value="{{$formation->id}}" name="{{$formation->id}}">{{$formation->intitule}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">créer</button>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
