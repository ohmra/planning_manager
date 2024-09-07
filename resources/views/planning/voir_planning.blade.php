@extends('modele')

@section('content')

    Planning pour le cours {{$planning->cours->intitule}}
    <form method="post" action="{{route('planning.update', ['id' => $planning->id])}}">
        <input type="date" name="date" value="{{substr($planning->date_debut, 0, 10)}}">
        Debut : <input type="time" name="debut" value="{{substr($planning->date_debut, 11, 5)}}">
        Fin : <input type="time" name="fin" value="{{substr($planning->date_fin, 11, 5)}}">
        <input type="submit" value="modifier">
        @csrf
    </form>
@endsection
