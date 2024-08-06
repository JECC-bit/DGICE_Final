<!-- Buscar actividades creadas en la bd -->
<?php
include 'scripts/db.php';

$codigoBootcamp = $_GET['cod']; // Obtener el código del bootcamp desde la URL

// Obtener el Id_bootcamp a partir del código
$sqlBootcamp = "SELECT Id_bootcamp FROM bootcamp WHERE Codigo = ?";
$stmtBootcamp = $conn->prepare($sqlBootcamp);
$stmtBootcamp->bind_param("s", $codigoBootcamp);
$stmtBootcamp->execute();
$resultBootcamp = $stmtBootcamp->get_result();

if ($resultBootcamp->num_rows > 0) {
    $rowBootcamp = $resultBootcamp->fetch_assoc();
    $idBootcamp = $rowBootcamp['Id_bootcamp'];

    // Obtener las actividades del bootcamp
    $sqlActivities = "SELECT a.Id_actividad, a.Titulo FROM actividad a
                      INNER JOIN asignacion_actividad aa ON a.Id_actividad = aa.Id_actividad
                      WHERE aa.Id_bootcamp = ?
                      ORDER BY a.orden ASC";
    $stmtActivities = $conn->prepare($sqlActivities);
    $stmtActivities->bind_param("i", $idBootcamp);
    $stmtActivities->execute();
    $resultActivities = $stmtActivities->get_result();
}
?>

<!-- Actualizar los datos del bootcamp seleccionado -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'scrips/db.php';

    // Obtener los datos del formulario
    $idActividad = $_POST['actividad'];
    $titulo = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $fechaEntrega = $_POST['fechaEntrega'] . ' ' . $_POST['horaEntrega'];

    // Actualizar la actividad en la base de datos
    $sqlUpdateActivity = "UPDATE actividad SET Titulo = ?, Descripcion = ?, Fecha_entrega = ? WHERE Id_actividad = ?";
    $stmtUpdateActivity = $conn->prepare($sqlUpdateActivity);
    $stmtUpdateActivity->bind_param("sssi", $titulo, $descripcion, $fechaEntrega, $idActividad);
    $stmtUpdateActivity->execute();

    // Manejo de archivos subidos
    $uploadDir = 'material_bootcamps/';
    foreach ($_FILES['archivos']['tmp_name'] as $key => $tmpName) {
        $fileName = basename($_FILES['archivos']['name'][$key]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $filePath)) {
            // Insertar el material en la tabla `material`
            $sqlInsertMaterial = "INSERT INTO material (Material) VALUES (?)";
            $stmtInsertMaterial = $conn->prepare($sqlInsertMaterial);
            $stmtInsertMaterial->bind_param("s", $filePath);
            $stmtInsertMaterial->execute();

            $idMaterial = $conn->insert_id;

            // Asignar el material a la actividad
            $sqlAssignMaterial = "INSERT INTO asignacion_material (Id_material, Id_actividad) VALUES (?, ?)";
            $stmtAssignMaterial = $conn->prepare($sqlAssignMaterial);
            $stmtAssignMaterial->bind_param("ii", $idMaterial, $idActividad);
            $stmtAssignMaterial->execute();
        }
    }

    $stmtUpdateActivity->close();
    $conn->close();

    // Redirigir a la página de actividades
    header("Location: actividades.php?cod=" . $_GET['cod']);
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar actividad</title>
    <link rel="icon" href="img/UDC_logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/crearBootcampCSS.css">
    <link rel="stylesheet" href="css/actividades.css">
    <link rel="stylesheet" href="css/actividadesAdmin.css">
    <link rel="stylesheet" href="css/file.css">
</head>
<body>
    <!--? Barra de navegación -->
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
                <li class="nav-item active mb-2 shadow rounded">
                    <!--! Cambiar el active en la clase para indicar en la acción en la que está actualmente -->
                    <!--! El aria-current es para indicar que se encuentra en la página actual, también cambiarla según sea el caso -->
                    <a class="nav-link active mb-2 ms-4 mt-2" aria-current="page" href="actividades_admin.php?cod=<?php echo $_GET['cod'] ?>">
                        <i class="bi bi-bullseye me-2"></i>
                        <span>Actividades asignadas</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link mb-2 ms-2 mt-2" href="#"> 
                        <i class="bi bi-graph-up me-2"></i> 
                        <span>Progresos de los participantes</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link mb-2 ms-2 mt-2" href="Equipos.php?cod=<?php echo $_GET['cod'] ?>">
                        <i class="bi bi-people-fill me-2"></i>
                        <span>Equipos</span>
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
                    <li class="nav-item active shadow rounded">
                        <!--! Cambiar el active en la clase para indicar en la acción en la que está actualmente -->
                        <!--! El aria-current es para indicar que se encuentra en la página actual, también cambiarla según sea el caso -->
                        <a class="nav-link active" aria-current="page" href="actividades_admin.php?cod=<?php echo $_GET['cod'] ?>">
                            <i class="bi bi-bullseye me-2"></i>
                            <span>Actividades asignadas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-graph-up me-2"> </i>
                            <span>Progresos de los participantes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Equipos.php?cod=<?php echo $_GET['cod'] ?>">
                            <i class="bi bi-people-fill me-2"></i>
                            <span>Equipos</span>
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
            <!--? Contenido general -->
            <div class="col-9 col-lx-10" id="contenido">
                <div class="container-fluid" id="formulario">
                    <div class="row position-relative mb-4" id="upper-part">
                        <div class="col-6">
                            <a href="actividades_admin.php?cod=<?php echo $_GET['cod'] ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-3"></i>
                                <span>Regresar</span>
                            </a>
                        </div>
                        <div class="col-12 mt-4" id="title">
                            <h1 class="position-absolute top-0 start-50 translate-middle-x">Editar actividades</h1>
                        </div>
                    </div>
                    <div class="row">
                        <form action="editar_actividad.php?cod=<?php echo $_GET['cod']; ?>" method="POST" enctype="multipart/form-data">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Selecciona la actividad</label>
                                    <select name="actividad" id="actividad" class="form-control" required onchange="cargarDatosActividad(this.value)">
                                        <?php while ($rowActivity = $resultActivities->fetch_assoc()): ?>
                                            <option value="<?php echo $rowActivity['Id_actividad']; ?>"><?php echo $rowActivity['Titulo']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div id="form-datos-actividad">
                                    <!-- Los datos de la actividad seleccionada se cargarán aquí -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        function cargarDatosActividad(idActividad) {
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', 'scripts/obtener_datos_actividad.php?id=' + idActividad, true);
                            xhr.onload = function() {
                                if (this.status == 200) {
                                    document.getElementById('form-datos-actividad').innerHTML = this.responseText;
                                }
                            };
                            xhr.send();
                        }
                    </script>
                </div>
            </div>
        </div>
    </main>
</body>
</html>