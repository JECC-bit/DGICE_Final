<?php
session_start();
include 'db.php'; // Asegúrate de que este archivo tiene la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $codigoBootcamp = $_POST["bootcamp_codg"];
    $idUsuario = $_SESSION['id_cuenta']; // Suponiendo que el ID del usuario está almacenado en la sesión

    // Verificar si el código del bootcamp existe y está activo
    $sql_verificar_bootcamp = "SELECT Id_bootcamp FROM bootcamp WHERE Codigo = ? AND Status = 'Activo'";
    if ($stmt = $conn->prepare($sql_verificar_bootcamp)) {
        $stmt->bind_param("s", $codigoBootcamp);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // El bootcamp existe, obtener su ID
            $stmt->bind_result($idBootcamp);
            $stmt->fetch();

            // Registrar al usuario en el bootcamp
            $sql_registro_bootcamp = "INSERT INTO asignacion_cuenta (Id_bootcamp, Id_cuenta) VALUES (?, ?)";
            if ($stmt_registro = $conn->prepare($sql_registro_bootcamp)) {
                $stmt_registro->bind_param("ii", $idBootcamp, $idUsuario);
                if ($stmt_registro->execute()) {
                    $_SESSION['registro_exito'] = "Te has registrado en el bootcamp exitosamente.";
                } else {
                    $_SESSION['registro_error'] = "Error al registrarte en el bootcamp. Inténtalo de nuevo.";
                }
                $stmt_registro->close();
            }
        } else {
            $_SESSION['registro_error'] = "Código de bootcamp no válido.";
        }
        $stmt->close();
    }
    header("Location: ../actividades.html");
    exit();
}

$conn->close();
?>
