<?php
session_start();
require 'scripts/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar si el usuario existe
    $stmt = $pdo->prepare('SELECT * FROM cuenta WHERE No_cuenta = ? OR Correo = ?');
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Contrasena'])) {
        // Usuario autenticado correctamente
        $_SESSION['numero_cuenta'] = $user['No_cuenta'];
        $_SESSION['rol'] = $user['Rol'];
        $_SESSION['id_cuenta'] = $user['Id_cuenta'];

        // Verificar si el usuario tiene un equipo asignado
        $stmt = $pdo->prepare('SELECT ae.Id_equipo FROM asignacion_equipo ae 
                                JOIN asignacion_cuenta ac ON ae.Id_cuenta_bootcamp = ac.Id_cuenta_bootcamp 
                                WHERE ac.Id_cuenta = ?');
        $stmt->execute([$user['Id_cuenta']]);
        $equipo = $stmt->fetch();

        if ($equipo) {
            $_SESSION['Id_equipo'] = $equipo['Id_equipo'];
        }

        // Redirigir según el rol del usuario
        if ($user['Rol'] == 'Administrador') {
            header('Location: bootcamps_index.php');
        } else {
            header('Location: Principal.php');
        }
        exit;
    } else {
        $error = 'Número de cuenta/correo o contraseña incorrectos.';
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="icon" href="img/UDC_logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style_IS.css">
    <script src="js/val_log.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <div class="container">
                <a href="index.php" class="navbar-brand d-flex justify-content-start "><img src="img/logo.png" alt="Logo"></a>
            </div>
        </nav>
    </header>
    
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="InicioSesion.php" method="POST" id="loginForm" onsubmit="return validarFormulario()">
            <div class="input-group">
                <label for="username">Número de cuenta o trabajador</label>
                <input type="text" id="username" name="username" required>
                <div class="invalid-feedback">
                    Formato inválido. Use 20XXXXXX o XXXX (X = dígito).
                </div>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
                <div class="invalid-feedback" id="password-feedback">
                    La contraseña solo puede contener letras y números.
                </div>
            </div>
            <button type="submit">ACCEDER</button>
        </form>
        <div class="additional-actions">
            <p>¿Olvidaste la contraseña? <a href="recuperar.php"> Recupérala</a></p>
            <p>¿No tienes cuenta? <a href="registro.php">Crea una</a></p>
        </div>
    </div>
</body>
</html>
