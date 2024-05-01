@extends('plantillas.base')

@section('title', "Zapatillas - Bamba's")

@section('content')
<section id="sectionProductos">
    <div class="container">
        <div class="row justify-content-center text-center">
        @if(request()->has('param'))
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="text-left"><b>Resultados de la búsqueda: </b>{{ request()->get('param') }}</h3>
                    </div>
                    @if(count($zapatillas) > 0)
                    <div>
                        <h3 class="text-right"><b>{{count($zapatillas)}} resultado/s</b></h3>
                    </div>
                    @endif
                </div>
                @if(count($zapatillas) > 0)
                <hr class="mt-3"> 
                @endif
            </div>
        @elseif(request()->is('sneakers') && count($zapatillas) > 0)
            <div class="col-md-8 col-lg-6">
                <div class="header">
                    <h2 class="bambas text-danger">Bamba's</h3>
                    <h2>Nuestro catálogo de bambas</h2>
                    <hr class="mt-3">
                </div>
            </div>
        @endif
        </div>
        @if(count($zapatillas) > 1)
            <div class="row justify-content-center mt-3">
                <div class="col-12">
                    <div class="dropdown bg-transparent d-flex">
                        <button class="btn btn-dark dropdown-toggle fw-bold" type="button" id="dropdownOrdenarPor" data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                                $orderby = request()->has('orderby') ? request()->get('orderby') : '';
                                $texto = '';
                                switch($orderby) {
                                    case 'nombreA-Z':
                                        $texto = 'Nombre A-Z';
                                        break;
                                    case 'nombreZ-A':
                                        $texto = 'Nombre Z-A';
                                        break;
                                    case 'precioAsc':
                                        $texto = 'Precio ascendente';
                                        break;
                                    case 'precioDesc':
                                        $texto = 'Precio descendente';
                                        break;
                                    case 'maxDesc':
                                        $texto = 'Mayor descuento';
                                        break;
                                    default:
                                        $texto = 'Más nuevas';
                                }
                            @endphp
                            Ordenar por: {{ $texto }}
                        </button>
                        <ul class="dropdown-menu text-center fw-bold bg-dark" aria-labelledby="dropdownOrdenarPor">
                            @php
                                $opciones = [
                                    'nombreA-Z' => 'Nombre A-Z',
                                    'nombreZ-A' => 'Nombre Z-A',
                                    'precioAsc' => 'Precio ascendente',
                                    'precioDesc' => 'Precio descendente',
                                    'maxDesc' => 'Mayor descuento',
                                    '' => 'Más nuevas'
                                ];
                            @endphp
                            @foreach($opciones as $opcion => $texto)
                                @if($orderby != $opcion)
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['orderby' => $opcion]) }}">{{ $texto }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            @if(count($zapatillas) > 0)
                @foreach($zapatillas as $zapatilla)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <a href="/sneaker/{{ $zapatilla['id'] }}" prefetch>
                        <div id="producto-{{ $zapatilla['id'] }}" class="producto" style="background-image: url({{ $zapatilla['imagen'] }});" loading="lazy" fetchpriority="high">
                                <div class="parteSup">
                                    @if($zapatilla['pvp'] > $zapatilla['precio'])
                                        <span class="descuento">-{{ round((($zapatilla['pvp'] - $zapatilla['precio']) / $zapatilla['pvp']) * 100) }}%</span>
                                    @endif
                                    @php
                                        $fechaLanzamiento = new DateTime($zapatilla['fecha_lanzamiento']);
                                        $diferenciaDias = (new DateTime())->diff($fechaLanzamiento)->days;
                                    @endphp
                                    @if($diferenciaDias < 7)
                                        <span class="nuevo">Nuevo</span>
                                    @endif
                                </div>
                                <div class="parteInf">
                                    <hr class="mt-1 mb-2">
                                    <h3 class="nombreProducto"><b>{{ $zapatilla['nombre'] }}</b></h3>
                                    <h4 class="text-light text-muted"><b>{{ $zapatilla['marca'] }} - {{ $zapatilla['categoria'] }}</b></h3>
                                    <br>
                                    @if($zapatilla['precio'] < $zapatilla['pvp'])
                                        <h4 class="precio">{{ $zapatilla['precio'] }} €</h4>
                                        <h4 class="pvp">{{ $zapatilla['pvp'] }} €</h4>
                                    @else
                                        <h4 class="precio">{{ $zapatilla['precio'] }} €</h4>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @elseif(request()->is('sneakers') && count($zapatillas) == 0)
                <div class="col-12">
                    <div class="alert alert-danger mt-4" role="alert">
                        <h4 class="alert-heading text-center">¡Ups!</h4>
                        <p class="text-center">No hay zapatillas disponibles.</p>
                    </div>
                </div>
            @elseif(count($zapatillas) == 0 && request()->has('param'))
                <div class="col-12">
                    <div class="alert alert-danger mt-4" role="alert">
                        <h4 class="alert-heading text-center">¡Ups!</h4>
                        <p class="text-center">No hemos encontrado resultados para tu búsqueda.</p>
                    </div>
                </div>
            @endif
            
            @if(!empty($zapatillas) && $zapatillas instanceof \Illuminate\Pagination\LengthAwarePaginator && !$zapatillas->isEmpty())
            <div class="row justify-content-center">
                <div class="col-12">
                    {{ $zapatillas->appends(['page' => $zapatillas->currentPage()])->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<script src="{{ asset('js/filtrarZapatillas.js') }}"></script>

@endsection