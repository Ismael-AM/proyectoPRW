document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const btnConfirmarPedido = document.getElementById('btnConfirmarPedido');

    btnConfirmarPedido.addEventListener('click', (event) => {
        event.preventDefault();
        Swal.fire({
            icon: "question",
            title: "Confirmar pedido",
            text: "¿Desea confirmar el pedido?",
            buttons: true,
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Confirmar",
        })
        .then((result) => {
            if (result.isConfirmed) {
                fetch(`/confirmarPedido`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }, 
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Confirmado",
                            text: "El pedido ha sido confirmado",
                        })
                        setTimeout(function () {
                            window.location.href = '/';
                        }, 2000);
                    }else if(!data.token){
                        Swal.fire({
                            icon: "error",
                            title: "Token expirado",
                            text: "Por favor, vuelva a iniciar sesion",
                        })
                        setTimeout(function () {
                            window.location.href = '/';
                        }, 500);
                    }
                })
            }
        })
    })
});