<?php
require 'db.php';

$cod = $_POST['cod'];
$limiteEquipo = $_POST['limiteEquipo'];

if ($cod && $limiteEquipo) {
    // Obtener el ID del bootcamp usando el código
    $stmt = $pdo->prepare('SELECT Id_bootcamp FROM bootcamp WHERE Codigo = ?');
    $stmt->execute([$cod]);
    $bootcamp = $stmt->fetch();

    if ($bootcamp) {
        $id_bootcamp = $bootcamp['Id_bootcamp'];

        // Obtener todos los usuarios del bootcamp
        $stmt = $pdo->prepare('SELECT ac.Id_cuenta_bootcamp, c.Id_cuenta FROM cuenta c JOIN asignacion_cuenta ac ON c.Id_cuenta = ac.Id_cuenta WHERE ac.Id_bootcamp = ? AND c.Rol = "Usuario"');
        $stmt->execute([$id_bootcamp]);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcular el número de equipos necesarios
        $totalUsuarios = count($usuarios);
        $numeroEquipos = max(1, ceil($totalUsuarios / $limiteEquipo)); // Asegura al menos 1 equipo

        // Barajar los usuarios
        shuffle($usuarios);

        // Dividir usuarios en equipos
        $equipos = array_chunk($usuarios, ceil($totalUsuarios / $numeroEquipos));

        // Insertar equipos en la base de datos
        foreach ($equipos as $index => $equipo) {
            // Inserción en la tabla equipo con el campo No_equipo
            $stmt = $pdo->prepare('INSERT INTO equipo (No_equipo) VALUES (?)');
            $stmt->execute(['Equipo ' . ($index + 1)]);
            $equipoId = $pdo->lastInsertId();

            foreach ($equipo as $usuario) {
                $stmt = $pdo->prepare('INSERT INTO asignacion_equipo (Id_equipo, Id_cuenta_bootcamp) VALUES (?, ?)');
                $stmt->execute([$equipoId, $usuario['Id_cuenta_bootcamp']]);
            }
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Código de bootcamp inválido.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos insuficientes.']);
}
?>
