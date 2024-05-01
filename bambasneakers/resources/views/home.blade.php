@extends('plantillas.base')

@section('title', "Bamba's")

@section('content')
<div class="container-fluid mt-3 mb-3">
  <div class="welcome-message text-light p-5 rounded">
    <h1 class="text-center text-danger fw-bold mb-4 bambas" style="font-size: 2rem;">Bienvenido a<br><span class="bambas text-primary">BA</span><span class="bambas text-white">MB</span><span class="bambas text-warning">A'S</span></h1>
    <p class="lead text-center fw-bold">Anunciamos con orgullo la apertura de nuestra nueva tienda en Vecindario, Gran Canaria.<br>¡La tienda de zapatillas más grande de Canarias!</p>
  </div>
</div>
<div id="carouselExampleSlidesOnly" class="carousel carousel-fade mb-3 mx-auto" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="{{ asset('img/nike2.gif') }}" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('img/nike.gif') }}" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('img/nike3.gif') }}" class="d-block w-100" alt="...">
    </div>
  </div>
</div>
<div class="container-fluid mb-5 mx-auto">
  <div class="container-fluid text-center mt-5">
    @if(count($novedades) > 0)
      <h5 class="mx-auto fw-bold novedades text-danger">Novedades</h5>
      <hr class="mt-3 mb-5" style="color: white">
    @endif
  </div>
  <div class="row flex-row flex-nowrap overflow-hidden" id="carouselContainer">
    @foreach($novedades as $novedad)
    <div class="col-3" draggable="false" style="user-select: none;">
        <div class="card card-block position-relative bg-dark border-0 rounded">
            @php
                $fecha_actual = Carbon\Carbon::now();
            @endphp
            <div class="newSneaker-container position-absolute top-0 start-0 w-100 border-0 @if($fecha_actual->diffInDays($novedad->fecha_lanzamiento) > 7) d-none @endif" style="background-color: rgba(0, 0, 0, 0.75);">
                <p class="text-white text-center fw-bolder p-1 m-0">NUEVO LANZAMIENTO</p>
            </div>
            <div class="card-img-container2">
            <a href="/sneaker/{{$novedad->id}}" draggable="false"><img src="{{ $novedad->imagen }}" class="card-img-top-desc" alt="{{ $novedad->nombre }}" draggable="false"></a>
            </div>
            <div class="card-body d-flex flex-column justify-content-center text-white">
                <h5 class="nombreProducto text-danger" href="/sneaker/{{$novedad->id}}">{{ $novedad->nombre }}</h5>
                <hr class="mb-2 mt-2">
                <p class="text-white fw-bold">{{ $novedad->marca }} - {{ $novedad->categoria }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-text fw-bold mt-2">{{ $novedad->precio }}€ @if($novedad->pvp > $novedad->precio)<del class="text-muted">{{ $novedad->pvp }}€</del>@endif</p>
                    <a href="/sneaker/{{$novedad->id}}" class="btn btn-light" draggable="false">Ver más</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
  </div>
</div>
<script src="{{ asset('js/scroll.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
@endsection