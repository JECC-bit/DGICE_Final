function validarFormulario() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const usernameRegex = /^(20\d{6}|\d{4})$/;
    const passwordRegex = /^[a-zA-Z0-9]+$/;
    let isValid = true;
    
    if (!usernameRegex.test(username)) {
        document.getElementById('username').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('username').classList.remove('is-invalid');
    }
    
    if (!passwordRegex.test(password)) {
        document.getElementById('password').classList.add('is-invalid');
        document.getElementById('password-feedback').textContent = 'La contraseña solo puede contener letras y números.';
        isValid = false;
    } else {
        document.getElementById('password').classList.remove('is-invalid');
    }
    
    return isValid;
}