@extends('plantillas.base')

@section('title', "Confirmar pedido - Bamba's")

@section('content')
<section id="sectionConfirmarPedido" class="pt-5 pb-5">
    <div class="container">
        <div class="row w-100">
            <div class="col-12">
                <h3 class="display-5 mb-4 text-left fw-bold"><b>CONFIRMAR PEDIDO</b></h3>

                @if(isset($carrito) && isset($datos) && count($carrito) > 0 && count($datos) > 0)
                    <table id="carrito" class="table table-condensed table-responsive table-hover">
                        <thead>
                            <tr>
                                <th style="width:70%" class="text-left">Producto</th>
                                <th style="width:20%" class="text-left">Cantidad</th>
                                <th style="width:10%" class="text-left">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $subtotal = 0;
                            $total = 0;
                        @endphp
                        @foreach($carrito as $item)
                            @php
                                $producto = collect($datos)->where('id', $item['id_zapatilla'])->first();
                                $subtotal += $producto['precio'] * $item['cantidad'];
                                $total += $producto['pvp'] * $item['cantidad'];
                            @endphp
                            <tr>
                                <td data-th="producto">
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" class="img-fluid d-none d-md-block rounded mb-2 shadow ">
                                        </div>
                                        <div class="col-md-9 text-left mt-sm-2">
                                            <h4>{{ $producto['nombre'] }}@if($producto['pvp'] > $producto['precio']) <span class="text-warning ms-1">-{{ round((($producto['pvp'] - $producto['precio']) / $producto['pvp']) * 100) }}%</span>@endif</h4>
                                            <p class="font-weight-light text-muted" style="font-size: 1.5vh;">{{ $producto['marca'] }} - {{ $producto['categoria'] }} (Talla {{ $item['id_talla'] }})</p>
                                            <p class="font-weight-light text-muted mb-2" style="font-size: 1.3vh;">{{ $producto['descripción'] }}</p>
                                            <p style="font-size: 2vh;">{{ $producto['precio'] }} € <br>@if($producto['pvp'] > $producto['precio']) <del class="text-muted">{{ $producto['pvp'] }} €</del> @endif</p>
                                        </div>
                                    </div>
                                </td>
                                <td data-th="cantidad">{{ $item['cantidad'] }}</td>
                                <td><p id="precioTotal" data-th="precio">{{ ($producto['precio']) * ($item['cantidad']) }} €</p>
                                    @if($producto['pvp'] > $producto['precio'])
                                        <p id="pvpTotal" class="text-muted"><del>{{ ($producto['pvp']) * ($item['cantidad']) }} €</del></p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($subtotal < $total)
                        <tr>
                            <td colspan="2" class="text-left"><b>Total</b></td>
                            <td class="text-left">
                                <p class="text-muted"><del><b>{{ $total }} €</b></del></p>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="2" class="text-left"><b>Precio final</b></td>
                            <td class="text-left">
                                <b>{{ $subtotal }} €</b>
                                @if($subtotal < $total)<span class="descuento border rounded p-1">Ahorras -{{ round((($total - $subtotal) / $total) * 100) }}%</span>@endif
                            </td>

                        </tr>
                    </tbody>
                    </table>
                @endif
                <div class="row mt-4 d-flex align-items-center">
                    <div class="col-6 order-2 text-right">
                        <button class="btn btn-dark mb-4 btn-lg pl-5 pr-5" id="btnConfirmarPedido">Confirmar pedido</button>
                    </div>
                    <div class="col-6 mb-3 mb-m-1 order-1 text-md-left">
                        <a href="/carrito"><i class="fas fa-arrow-left me-2"></i>Volver al carrito</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/pedido.js') }}"></script>
</section>
@endsection