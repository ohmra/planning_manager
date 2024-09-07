@extends('modele')

@section('title', 'Modification Utilisateur')

@section('error')
@endsection

@section('content')
    <div class="alert alert-dismissible alert-light">
        <strong>Note :</strong> Laissez le <strong>login</strong> et/ou le <strong>mot de passe</strong> vide si vous ne souhaitez pas les modifier
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Modifier un utilisateur</h5>
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
                        <form method='post' action="{{route('user.update', ['id' => $user->id])}}">
                            @method('put')
                            <div class="form-label-group">
                                <label for="nom">Nom</label>
                                <input type="text" name="nom" value="{{$user->nom}}" id="nom" class="form-control" placeholder="Nom" required autofocus>
                            </div>

                            <div class="form-label-group">
                                <label for="prenom">Prenom</label>
                                <input type="text" name="prenom" value="{{$user->prenom}}" id="prenom" class="form-control" placeholder="Prenom" required>
                            </div>
                            <div class="form-label-group">
                                <label for="inputid">Login</label>
                                <input type="text" name="login" id="inputid" class="form-control" placeholder="{{$user->login}}">
                            </div>
                            <div class="form-label-group">
                                <label for="inputPassword">Password</label>
                                <input type="password" name="mdp" id="inputPassword" class="form-control">
                            </div>
                            <div class="form-label-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="mdp_confirmation" id="password_confirmation" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="type">Type de l'utilisateur</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="{{$user->type}}">{{$user->type}}</option> <!-- Pour connaitre le type de  depart -->
                                    <option value="admin" >Admin</option>
                                    <option value="enseignant" >Enseignant</option>
                                    <option value="etudiant" >Etudiant</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="formation">Formation</label>
                                <select name="formation_id" class="form-control" id="formation">
                                    @if(isset($user->formation))
                                        <option value="{{$user->formation->id}}">{{$user->formation->intitule}}</option>
                                    @endif
                                    <option value="null" name="null">ENSEIGNANT/ADMIN</option>
                                    @foreach($formations as $formation)
                                        <option value="{{$formation->id}}" name="{{$formation->id}}">{{$formation->intitule}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Modifier</button>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
