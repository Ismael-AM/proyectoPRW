<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Javascript deshabilitado</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger text-center" role="alert">
                    <h4 class="alert-heading fw-bold">JAVASCRIPT DESACTIVADO</h4>
                    <p class="mb-0">Esta página necesita de Javascript para funcionar correctamente</p>
                    <p class="mb-0">Por favor, habilite JavaScript en su navegador.</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.location.href = "/";
        });
    </script>
</body>
</html>