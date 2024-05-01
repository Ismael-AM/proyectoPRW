document.addEventListener('DOMContentLoaded', () => {
    const btnAñadir = document.querySelector('#btnAñadir');

    btnAñadir.addEventListener('click', async (event) => {
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

            const idZapatilla = btnAñadir.getAttribute('data-id');
            const nombreZap = document.getElementsByClassName('tituloProducto')[0].textContent;
            const talla = document.querySelector('#talla').value;
            añadirAlCarrito(idZapatilla, talla, nombreZap);

        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ha ocurrido un error al intentar verificar la sesión",
            });
        }
    });
    const range = document.querySelector('#range');

    range.addEventListener('input', (event) => {
        const value = event.target.value.padStart(2, '0');
        
        const img = document.querySelector('#img');
        let src = img.getAttribute('src');
        const fileName = src.substring(src.lastIndexOf('/') + 1, src.lastIndexOf('.'));
    
        const regex = /img(\d+)/;
        const match = fileName.match(regex);
        if (match && match[1]) {
            const currentNumber = match[1];
            const newFileName = fileName.replace(currentNumber, value);
            src = src.replace(fileName, newFileName);
            img.setAttribute('src', src);
        }
    }); 
});

function añadirAlCarrito(idZapatilla, talla, nombreZap) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/añadirAlCarrito/${idZapatilla}?talla=${talla}`, {
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
                icon: "success",
                title: nombreZap + " (" + talla + ")",
                text: "añadida correctamente al carrito",
                showConfirmButton: false,
                timer: 1000
              });
        }else if(!data.token){
            Swal.fire({
                icon: "error",
                title: "Token expirado",
                text: "Por favor, vuelva a iniciar sesion",
              });
        }
        setTimeout(function () {
            window.location.href = '/logout';
        })
    })
    .catch(error => {
        console.error('Error:', error);
    });
}''