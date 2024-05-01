@extends('plantillas.base')

@section('title', "Carrito - Bamba's")

@section('content')
<section id="sectionCarrito" class="pt-5 pb-5">
  <div class="container">
    <div class="row w-100">
        <div class="col-12 ">
            <h3 class="display-5 mb-2 text-left fw-bold"><b>CARRITO DE COMPRA</b></h3>
            
            @if(isset($carrito) && isset($datos) && count($carrito) > 0 && count($datos) > 0)
            @php
                $totalPares = 0;
                foreach($carrito as $item) {
                    $totalPares += $item['cantidad'];
                }
            @endphp
                <p class="mb-5 text-left fw-bold">
                    <i class="text-danger">{{ count($carrito) }}</i> zapatilla/s - <i class="text-danger total-pares">{{ $totalPares }}</i> par/es</p>
            @else
                <table id="carrito" class="table table-condensed table-responsive table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td colspan="4">
                                <p class="mt-5">No hay elementos en el carrito</p>
                                <a href="/sneakers" class="btn btn-outline-dark mt-1 mb-5">Empezar a comprar</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
            @if(isset($carrito) && isset($datos) && count($carrito) > 0 && count($datos) > 0)
                <table id="carrito" class="table table-condensed table-responsive table-hover">
                    <thead>
                        <tr>
                            <th style="width:60%" class="text-left">Producto</th>
                            <th style="width:10%" class="text-left">Cantidad</th>
                            <th style="width:12%" class="text-left">Precio</th>
                            <th style="width:16%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = 0;
                        @endphp
                        @foreach($carrito as $item)
                            @php
                                $producto = collect($datos)->where('id', $item['id_zapatilla'])->first();
                                $subtotal += $producto['precio'] * $item['cantidad'];
                            @endphp
                            <tr data-id="{{ $producto['id'] }}" data-talla="{{ $item['id_talla'] }}">
                                <td data-th="producto">
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <a href="/sneaker/{{ $producto['id'] }}"><img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" class="img-fluid d-none d-md-block rounded mb-2 shadow" draggable="false"></a>
                                        </div>
                                        <div class="col-md-9 text-left mt-sm-2">
                                            <a href="/sneaker/{{ $producto['id'] }}"><h4>{{ $producto['nombre']}}</a>@if($producto['pvp'] > $producto['precio']) <span class="text-warning ms-1">-{{ round((($producto['pvp'] - $producto['precio']) / $producto['pvp']) * 100) }}%</span>@endif</h4>
                                            <p class="font-weight-light text-muted" style="font-size: 1.5vh;">{{ $producto['marca'] }} - {{ $producto['categoria'] }} (Talla {{ $item['id_talla'] }})</p>
                                            <p class="font-weight-light text-muted mb-2" style="font-size: 1.3vh;">{{ $producto['descripción'] }}</p>
                                            <p id="precioUnidad" style="font-size: 2vh;">{{ $producto['precio'] }} €</p>
                                            @if($producto['pvp'] > $producto['precio'])
                                                <p class="text-muted" id="pvpUnidad" style="font-size: 2vh;"><del>{{ $producto['pvp'] }} €</del></p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td data-th="cantidad">
                                    <input type="number" class="form-control form-control-md text-center" value="{{ $item['cantidad'] }}" min="1" max="{{ $producto['stock'] }}">
                                </td>
                                <td>
                                    <p id="precioTotal" data-th="precio">{{ ($producto['precio']) * ($item['cantidad']) }} €</p>
                                    @if($producto['pvp'] > $producto['precio'])
                                        <p id="pvpTotal" class="text-muted"><del>{{ ($producto['pvp']) * ($item['cantidad']) }} €</del></p>
                                    @endif
                                </td>
                                <td class="acciones" data-th="">
                                <div class="text-right">
                                    <button class="btnEliminar btn border-secondary btn-md mb-2" data-id="{{ $producto['id'] }}" data-talla="{{ $item['id_talla'] }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-right">
                                <button class="btn btn-outline-danger btn-lg btn-block" id="vaciarCarrito">Vaciar carrito<i class="fas fa-trash ms-2"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="float-right text-right">
                    <h4><b>Precio total:</b></h4>
                    <h1 id="subtotal">{{ $subtotal }} €</h1>
                </div>
            @endif
            
        </div>
    </div>
    
    @if(isset($carrito) && isset($datos) && count($carrito) > 0 && count($datos) > 0)
        <div class="row mt-4 d-flex align-items-center">
            <div class="col-sm-6 order-md-2 text-right">
                <a href="/realizarPedido" class="btn btn-dark mb-4 btn-lg pl-5 pr-5">Realizar pedido</a>
            </div>
            <div class="col-sm-6 mb-3 mb-m-1 order-md-1 text-md-left">
                <a href="/sneakers"><i class="fas fa-arrow-left me-2"></i>Continuar comprando</a>
            </div>
        </div>
    @endif

</div>
</section>
<script src="{{ asset('js/accionesCarrito.js') }}"></script>
@endsection