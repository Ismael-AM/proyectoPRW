<footer class="footer @if(request()->is('/')) bg-body-dark text-white @else bg-body-tertiary @endif text-center mt-auto">
  <hr>
  <div class="container p-4">
    <section class="mb-4">
      <a data-mdb-ripple-init class="btn btn-outline btn-floating m-1 @if(request()->is('/')) text-white @endif" href="#!" role="button"><i class="fab fa-facebook-f"></i></a>
      <a data-mdb-ripple-init class="btn btn-outline btn-floating m-1 @if(request()->is('/')) text-white @endif" href="#!" role="button"><i class="fab fa-twitter"></i></a>
      <a data-mdb-ripple-init class="btn btn-outline btn-floating m-1 @if(request()->is('/')) text-white @endif" href="#!" role="button"><i class="fab fa-instagram"></i></a>
    </section>

    <section class="">
      <div class="row">
        <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase fw-bold"><b>MARCAS</b></h5>
          <br>
          <ul class="list-unstyled mb-0 @if(request()->is('/')) text-white @endif">
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="/sneakers/search?param=nike">Nike</a></li>
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="/sneakers/search?param=adidas">Adidas</a></li>
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="/sneakers/search?param=reebook">Reebook</a></li>
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="/sneakers/search?param=puma">Puma</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase fw-bold"><b>CONTACTO</b></h5>
          <br>
          <ul class="list-unstyled mb-0 @if(request()->is('/')) text-white @endif">
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif"><i class="fas fa-phone me-3"></i>(+34) 928 33 33 33</a></li>
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif"><i class="fas fa-envelope me-3"></i>bambasneakersgc@gmail.com</a></li>
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="https://www.google.com/maps/place/Centro+Comercial+Atl%C3%A1ntico+Vecindario/@27.8430351,-15.4396533,17.71z/data=!3m1!5s0xc409f08665057b3:0x7edf0a3ad78f612c!4m14!1m7!3m6!1s0xc409f07e0e224ab:0xf3e9033970b261fe!2sCentro+Comercial+Atl%C3%A1ntico+Vecindario!8m2!3d27.8433072!4d-15.4406529!16s%2Fg%2F11flc10gtm!3m5!1s0xc409f07e0e224ab:0xf3e9033970b261fe!8m2!3d27.8433072!4d-15.4406529!16s%2Fg%2F11flc10gtm?entry=ttu"><i class="fas fa-home me-3"></i>Centro Comercial Atlántico Vecindario</a></li>
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="https://www.google.com/maps/place/Centro+Comercial+Atl%C3%A1ntico+Vecindario/@27.8430351,-15.4396533,17.71z/data=!3m1!5s0xc409f08665057b3:0x7edf0a3ad78f612c!4m14!1m7!3m6!1s0xc409f07e0e224ab:0xf3e9033970b261fe!2sCentro+Comercial+Atl%C3%A1ntico+Vecindario!8m2!3d27.8433072!4d-15.4406529!16s%2Fg%2F11flc10gtm!3m5!1s0xc409f07e0e224ab:0xf3e9033970b261fe!8m2!3d27.8433072!4d-15.4406529!16s%2Fg%2F11flc10gtm?entry=ttu"><i class="fas fa-map-marker-alt me-3"></i>C. Adargoma, s/n, 35110 Vecindario, Las Palmas</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-6 mb-5 mb-md-0">
          <h5 class="text-uppercase fw-bold"><b>CATEGORÍAS</b></h5>
          <br>
          <ul class="list-unstyled mb-0 @if(request()->is('/')) text-white @endif">
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="/sneakers/search?param=niño/a">Niño/a</a></li>
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="/sneakers/search?param=mujer">Mujer</a></li>
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="/sneakers/search?param=hombre">Hombre</a></li>
            <li><a class="@if(request()->is('/')) text-white @else text-body @endif" href="/sneakers/search?param=unisex">Unisex</a></li>
          </ul>
        </div>
      </div>
    </section>
  </div>

  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2024 Copyright:
    <a class="text-reset fw-bold" href="/">bambasneakers.com</a>
  </div>

</footer>