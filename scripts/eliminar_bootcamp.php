<?php
include 'db.php';

header('Content-Type: application/json');

// Permitir solicitudes de cualquier origen
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Manejar solicitudes de método POST y DELETE simuladas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el método DELETE se está simulando
    if (isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
        $id_bootcamp = $_POST['id'];

        if (isset($id_bootcamp)) {
            // Consultar y eliminar asignaciones relacionadas
            $sql_delete_asignaciones = "DELETE FROM asignacion_encargado WHERE Id_bootcamp = ?";
            $stmt_asignaciones = $conn->prepare($sql_delete_asignaciones);
            if ($stmt_asignaciones) {
                $stmt_asignaciones->bind_param("i", $id_bootcamp);
                if (!$stmt_asignaciones->execute()) {
                    echo json_encode(['error' => 'Error al eliminar asignaciones: ' . $stmt_asignaciones->error]);
                    exit;
                }
                $stmt_asignaciones->close();
            } else {
                echo json_encode(['error' => 'Error al preparar la consulta de eliminación de asignaciones: ' . $conn->error]);
                exit;
            }

            // Eliminar el bootcamp
            $sql = "DELETE FROM bootcamp WHERE Id_bootcamp = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $id_bootcamp);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['error' => 'Error al eliminar el bootcamp: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['error' => 'Error al preparar la consulta de eliminación del bootcamp: ' . $conn->error]);
            }
        } else {
            echo json_encode(['error' => 'ID de bootcamp no especificado.']);
        }
    } else {
        echo json_encode(['error' => 'Método DELETE no simulado correctamente.']);
    }
} else {
    echo json_encode(['error' => 'Método de solicitud no permitido.']);
}

$conn->close();
?>
