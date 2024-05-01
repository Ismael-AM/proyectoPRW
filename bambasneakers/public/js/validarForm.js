$(document).ready(function() {
    $(document).on("keyup", "#form input", function(){
        var correo = $("#correo").val();
        var contraseña = $("#contraseña").val();
        $("#btnEnviar").prop("disabled", !(correo !== "" && contraseña !== ""));
    });
});