@extends('modele')

@section('title', 'Liste des formations')

@section('content')
<div class="row">
    <div class="col-md-3 mx-auto">
        <form method="post" action="{{route('formation.store')}}">
            <label for="CreerIntitule" class="col-form-label">Creer une formation</label>
            <input type="text" class="form-control" id="CreerIntitule" value="{{old('intitule')}}" name="intitule" placeholder="intitule">
            <input class="btn btn-success form-control" type="submit" value="creer">
            @csrf
        </form>
    </div>
</div>
<br>
<div class="row">
    @unless(empty($formations))
        <div class="container">
            <table class="table table-striped">
                <thead>
                <th  colspan="3" class="text-center">Liste des formations</th>
                <tr>
                    <th>ID</th>
                    <th>Intitule</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($formations as $formation)
                    <tr>
                        <td>{{$formation->id}}</td>
                        <td>
                            <form method='post' action="{{route('formation.update', ['id' => $formation->id])}}">
                                <input type="text" name="intitule" value="{{$formation->intitule}}">
                                <input class="btn btn-primary" type="submit" value="modifier">
                                @csrf
                            </form>
                        </td>
                        <td><a class="btn btn-danger" href="{{route('formation.delete', ['id' => $formation->id])}}">supprimer</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else <p>No user found</p>
    @endunless
</div>
@endsection
