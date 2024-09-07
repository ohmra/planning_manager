@extends('modele')

@section('title', 'Liste Cours')

@section('content')
    @unless(empty($cours))
        <div class="col-sm-9 col-md-9 col-lg-9 mx-auto">
            <table class="table">
                <thead>
                <th colspan="2" class="text-center">Liste des cours pour {{$enseignant->nom}} {{$enseignant->prenom}}</th>
                <tr>
                    <th>Intitule</th>
                    <th>Formation</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($cours as $cour)
                    <tr>
                        <td>{{$cour->intitule}}</td>
                        <td>{{$cour->formation->intitule}}</td>
                        @if(Auth::user()->type == 'admin')
                            <td><a class="btn btn-primary" href="{{route('cours.modify', ['id' => $cour->id])}}">modifier</a></td>
                            <td><a class="btn btn-danger" href="{{route('cours.delete', ['id' => $cour->id])}}">supprimer</a></td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else <p>No user found</p>
    @endunless
@endsection
