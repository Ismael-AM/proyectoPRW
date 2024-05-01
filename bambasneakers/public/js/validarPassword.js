document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.getElementById('contraseña'),
        confirmPasswordInput = document.getElementById('confirmar_contraseña'),
        passwordSecurityError = document.getElementById('passwordSecurityError'),
        eyeIconPassword = document.getElementById('eyeIcon-Password'),
        eyeIconConfirmPassword = document.getElementById('eyeIcon-ConfirmPassword'),
        avisoSecurity = document.querySelector('.avisoSecurity'),
        avisoMatch = document.querySelector('.avisoMatch');

    eyeIconPassword.addEventListener('mouseover', function() {
        if(passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIconPassword.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            passwordInput.type = 'password';
            eyeIconPassword.classList.replace('fa-eye', 'fa-eye-slash');
        }
    });

    eyeIconPassword.addEventListener('mouseout', function() {
        if(passwordInput.type === 'text') {
            passwordInput.type = 'password';
            eyeIconPassword.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIconPassword.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    eyeIconConfirmPassword.addEventListener('mouseover', function() {
        if(confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            eyeIconConfirmPassword.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            confirmPasswordInput.type = 'password';
            eyeIconConfirmPassword.classList.replace('fa-eye', 'fa-eye-slash');
        }
    });

    eyeIconConfirmPassword.addEventListener('mouseout', function() {
        if(confirmPasswordInput.type === 'text') {
            confirmPasswordInput.type = 'password';
            eyeIconConfirmPassword.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            confirmPasswordInput.type = 'password';
            eyeIconConfirmPassword.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    function checkPasswordMatch() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            avisoMatch.classList.add("active");
            confirmPasswordInput.style.borderColor = 'red';
            eyeIconConfirmPassword.style.color = 'red';
        } else {
            avisoMatch.classList.remove("active");
            passwordInput.style.borderColor = 'green';
            eyeIconPassword.style.color = 'green';
            confirmPasswordInput.style.borderColor = 'green';
            eyeIconConfirmPassword.style.color = 'green';
        }

        if(passwordInput.value == confirmPasswordInput.value && correo.value !== ""){
            botonEnviar.disabled = false;
        }
    }

    document.getElementById('correo').addEventListener('input', function() {
        if(passwordInput.value == confirmPasswordInput.value && correo.value !== "" && passwordInput.value !== "" && confirmPasswordInput.value !== ""){
            botonEnviar.disabled = false;
        }else{
            botonEnviar.disabled = true;
        }
    });

    document.getElementById('contraseña').addEventListener('input', function() {
        if(passwordInput.value !== "" && confirmPasswordInput.value !== ""){
            checkPasswordMatch();        
        }else{
            avisoMatch.classList.remove("active");
            eyeIconConfirmPassword.style.color = '';
        }

        if(passwordInput.value == confirmPasswordInput.value && correo.value !== ""){
            botonEnviar.disabled = false;
        }else{
            botonEnviar.disabled = true;
        }
    });

    document.getElementById('confirmar_contraseña').addEventListener('input', function() {
        if(passwordInput.value !== "" && confirmPasswordInput.value !== ""){
            checkPasswordMatch();        
        }else{
            avisoMatch.classList.remove("active");
            confirmPasswordInput.style.borderColor = '';
            eyeIconConfirmPassword.style.color = '';
        }

        if(passwordInput.value == confirmPasswordInput.value && correo.value !== ""){
            botonEnviar.disabled = false;
        }else{
            botonEnviar.disabled = true;
        }
    });

    passwordInput.addEventListener('keyup', function() {
        let val = passwordInput.value;
        const letrasMinusculas = /[a-z]/,
            letrasMayusculas = /[A-Z]/,
            numeros = /[0-9]/,
            simbolos = /[!,@,#,$,%,^,&,*,?,_,~,(,),{,},[,),-,+,=,;,:,<,>,.]/;
        
        if(val.length < 4){
            avisoSecurity.style.display = 'block';
            avisoSecurity.style.color = 'red';
            passwordSecurityError.textContent = 'La contraseña debe contener como mínimo 4 caracteres';
            passwordSecurityError.classList.remove('text-success', 'text-warning');
            passwordSecurityError.classList.add('text-danger');
            passwordInput.style.borderColor = 'red';
            eyeIconPassword.style.color = 'red';
        }
        if((letrasMinusculas.test(val) || letrasMayusculas.test(val) || numeros.test(val) || simbolos.test(val)) && val.length >= 4) {
            avisoSecurity.style.display = 'block';
            avisoSecurity.style.color = 'red';
            passwordSecurityError.textContent = 'Seguridad: baja';
            passwordSecurityError.classList.remove('text-success', 'text-warning');
            passwordSecurityError.classList.add('text-danger');
            passwordInput.style.borderColor = 'red';
            eyeIconPassword.style.color = 'red';
        } 
        if((letrasMinusculas.test(val) || letrasMayusculas.test(val)) && (numeros.test(val) || simbolos.test(val)) && val.length >= 6) {
            avisoSecurity.style.display = 'block';
            avisoSecurity.style.color = 'yellow';
            passwordSecurityError.style.display = 'block';
            passwordSecurityError.textContent = 'Seguridad: media';
            passwordSecurityError.classList.remove('text-danger', 'text-success');
            passwordSecurityError.classList.add('text-warning');
            passwordInput.style.borderColor = 'yellow';
            eyeIconPassword.style.color = 'yellow';
        } 
        if(letrasMinusculas.test(val) && letrasMayusculas.test(val) && numeros.test(val) && simbolos.test(val) && val.length >= 8) {
            avisoSecurity.style.display = 'block';
            avisoSecurity.style.color = 'green';
            passwordSecurityError.style.display = 'block';
            passwordSecurityError.textContent = 'Seguridad: alta';
            passwordSecurityError.classList.remove('text-danger', 'text-warning');
            passwordSecurityError.classList.add('text-success');
            passwordInput.style.borderColor = 'green';
            eyeIconPassword.style.color = 'green';
        } 
        if(val.length == 0) {
            avisoSecurity.style.display = 'none';
            passwordInput.style.borderColor = '';
            eyeIconPassword.style.color = '';
            confirmPasswordInput.style.borderColor = '';
        }
    });
});