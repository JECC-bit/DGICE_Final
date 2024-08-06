<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_bootcamp = $_POST["id_bootcamp"];
    $nombreBootcamp = $_POST["nombreBootcamp"];
    $encargadoBootcamp = $_POST["encargadoBootcamp"];
    $dependenciaBootcamp = $_POST["dependenciaBootcamp"];
    $descripcionBootcamp = $_POST["descripcionBootcamp"];
    $fechaInicioBootcamp = $_POST["fechaInicioBootcamp"];
    $fechaFinBootcamp = $_POST["fechaFinBootcamp"];

    // Actualizar los datos del bootcamp
    $sql = "UPDATE bootcamp
            SET Nombre_bootcamp = ?, Fecha_inicio = ?, Fecha_cierre = ?, Descripcion = ?, Id_campus = ?
            WHERE Id_bootcamp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $nombreBootcamp, $fechaInicioBootcamp, $fechaFinBootcamp, $descripcionBootcamp, $dependenciaBootcamp, $id_bootcamp);

    if ($stmt->execute()) {
        // Actualizar el encargado en la tabla `asignacion_encargado`
        $sql_encargado = "UPDATE asignacion_encargado
                          SET Id_cuenta = ?
                          WHERE Id_bootcamp = ?";
        $stmt_encargado = $conn->prepare($sql_encargado);
        $stmt_encargado->bind_param("ii", $encargadoBootcamp, $id_bootcamp);
        $stmt_encargado->execute();
        $stmt_encargado->close();
        echo "Bootcamp actualizado exitosamente.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
