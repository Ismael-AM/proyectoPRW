@extends('plantillas.base')

@section('title', $zapatilla['marca'] . ' ' . $zapatilla['nombre'] . ' - Bamba\'s')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center">
            <img id="img" src="{{ $zapatilla['imagen'] }}" width="400vw" height="400vh" class="img-fluid mb-3 skeleton-img" alt="{{ $zapatilla['nombre'] }}" draggable="false">
            <input id="range" type="range" class="range mb-1" style="width: 50%;" min="1" max="36" step="1" value="1"></input>
            <p class="fw-bold text-muted mb-5"> Deslice para rotar la zapatilla</p>
        </div>

        <div class="col-md-6">
            <h3 class="tituloProducto mb-1 text-danger"><b>{{ $zapatilla['nombre'] }}</b></h3>
            <h4 class="title mb-3 text-muted fw-bold">{{ $zapatilla['marca'] }} - {{ $zapatilla['categoria'] }}</h4>
            <p class="price-detail-wrap mb-3"> 
                <span class="h3">
                    @if($zapatilla['pvp'] > $zapatilla['precio'])
                        <span><b>{{ $zapatilla['precio'] }} €</b></span>
                        <del class="text-muted text-light">{{ $zapatilla['pvp'] }} €</del>
                        <span class="text-warning fw-bold ms-1">-{{ round((($zapatilla['pvp'] - $zapatilla['precio']) / $zapatilla['pvp']) * 100) }}%</span>
                    @else
                        <span><b>{{ $zapatilla['precio'] }} €</b></span>
                    @endif
                </span> 
            </p>
            <p><strong>Descripción</strong><br> {{ $zapatilla['descripción'] }} </p>
            <div class="row">
                <div class="col-sm-5 mt-3">
                    <div class="param param-inline">
                        <div>Talla </div>
                        <div>
                            <select id="talla" name="talla" class="form-control form-control-sm" style="width:70px;">
                                @foreach($zapatilla['tallas'] as $talla)
                                    <option value="{{ $talla['talla'] }}">{{ $talla['talla'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>  
                </div>
            </div>
            <hr class="mb-4 mt-4">
            <button id="btnAñadir" data-id="{{ $zapatilla['id'] }}" class="btn btn-lg btn-outline-dark text-uppercase justify-self-right"><i class="fas fa-shopping-cart"></i>Añadir al carrito</button>
        </div>
    </div>
    <div class="col-md-6 d-flex justify-content-center align-items-center">
</div>

<div class="container-fluid mb-5">
    <h3 class="mb-4 mt-5 fw-bold">Recomendadas para ti</h3>
    <div class="row flex-row flex-nowrap overflow-hidden" id="carouselContainer">
        @foreach($recomendaciones as $recomendacion)
        <div class="col-3" draggable="false" style="user-select: none;">
            <div class="card card-block position-relative">
                <div class="discount-container position-absolute top-0 start-0 w-100 @if(!($recomendacion->pvp > $recomendacion->precio)) d-none @endif">
                    <p class="bg-danger text-white text-center fw-bolder p-1 m-0">DESCUENTO DEL {{round((($recomendacion->pvp - $recomendacion->precio) / $recomendacion->pvp) * 100)}}%</p>
                </div>
                @php
                    $fecha_actual = Carbon\Carbon::now();
                @endphp
                <div class="newSneaker-container position-absolute top-0 start-0 w-100 @if($fecha_actual->diffInDays($recomendacion->fecha_lanzamiento) > 7) d-none @endif" style="background-color: rgba(128, 128, 128, 0.5);">
                    <p class="text-white text-center fw-bolder p-1 m-0">NUEVO LANZAMIENTO</p>
                </div>
                <div class="card-img-container">
                <a href="/sneaker/{{$recomendacion->id}}" draggable="false"><img src="{{ $recomendacion->imagen }}" class="card-img-top-desc" alt="{{ $recomendacion->nombre }}" draggable="false"></a>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="nombreProducto text-danger" href="/sneaker/{{$recomendacion->id}}">{{ $recomendacion->nombre }}</h5>
                    <hr class="mb-2 mt-2">
                    <p class="text-muted fw-bold">{{ $recomendacion->marca }} - {{ $recomendacion->categoria }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="card-text fw-bold mt-2">{{ $recomendacion->precio }}€ @if($recomendacion->pvp > $recomendacion->precio)<del class="text-muted">{{ $recomendacion->pvp }}€</del>@endif</p>
                        <a href="/sneaker/{{$recomendacion->id}}" class="btn btn-dark" draggable="false">Ver más</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
<script src="{{ asset('js/añadirAlCarrito.js') }}"></script>
<script src="{{ asset('js/scroll.js') }}"></script>
@endsection