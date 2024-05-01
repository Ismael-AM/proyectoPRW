document.addEventListener('DOMContentLoaded', () => {
    const btnEliminar = document.querySelectorAll('.btnEliminar');
    const vaciarCarrito = document.getElementById('vaciarCarrito');
    const inputsCantidad = document.querySelectorAll('input[type="number"].form-control-md');
    const subtotalElement = document.getElementById('subtotal');
    let timeout = null;

    function actualizarCarritoDespuesDeTiempo(idZapatilla, talla, cantidad) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            actualizarCarrito(idZapatilla, talla, cantidad);
        }, 500);
    }

    inputsCantidad.forEach(input => {
        input.addEventListener('input', () => {
            let subtotal = 0;
            inputsCantidad.forEach(input => {
                const fila = input.closest('tr'); 
                const cantidad = parseInt(input.value);
                const stockProducto = parseInt(input.getAttribute('max'));
                const precioUnitario = parseFloat(fila.querySelector('#precioUnidad').innerText.replace('€', '').trim());
                var precioTotal = 0;
                var pvpTotal = 0;
                if(cantidad > stockProducto) {
                    input.value = stockProducto;
                    precioTotal = precioUnitario * stockProducto;
                }else if(cantidad > 0) {
                    precioTotal = precioUnitario * cantidad;
                }else{
                    precioTotal = precioUnitario * 1;

                }
                const pvpUnidadElement = fila.querySelector('#pvpUnidad');
                let pvp = 0;
                if (pvpUnidadElement) {
                    pvp = parseFloat(pvpUnidadElement.innerText.replace('€', '').trim());
                }
                if (pvp > 0) {
                    if(cantidad > stockProducto) {
                        input.value = stockProducto;
                        pvpTotal = pvp * stockProducto;
                    }else if(cantidad > 0) {
                        pvpTotal = pvp * cantidad;
                    }else{
                        pvpTotal = pvp * 1;
                    }
                    fila.querySelector('#pvpTotal').innerHTML = `<del>${pvpTotal.toFixed(2)} €</del>`;
                }
                fila.querySelector('#precioTotal').innerText = `${precioTotal.toFixed(2)} €`;
                subtotal += precioTotal;
            });
            subtotalElement.innerText = `${subtotal.toFixed(2)} €`;

            if(parseInt(input.value) <= 0) {
                input.value = 1;
            }
    
            const totalPares = document.querySelector('.total-pares');
            let totalParesValue = 0;
            inputsCantidad.forEach(input => {
                totalParesValue += parseInt(input.value);
            });
            totalPares.innerText = totalParesValue;

            const fila = input.closest('tr');
            const idZapatilla = fila.getAttribute('data-id');
            const idTalla = fila.getAttribute('data-talla');
            actualizarCarritoDespuesDeTiempo(idZapatilla, idTalla, input.value);
        });
    });

    btnEliminar.forEach(btnEliminar => {
        btnEliminar.addEventListener('click', async (event) => {
            event.preventDefault();
    
            try {
                const response = await fetch('/estado-sesion', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
    
                if (!response.ok) {
                    throw new Error('Error en la solicitud');
                }
    
                const data = await response.json();
    
                if (!data.iniciada) {
                    Swal.fire({
                        icon: "error",
                        title: "Sesión no iniciada",
                        text: "Por favor, inicie sesión",
                    });
                    return;
                }
    
                const idZapatilla = btnEliminar.getAttribute('data-id');
                const idTalla = btnEliminar.getAttribute('data-talla');
    
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Se eliminarán todos los pares de esta zapatilla del carrito",
                    icon: "question",
                    buttons: true,
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "No, cancelar",
                    dangerMode: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        eliminarDelCarrito(idZapatilla, idTalla);
                    }
                });
                
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ha ocurrido un error al intentar verificar la sesión",
                });
            }
        });
    });
    
    vaciarCarrito.addEventListener('click', () => {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se eliminarán todas tus zapatillas del carrito",
            icon: "warning",
            buttons: true,
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "No, cancelar",
            dangerMode: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/vaciarCarrito';
            }
        });
    })
});

function actualizarCarrito(idZapatilla, talla, cantidad) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/actualizarCarrito/${idZapatilla}?talla=${talla}&cantidad=${cantidad}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ idZapatilla: idZapatilla, talla: talla, cantidad: cantidad })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
        }else if(!data.success){
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Error al actualizar la zapatilla",
            });
            setTimeout(function () {
                window.location.href = '/';
            }, 500);
        }
    })
}

function eliminarDelCarrito(idZapatilla, talla) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/eliminarDelCarrito/${idZapatilla}?talla=${talla}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ idZapatilla: idZapatilla, talla: talla })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            Swal.fire({
                position: "top-center",
                icon: "info",
                title: "Zapatilla eliminada del carrito",
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function () {
                window.location.reload();
            }, 1500);
        }else if(!data.success){
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Error al eliminar la zapatilla",
            });
        }else if(!data.token){
            Swal.fire({
                icon: "error",
                title: "Token expirado",
                text: "Por favor, vuelva a iniciar sesión",
            });
            setTimeout(function () {
                window.location.href = '/';
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}