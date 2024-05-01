<nav id="banner" class="navbar navbar-expand-md justify-content-center">
<div class="container">
    <a class="navbar-brand ms-1 text-danger" href="{{ route('home') }}" draggable="false"><b>BAMBA's</b></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link @if(request()->is('/')) active @endif" aria-current="page" href="/">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->is('sneakers')) active @endif" aria-current="page" href="/sneakers">Sneakers</a>
        </li>
      </ul>
      @if(!request()->is('login') && !request()->is('registro') && !request()->is('carrito'))
      <div class="navbar-search-container d-flex justify-content-center text-white">
          <form id="searchForm" class="d-flex mx-auto my-2 my-md-0 navbar-search-form">
              <input id="inputBusqueda" class="form-control ms-2 me-1 bg-dark text-white text-center" type="search" placeholder="{{ isset($_GET['param']) ? '' : 'Buscar' }}" aria-label="Buscar" value="{{ isset($_GET['param']) ? $_GET['param'] : '' }}" name="param">
              <button id="btnBuscar" class="btn btn-outline-danger me-3" type="submit"><i class="fas fa-search"></i></button>
          </form>
      </div>
      @endif
      <ul class="navbar-nav ms-auto me-2 mb-2 mb-md-0">
        <li class="nav-item">
          <div class="d-flex align-items-center">
            @if(isset($_SESSION['iniciada']) && $_SESSION['iniciada'])
            <div class="dropdown me-2">
                  <a class="dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(isset($_SESSION['avatar']) && $_SESSION['avatar'] != null)
                      <img src="{{$_SESSION['avatar']}}" class="rounded-circle me-2" alt="{{$_SESSION['usuario']}}" style="width: 30px; height: 30px;">
                    @else
                      <i class="fas fa-user-circle"></i>
                    @endif
                      <span><b>Bienvenido, {{$_SESSION['usuario']}}</b></span>
                  </a>
                  <div id="menuUsuario" class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                      <a id="logout" class="dropdown-item light fw-bold" href="/logout"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a>
                  </div>
                </div>
                <a class="nav-link" href="/carrito"><i class="fas fa-shopping-cart"></i></a>
                
            @elseif(!(request()->is('login') || request()->is('registro')))
                <a class="nav-link me-2" href="/login"><i class="fas fa-user me-1"></i><span><b>Iniciar sesión</b></span></a>
                <a class="nav-link" href="/login"><i class="fas fa-shopping-cart"></i></a>
            @endif
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
<hr>
<script src="{{ asset('js/busqueda.js') }}"></script>
