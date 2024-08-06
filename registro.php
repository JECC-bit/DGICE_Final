<?php
session_start();
require 'scripts/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombres = $_POST['nombres'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo = $_POST['correo'];
    $numero_cuenta = $_POST['numero_cuenta'];
    $id_campus = $_POST['campus'];
    $id_facultad = $_POST['facultad'];  
    $id_carrera = $_POST['carrera'];
    $semestre = $_POST['semestre'];
    $grupo = $_POST['grupo'];    
    $contrasena = $_POST['contrasena'];
    $repetir_contrasena = $_POST['repetir_contrasena'];
    $rol = 'Usuario';  // Ajustar según corresponda

    // Validar contraseñas
    if ($contrasena !== $repetir_contrasena) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        try {
            // Verificar si el correo o el número de cuenta ya están registrados
            $stmt = $pdo->prepare('SELECT * FROM cuenta WHERE Correo = ? OR No_cuenta = ?');
            $stmt->execute([$correo, $numero_cuenta]);
            if ($stmt->fetch()) {
                $error = 'El correo o el número de cuenta ya están registrados.';
            } else {
                // Iniciar transacción
                $pdo->beginTransaction();

                // Insertar en la tabla nombre
                $stmt = $pdo->prepare('INSERT INTO nombre (Nombres, Apellido_paterno, Apellido_materno) VALUES (?, ?, ?)');
                $stmt->execute([$nombres, $apellido_paterno, $apellido_materno]);
                $id_nombre = $pdo->lastInsertId();

                // Insertar en la tabla usuario
                $stmt = $pdo->prepare('INSERT INTO usuario (No_cuenta, Id_nombre, Id_campus, Id_facultad, Id_carrera, Semestre, Grupo) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$numero_cuenta, $id_nombre, $id_campus, $id_facultad, $id_carrera, $semestre, $grupo]);

                // Hash de la contraseña
                $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

                // Insertar en la tabla cuenta
                $stmt = $pdo->prepare('INSERT INTO cuenta (No_cuenta, Correo, Contrasena, Rol) VALUES (?, ?, ?, ?)');
                $stmt->execute([$numero_cuenta, $correo, $hashed_password, $rol]);

                // Confirmar transacción
                $pdo->commit();

                // // Iniciar sesión
                // $_SESSION['numero_cuenta'] = $numero_cuenta;
                // $_SESSION['rol'] = $rol;

                // Redirigir al usuario a la página principal
                header('Location: InicioSesion.php');
                exit;
            }
        } catch (Exception $e) {
            // Revertir transacción si hay un error
            $pdo->rollBack();
            $error = "Error en el registro: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="icon" href="img/UDC_logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style_IS.css">
    <link rel="stylesheet" href="css/retos.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a href="index.php" class="navbar-brand"><img src="img/logo.png" alt="Logo"></a>
        </div>
    </nav>

    <div class="registro-container">
        <h2>¡Regístrate!</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="registro.php" method="POST" onsubmit="return validarFormulario()">
            <div class="input-group">
                <label for="nombres">Nombres: <span class="obligatorio">*</span></label>
                <input type="text" id="nombres" name="nombres" required>
            </div>
            <div class="input-group">
                <label for="apellido_paterno">Apellido Paterno: <span class="obligatorio">*</span></label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" required>
            </div>
            <div class="input-group">
                <label for="apellido_materno">Apellido Materno: <span class="obligatorio">*</span></label>
                <input type="text" id="apellido_materno" name="apellido_materno" required>
            </div>
            <div class="input-group">
                <label for="correo">Correo Universitario: <span class="obligatorio">*</span></label>
                <input type="email" id="correo" name="correo" required>
                <div class="invalid-feedback">Por favor, introduce un correo universitario válido (usuario@ucol.mx).</div>
            </div>
            <div class="input-group">
                <label for="numero_cuenta">Número de Cuenta: <span class="obligatorio">*</span></label>
                <input type="text" id="numero_cuenta" name="numero_cuenta" required>
                <div class="invalid-feedback">Por favor, introduce un número de cuenta válido.</div>
            </div>
            <div class="input-group">
                <label for="campus">Selecciona tu campus: <span class="obligatorio">*</span></label>
                <select name="campus" id="campus" class="selec-fac" required>
                    <option value="">---</option>
                    <!-- Las opciones de campus serán cargadas desde la base de datos -->
                </select>
            </div>
            <div class="input-group">
                <label for="facultad">Selecciona tu facultad: <span class="obligatorio">*</span></label>
                <select name="facultad" id="facultad" class="selec-fac" data-campus-required="true" required>
                    <option value="">---</option>
                    <!-- Las opciones de facultades serán cargadas dinámicamente -->
                </select>
            </div>
            <div class="input-group">
                <label for="carrera">Selecciona tu carrera: <span class="obligatorio">*</span></label>
                <select name="carrera" id="carrera" class="selec-fac" data-facultad-required="true" required>
                    <option value="">---</option>
                    <!-- Las opciones de carreras serán cargadas dinámicamente -->
                </select>
            </div>
            <div class="input-group">
                <label for="semestre">Selecciona tu semestre: </label>
                <select name="semestre" id="semestre" class="selec-fac" >
                    <option value="">---</option>
                    <!-- Las opciones de carreras serán cargadas dinámicamente -->
                </select>
            </div>
            <div class="input-group">
                <label for="grupo">Selecciona tu grupo: </label>
                <select name="grupo" id="grupo" class="selec-fac" >
                    <option value="">---</option>
                    <!-- Las opciones de carreras serán cargadas dinámicamente -->
                </select>
            </div>
            <div class="input-group">
                <label for="contrasena">Contraseña: <span class="obligatorio">*</span></label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <div class="input-group mb-3">
                <label for="repetir_contrasena" class="form-label">Repetir Contraseña: <span class="obligatorio">*</span></label>
                <input type="password" id="repetir_contrasena" name="repetir_contrasena" required>
                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
            </div>
            <button type="submit" class="btn btn-primary">ACCEDER</button>
        </form>
        <div class="additional-actions">
            <p class="cuenta">¿Ya tienes una cuenta? <a href="InicioSesion.php">Iniciar sesión</a></p>
        </div>
    </div>
    <script src="js/facultades.js"></script>
    <script src="js/validacion_registro.js"></script>
</body>
</html>
