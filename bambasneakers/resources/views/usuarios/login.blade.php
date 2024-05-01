@extends('plantillas.base')

@section('title', 'Inicio de sesión')

@section('content')
    <div class="container-fluid mt-5 mx-auto">
        <form action="/confirmarLogin" id="form" method='POST'>
            @csrf
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <div class="card mt-5">
                        <div class="card-body">
                            @if(session('mensaje'))
                                <div class="alert alert-danger text-center">{{ session('mensaje') }}</div>
                            @endif
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput" name="correo" value="" maxlength="100" placeholder="Introduzca su dirección de correo electrónico" required>
                                <label for="floatingInput">Correo</label>
                                @error('correo')
                                    <small class="text-danger">{{ $message }} </small>
                                @enderror
                            </div>
                            <br>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="floatingInput" name="contraseña" value="" maxlength="255" placeholder="Introduzca su contraseña" required>
                                <label for="floatingInput">Contraseña</label>
                                @error('contraseña')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <div style="font-size: 13px; text-align: right">
                                    <a href="/login">¿Olvidaste tu contraseña?</a>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-success col-12 mb-2">Iniciar sesión</button>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row mx-auto">
                                        <div class="separator">
                                            <span class="line"></span>
                                            <span class="mensajeLog">O</span>
                                            <span class="line"></span>
                                        </div>
                                        <a href="/registro" class="btn btnForm btn-block btn-outline-light mb-2">Registrarse</a>
                                        <a class="btn btnGoogle btn-block btn-outline-light" id="btnEnviar" href="/loginGoogle" disabled><i class="fab fa-google me-2"></i>Iniciar sesión con Google</a>
                                    </div>                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </form>
    </div>

    <script src="{{ asset('js/validarForm.js') }}"></script>
@endsection