@extends('modele')

@section('title', 'Page principale')

@section('error')
@endsection

@section('content')
    @guest
        <div class="container">
                <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                    <div class="card card-signin my-5">
                        <div class="card-body">
                            <h5 class="card-title text-center">Connexion</h5>
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
                            <form class="form-signin" method="post" action="{{route('user.login')}}">
                                <div class="form-label-group">
                                    <label for="inputid">Login</label>
                                    <input type="text" name="login" id="inputid" class="form-control" placeholder="Login" required autofocus>
                                </div>

                                <div class="form-label-group">
                                    <label for="inputPassword">Password</label>
                                    <input type="password" name="mdp" id="inputPassword" class="form-control" placeholder="Password" required>
                                </div>
                                <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">connexion</button>
                                @csrf
                            </form>
                            <hr class="my-4">
                            <div>
                                Vous n'êtes pas encore un utilisateur? <br>
                                <a href="{{route('register')}}">Creer un compte</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    @endguest
@endsection

