function validarFormulario() {
    const correo = document.getElementById('correo');
    const numeroCuenta = document.getElementById('numero_cuenta');

    const correoValido = /^[a-zA-Z0-9._%+-]+@ucol\.mx$/;
    const numeroCuentaValido = /^(20\d{6}|\d{4})$/;

    const contrasena = document.getElementById('contrasena');
    const repetirContrasena = document.getElementById('repetir_contrasena');

    let esValido = true;

    if (!correoValido.test(correo.value)) {
        correo.classList.add('is-invalid');
        esValido = false;
    } else {
        correo.classList.remove('is-invalid');
    }

    if (!numeroCuentaValido.test(numeroCuenta.value)) {
        numeroCuenta.classList.add('is-invalid');
        esValido = false;
        console.log("Valor del número de cuenta:", numeroCuenta.value);
    console.log("¿Es válido?", numeroCuentaValido.test(numeroCuenta.value));
    } else {
        numeroCuenta.classList.remove('is-invalid');
    }
    if (contrasena.value !== repetirContrasena.value) {
        repetirContrasena.classList.add('is-invalid');
        esValido = false;
    } else {
        repetirContrasena.classList.remove('is-invalid');
    }

    return esValido;
}