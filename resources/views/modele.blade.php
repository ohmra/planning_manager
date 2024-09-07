<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="{{asset('/css/bootstrap.min.css')}}"  rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"/>
    <link  href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @section('menu')
                @auth
                    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                        @if(Auth::user()->type == 'admin')
                            <a class="navbar-brand" href="{{route('home_admin')}}">Acceuil Admin</a>
                        @else
                            <a class="navbar-brand" href="{{route('acceuil')}}">Accueil</a>
                        @endif
                        <div class="collapse navbar-collapse" id="navbarColor02">
                            <ul class="navbar-nav mr-auto">
                                @if(Auth::user()->type == 'admin')
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Utilisateurs</a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{route('user.index')}}">Liste des utilisateurs</a>
                                            <a class="dropdown-item" href="{{route('admin.attente')}}">Liste des utilisateurs en attente</a>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('cours.index')}}">Cours</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('formation.index')}}">Formations</a>
                                    </li>
                                @endif
                                @if(Auth::user()->type == 'etudiant')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('cours.inscrit')}}">Mes cours</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('cours.formation_index')}}">Ma formation</a>
                                    </li>
                                @endif
                                @if(Auth::user()->type == 'enseignant')
                                    <li class="nav-item">
                                        <a href="{{route('cours.enseignant')}}" class="nav-link">Mes cours</a>
                                    </li>
                                @endif
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Plannings</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{route('planning.affichage')}}">Intégrale</a>
                                        <a class="dropdown-item" href="{{route('planning.filtre_semaine', ['semaine' => now()->toDateString()])}}">Par semaine</a>
                                    </div>
                                </li>
                            </ul>
                            <a class="nav-link" href="{{route('logout')}}">Deconnexion</a>
                        </div>
                    </nav>
                @endauth
            @show
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @section('etat')
                @if(session()->has('etat'))
                    <div class="alert alert-dismissible alert-primary col-sm-9 col-md-7 col-lg-5 mx-auto">
                        <button type="button" class="close" data-bs-dismiss="alert">&times;</button>
                        <p class="text-center">{{session()->get('etat')}}</p>
                    </div>
                @endif
            @show
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @section('error')
                @if ($errors->any())
                    <div class="alert alert-dismissible alert-danger col-sm-9 col-md-7 col-lg-5 mx-auto">
                        <button type="button" class="close" data-bs-dismiss="alert">&times;</button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @show
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>
</div>


<script src="/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
</script>




</body>
</html>
