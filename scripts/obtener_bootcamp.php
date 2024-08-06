<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id_bootcamp = $_GET['id'];

    // Consultar los datos del bootcamp
    $sql = "SELECT b.Id_bootcamp, b.Nombre_bootcamp, b.Fecha_inicio, b.Fecha_cierre, b.Descripcion, b.Id_campus, ae.Id_cuenta
            FROM bootcamp b
            JOIN asignacion_encargado ae ON b.Id_bootcamp = ae.Id_bootcamp
            WHERE b.Id_bootcamp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_bootcamp);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No se encontraron datos para este bootcamp.']);
    }
    
    $stmt->close();
}
$conn->close();
?>
