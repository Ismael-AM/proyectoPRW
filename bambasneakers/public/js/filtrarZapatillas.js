function filtrarZapatillas() {
    var selectedMarca = document.getElementById('marca').value;
    if (selectedMarca) {
        window.location.href = '/sneakers/' + encodeURIComponent(selectedMarca);
    } else if(selectedMarca === '') {
        window.location.href = '/sneakers';
    }
}