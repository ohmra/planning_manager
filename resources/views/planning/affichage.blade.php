@extends('modele')

@section('title', 'Affichage du planning')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-4 border-right">
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
                <form method="get" action="{{route('affichage.cours_filtre')}}">
                    <h1><label for="cours_id"> Filtrer par cours : </label></h1>
                    <select name="cours_id" class="form-control" id="cours_id">
                        @foreach($cours as $cour)
                            <option value="{{$cour->id}}">{{$cour->intitule}}</option>
                        @endforeach
                        <input type="submit" class="btn btn-primary btn-lg" value="filtrer">
                    </select>
                    @csrf
                </form>
            </div>
            <div class="col-6 mx-auto">
                <div class="container-fluid">
                    {{$plannings->links()}}
                    @foreach($plannings as $planning)
                        @if(substr($planning->date_debut, 0, 10) != session()->get('debut'))
                            <br>
                            <div class="row border border-dark rounded-pill">
                                <div class="col-12 text-center">
                                    <h1>{{substr($planning->date_debut, 0, 10)}}</h1>
                                </div>
                            </div>
                            {{session()->put('debut', substr($planning->date_debut, 0, 10))}}
                        @endif
                            <div class="row rounded-pill" style="background-color: lightblue">
                                <div class="col-md-3">
                                    <div class="border-right">
                                        {{substr($planning->date_debut, 11, 5)}} <br>
                                        {{substr($planning->date_fin, 11, 5)}}
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    {{$planning->cours->intitule}}
                                </div>
                            </div>
                    @endforeach
                    <br>
                    {{$plannings->links()}}
                    {{session()->forget('debut')}}
                </div>
            </div>
        </div>
    </div>
@endsection
