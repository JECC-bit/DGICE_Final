<?php
session_start();
require 'scripts/db.php';

$cod = $_GET['cod'] ?? null;

// Nos aseguramos que solo los administradores puedan acceder a esta página
if ($_SESSION['rol'] !== 'Administrador') {
    header("Location: home_bootcamp.html");
    exit();
}

if ($cod) {
    // Obtener el ID del bootcamp usando el código
    $stmt = $pdo->prepare('SELECT Id_bootcamp FROM bootcamp WHERE Codigo = ?');
    $stmt->execute([$cod]);
    $bootcamp = $stmt->fetch();

    if ($bootcamp) {
        $id_bootcamp = $bootcamp['Id_bootcamp'];

        // Contar la cantidad de participantes del bootcamp
        $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM cuenta c JOIN asignacion_cuenta ac ON c.Id_cuenta = ac.Id_cuenta WHERE ac.Id_bootcamp = ? AND c.Rol = "Usuario"');
        $stmt->execute([$id_bootcamp]);
        $total_participantes = $stmt->fetch()['total'];

        // Verificar si los usuarios ya tienen equipo asignado
        $stmt = $pdo->prepare('SELECT e.No_equipo, n.Nombres, n.Apellido_paterno, n.Apellido_materno, c.No_cuenta, c.Correo, f.Facultad 
                               FROM asignacion_equipo ae
                               JOIN asignacion_cuenta ac ON ae.Id_cuenta_bootcamp = ac.Id_cuenta_bootcamp
                               JOIN equipo e ON ae.Id_equipo = e.Id_equipo
                               JOIN cuenta c ON ac.Id_cuenta = c.Id_cuenta
                               JOIN usuario u ON c.No_cuenta = u.No_cuenta
                               JOIN nombre n ON u.Id_nombre = n.Id_nombre
                               JOIN facultad f ON u.Id_facultad = f.Id_facultad
                               WHERE ac.Id_bootcamp = ?');
        $stmt->execute([$id_bootcamp]);
        $equipos = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
        $hayEquiposAsignados = !empty($equipos);
    } else {
        $total_participantes = 0;
        $equipos = [];
    }
} else {
    $total_participantes = 0;
    $equipos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar equipos</title>
    <link rel="icon" href="img/UDC_logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- Añadido jQuery -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/crearBootcampCSS.css">
    <link rel="stylesheet" href="css/generarEquipos.css">
    <link rel="stylesheet" href="css/actividades.css">
    <link rel="stylesheet" href="css/actividadesAdmin.css">
</head>
<body>
            <!-- Barra de navegacion -->
            <nav class="navbar bg-body-tertiary fixed-top navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#barraLateralRetraible" aria-controls="barraLateralRetraible" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a href="Principal.php" class="navbar-brand"><img src="img/logo.png" alt="Logo"></a>
                <a class="nav-link" href="#"><span class="bi bi-person-circle" title="Perfil"></span></a>
            </div>
        </nav>
        
        <!--? Contenido barra lateral retraible -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="barraLateralRetraible" aria-labelledby="barraLateralLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="barraLateralLabel">Acciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item mb-2">
                        <a class="nav-link mb-2 ms-2 mt-2" href="bootcamps_index.php"> 
                            <i class="bi bi-arrow-return-left"></i> 
                            <span>Regresar a la vista bootcamps</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <?php echo '<a class="nav-link mb-2 ms-4 mt-2" href="actividades_admin.php?cod='.$_GET['cod'].'">';?>
                            <i class="bi bi-bookmark-check me-2"></i>
                            <span>Actividades asignadas</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link mb-2 ms-2 mt-2" href="#"> 
                            <i class="bi bi-graph-up me-2"></i> 
                            <span>Progresos de los participantes</span>
                        </a>
                    </li>
                    <li class="nav-item active mb-2 shadow rounded">
                        <?php echo '<a class="nav-link active ms-2 mt-2" aria-current="page" href="Equipos.php?cod='.$_GET['cod'].'">';?>
                            <i class="bi bi-people-fill me-2"></i>
                            <span>Generar o ver equipos</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link mb-2 ms-2 mt-2" href="#">
                            <i class="bi bi-diagram-3-fill me-2"></i>
                            <span>Administradores</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="actividades_admin.php?cerrar=yes">
                            <i class="bi bi-x-circle-fill"></i>
                            <span>Cerrar sesión</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <main class="container-fluid" id="main">
            <div class="row">
                <!--? Contenido barra lateral fija -->
                <div class="col-2 bg-body-tertiary d-none d-lg-block mt-2" id="barraLateralFija">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link mb-2 ms-2 mt-2" href="bootcamps_index.php"> 
                                <i class="bi bi-arrow-return-left"></i> 
                                <span>Regresar a la vista bootcamps</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <?php echo '<a class="nav-link" href="actividades_admin.php?cod='.$_GET['cod'].'">';?>
                                <i class="bi bi-bookmark-check me-2"></i>
                                <span>Actividades asignadas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-graph-up me-2"> </i>
                                <span>Progresos de los participantes</span>
                            </a>
                        </li>
                        <li class="nav-item active shadow rounded">
                            <?php echo '<a class="nav-link active" aria-current="page" href="Equipos.php?cod='.$_GET['cod'].'">';?>
                                <i class="bi bi-people-fill me-2"></i>
                                <span>Generar o ver equipos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-diagram-3-fill me-2"></i>
                                <span>Administradores</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="actividades_admin.php?cerrar=yes">
                                <i class="bi bi-x-circle-fill"></i>
                                <span>Cerrar sesión</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Contenido principal -->
                <div class="col-lg-10 offset-lg-2">
                    <h2 class="text-center mt-3">Generar equipos</h2>
                    <div class="table-responsive">
                        <p id="total-participantes" class="mt-4">Total de participantes: <span><?php echo $total_participantes; ?></span></p>
                        <div class="select-container"> <!--checklist-->
                            <label for="limite-equipo">Límite de equipo:</label>
                            <select class="form-select w-auto d-inline-block" id="limite-equipo">
                                <option value="" selected></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="btn-container">
                            <button class="btn btn-primary mt-3" id="generar-equipos-btn">Generar equipo</button> <!--  boton de generar -->
                        </div>

                        <table class="table table-bordered mt-3">
                            <thead class="thead-light">
                                <tr>
                                    <th>Equipos</th>
                                    <th>Nombre de los integrantes</th>
                                    <th>Número de cuenta</th>
                                    <th>Correo</th>
                                    <th>Facultad/Área</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($equipos)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No hay equipos asignados. Presiona "Generar equipos".</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($equipos as $equipo => $integrantes): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($equipo); ?></td>
                                            <td>
                                                <ul>
                                                    <?php foreach ($integrantes as $integrante): ?>
                                                        <li><?php echo htmlspecialchars($integrante['Nombres'] . ' ' . $integrante['Apellido_paterno'] . ' ' . $integrante['Apellido_materno']); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul>
                                                    <?php foreach ($integrantes as $integrante): ?>
                                                        <li><?php echo htmlspecialchars($integrante['No_cuenta']); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul>
                                                    <?php foreach ($integrantes as $integrante): ?>
                                                        <li><?php echo htmlspecialchars($integrante['Correo']); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul>
                                                    <?php foreach ($integrantes as $integrante): ?>
                                                        <li><?php echo htmlspecialchars($integrante['Facultad']); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <script>
            var hayEquiposAsignados = <?php echo json_encode($hayEquiposAsignados); ?>;
        </script>

<script>
    $(document).ready(function() {
        var $generarEquiposBtn = $('#generar-equipos-btn');
        
        // Deshabilitar el botón si ya hay equipos asignados
        if (hayEquiposAsignados) {
            $generarEquiposBtn.prop('disabled', true).addClass('btn-secondary').removeClass('btn-primary');
            $generarEquiposBtn.attr('title', 'Los equipos ya han sido generados');
        }

        $generarEquiposBtn.click(function() {
            var limiteEquipo = $('#limite-equipo').val();
            if (!limiteEquipo) {
                alert('Por favor selecciona un límite de equipo.');
                return;
            }

            $.post('scripts/generar_equipos.php', { cod: '<?php echo $cod; ?>', limiteEquipo: limiteEquipo }, function(response) {
                if (response.success) {
                    alert('Equipos generados exitosamente.');
                    location.reload();
                } else {
                    alert('Hubo un error al generar los equipos.');
                }
            }, 'json');
        });
    });
</script>
</body>

</html>
