@extends('modele')

@section('title', 'Liste des cours par formation')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <form method='post' action="{{route('cours_formation.search')}}">
                <input type="text" class="form-control" name="intitule" placeholder="intitule">
                <input type="submit" class="form-control" value="rechercher">
                @csrf
            </form>
        </div>
    </div>
    <div class="row">
        @unless(empty($cours))
            <div class="container">
                <table class="table table-striped">
                    <thead>
                    <th colspan="1" class="text-center">Liste des cours pour la formation {{Auth::user()->formation->intitule}}</th>
                    <tr>
                        <th>Intitule</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($cours as $cour)
                        <tr>
                            <td>{{$cour->intitule}}</td>
                            @if(Auth::user()->type == 'etudiant')
                                <td>
                                    @if(Auth::user()->coursEtudiant->contains($cour))
                                        <a class="btn btn-warning" href="{{route('cours.desinscription', ['id' => $cour->id])}}">desinscription</a>
                                    @else
                                        <a class="btn btn-success" href="{{route('cours.inscription', ['id' => $cour->id])}}">inscription</a>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else <p>No Cours found</p>
        @endunless
    </div>
</div>
@endsection
