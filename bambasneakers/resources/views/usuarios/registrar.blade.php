@extends('plantillas.base')

@section('title', 'Registrarse')

@section('content')
    <div class="container-fluid mt-5 mx-auto">
        <form action="/confirmarRegistro" id="form" method='POST'>
            @csrf
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <div class="card mt-5">
                        <div class="card-body">
                            @if(session('mensaje'))
                                <div class="alert alert-danger text-center">{{ session('mensaje') }}</div>
                            @endif
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="" maxlength="100" placeholder="Introduzca su nombre" required>
                                @error('nombre')
                                    <small class="text-danger">{{ $message }} </small>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="correo">Correo:</label>
                                <input type="text" class="form-control" id="correo" name="correo" value="" maxlength="100" placeholder="Introduzca su dirección de correo electrónico" required>
                                @error('correo')
                                    <small class="text-danger">{{ $message }} </small>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="contraseña">Contraseña:</label>
                                <div class="input-group">
                                    <i class="fas fa-eye-slash show-hide" id="eyeIcon-Password"></i>
                                    <input type="password" class="form-control" id="contraseña" name="contraseña" value="" maxlength="255" placeholder="Introduzca su contraseña" required>
                                </div>
                                @error('contraseña')
                                    <small class="text-danger">{{ $message }} </small>
                                @enderror
                            </div>
                            <div class="avisoSecurity">
                                <div class="mensajeSecurity">
                                    <i class="fas fa-exclamation-circle error-icon" id="passwordSecurityIcon"></i>
                                    <small class="text-danger" id="passwordSecurityError"></small>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="input-group">
                                    <i class="fas fa-eye-slash show-hide" id="eyeIcon-ConfirmPassword"></i>
                                    <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" value="" maxlength="255" placeholder="Repita su contraseña" required>
                                </div>
                                @error('contraseña')
                                    <small class="text-danger">{{ $message }} </small>
                                @enderror
                            </div>
                            <div class="avisoMatch">
                                <div class="mensajeMatch">
                                    <i class="fas fa-exclamation-circle error-icon" id="passwordMatchIcon"></i>
                                    <small class="text-danger" id="passwordMatchError">Las contraseñas no coinciden.</small>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                            <label for="genero">Género:</label>
                                <div class="select-group">
                                    <select class="form-select" type="genero" class="form-control" id="genero" name="genero" value="" maxlength="255" required>
                                        <option value="" disabled selected></option>
                                        <option value="Hombre">Hombre</option>
                                        <option value="Mujer">Mujer</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                @error('contraseña')
                                    <small class="text-danger">{{ $message }} </small>
                                @enderror
                            </div>
                            <br>
                            <button type="submit" class="btn btn-success col-12 mb-3" id="botonEnviar" disabled>Registrarse</button>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row mx-auto">
                                        <div class="separator fw-bold">
                                            <span class="line"></span>
                                            <span class="mensajeReg">¿Ya tienes cuenta?</span>
                                            <span class="line"></span>
                                        </div>
                                        <h5 class="card-title text-center mb-3"></h5>
                                        <a href="/login" class="btn btnForm btn-block btn-outline-light">Iniciar sesión</a>
                                    </div>                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/validarPassword.js') }}"></script>
@endsection