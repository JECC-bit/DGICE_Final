<?php
require 'db.php';

$response = [];

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
    if ($tipo == 'campus') {
        $query = "SELECT Id_campus, Campus FROM campus";
    } elseif ($tipo == 'facultades' && isset($_GET['id_campus'])) {
        $id_campus = $_GET['id_campus'];
        $query = "SELECT Id_facultad, Facultad FROM facultad WHERE Id_campus = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_campus);
    } elseif ($tipo == 'carreras' && isset($_GET['id_facultad'])) {
        $id_facultad = $_GET['id_facultad'];
        $query = "SELECT Id_carrera, Carrera FROM carrera WHERE Id_facultad = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_facultad);
    }

    if (isset($stmt)) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        $stmt->close();
    } else {
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    }
}

echo json_encode($response);
?>
