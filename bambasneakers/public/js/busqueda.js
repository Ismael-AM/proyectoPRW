document.addEventListener('DOMContentLoaded', () => {
    var navbarBrand = document.querySelector(".navbar-brand");

    navbarBrand.addEventListener("mouseover", function() {
        navbarBrand.classList.replace("text-danger", "text-white");
    });

    navbarBrand.addEventListener("mouseout", function() {
        navbarBrand.classList.replace("text-white", "text-danger");
    });

    const btnBuscar = document.getElementById('btnBuscar') ? document.getElementById('btnBuscar') : null;

    if(btnBuscar){
        btnBuscar.addEventListener('click', (event) => {
            event.preventDefault();
            const inputBusqueda = document.getElementById('inputBusqueda');
    
            try {
                if (inputBusqueda.value === "") {
                    Swal.fire({
                        icon: "question",
                        title: "Búsqueda vacía",
                        text: "¿Desea buscar todos los sneakers?",
                        buttons: true,
                        showCancelButton: true,
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Confirmar",
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/sneakers';
                        }
                    });
                }else{
                    const searchTerm = encodeURIComponent(inputBusqueda.value);
                    window.location.href = '/sneakers/search?param=' + searchTerm;
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Error en la petición",
                })
            }
        });
    };

    var container = $('#carouselContainer');
    var isDragging = false;
    var startX, scrollLeft;

    container.on('mousedown touchstart', function(e) {
        isDragging = true;
        startX = (e.type === 'mousedown') ? e.pageX - container.offset().left : e.originalEvent.touches[0].pageX - container.offset().left;
        scrollLeft = container.scrollLeft();
    }).on('mouseup touchend', function() {
        isDragging = false;
    }).on('mouseleave touchleave', function() {
        isDragging = false;
    }).on('mousemove touchmove', function(e) {
        if (isDragging) {
            var mousePos = (e.type === 'mousemove') ? e.pageX - container.offset().left : e.originalEvent.touches[0].pageX - container.offset().left;
            var distance = mousePos - startX;
            container.scrollLeft(scrollLeft - distance);
        }
    });
});
