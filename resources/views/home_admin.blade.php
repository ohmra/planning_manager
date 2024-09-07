@extends('modele')

@section('title', 'Page principale')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center rounded-pill border">
                <h1>{{$user->nom}} {{$user->prenom}}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 border-right">
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
            </div>
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#ADMIN">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#modifierMDP">MDP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#modifierNom">Changer Nom</a>
                    </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade" id="profile">
                        <h2>Nom : <small class="text-muted">{{$user->nom}}</small></h2>
                        <h2>Prenom : <small class="text-muted">{{$user->prenom}}</small></h2>
                        @unless(empty($user->formation))
                            <h2>Formation : <small class="text-muted">{{$user->formation->intitule}}</small></h2>
                        @endunless
                        <h2>Type : <small class="text-muted">{{$user->type}}</small></h2>
                        <h2>Login : <small class="text-muted">{{$user->login}}</small></h2>
                    </div>
                    <div class="tab-pane fade" id="modifierMDP">
                        <form method='post' action='{{route('user.update_password')}}'>
                            @method('put')
                            <div class="form-label-group">
                                <label for="mdp">Mot de passe actuel</label>
                                <input type="password" class="form-control" id="mdp" name='mdp' />
                            </div>
                            <div class="form-label-group">
                                <label for="newMDP">Nouveau Mot de passe</label>
                                <input type="password" class="form-control" id="newMDP" name='new_mdp' />
                            </div>
                            <div class="form-label-group">
                                <label for="newMDP_confirm">Confirmer Mot de passe</label>
                                <input type="password" class="form-control" id="newMDP_confirm" name='new_mdp_confirmation' />
                            </div>
                            <input class="btn btn-success btn-block" type="submit" value='Changer' />
                            @csrf
                        </form>
                    </div>
                    <div class="tab-pane fade" id="modifierNom">
                        <form method='post' action='{{route('user.update_name')}}'>
                            @method('put')
                            <div class="form-label-group">
                                <label for="nom">Nom</label>
                                <input type="text" name='nom' class="form-control" id="nom" value="{{$user->nom}}"/>
                            </div>
                            <div class="form-label-group">
                                <label for="prenom">Prenom</label>
                                <input type="text" name='prenom' class="form-control" id="prenom" value="{{$user->prenom}}" />
                            </div>
                            <input class="btn btn-success btn-block" type="submit" value='Changer' />
                            @csrf
                        </form>
                    </div>
                    <div class="tab-pane fade active show" id="ADMIN">
                        <div class="list-group">
                            <a href="{{route('planning.filtre_semaine', ['semaine' => now()->toDateString()])}}" class="list-group-item list-group-item-action">Planning cette semaine</a>
                            <a href="{{route('admin.attente')}}" class="list-group-item list-group-item-action">Utilisateur en attente <span class="badge badge-danger">{{count($attente)}}</span></a>
                            <a href="{{route('user.create')}}" class="list-group-item list-group-item-action">Creer un utilisateur</a>
                            <a href="{{route('etudiant.index')}}" class="list-group-item list-group-item-action">Voir les etudiants</a>
                            <a href="{{route('enseignant.index')}}" class="list-group-item list-group-item-action">Voir les enseignants</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

