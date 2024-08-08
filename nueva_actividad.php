<?php
// Nos aseguramos que solo los administradores puedan acceder a esta página
session_start();
if ($_SESSION['rol'] !== 'Administrador') {
    header("Location: home_bootcamp.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'scripts/db.php';

    // Obtener los datos del formulario
    $titulo = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $video = $_POST['video'];
    $fechaPublicacion = $_POST['fechaPublicacion'];
    $fechaEntrega = $_POST['fechaEntrega'] . ' ' . $_POST['horaEntrega'];
    $orden = $_POST['orden'];
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

        // Verificar el número de actividades existentes para el bootcamp
        $sqlCountActivities = "SELECT COUNT(*) as count FROM asignacion_actividad WHERE Id_bootcamp = ?";
        $stmtCount = $conn->prepare($sqlCountActivities);
        $stmtCount->bind_param("i", $idBootcamp);
        $stmtCount->execute();
        $resultCount = $stmtCount->get_result();
        $rowCount = $resultCount->fetch_assoc();
        $activityCount = $rowCount['count'];

        // Determinar el estado de la nueva actividad
        // $status = ($activityCount == 0) ? 'pending' : 'blocked';

        // Establecemos el estado de la actividad dependiendo de la fecha de publicación y la fecha de entrega
        $fechaActual = date('Y-m-d');
        if($fechaPublicacion <= $fechaActual){
            $status = 'pending';
        } elseif($fechaPublicacion > $fechaActual){
            $status = 'blocked';
        }

        // Insertar la nueva actividad en la tabla `actividad`
        $sqlInsertActivity = "INSERT INTO actividad (Titulo, Descripcion, Video, `Status`, Created_at, Fecha_publicacion, Fecha_entrega, orden) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
        $stmtInsertActivity = $conn->prepare($sqlInsertActivity);
        $stmtInsertActivity->bind_param("ssssssi", $titulo, $descripcion, $video, $status, $fechaPublicacion, $fechaEntrega, $orden);
        $stmtInsertActivity->execute();

        $idActividad = $conn->insert_id;

        // Insertar la relación en `asignacion_actividad`
        $sqlAssignActivity = "INSERT INTO asignacion_actividad (Id_actividad, Id_bootcamp) VALUES (?, ?)";
        $stmtAssignActivity = $conn->prepare($sqlAssignActivity);
        $stmtAssignActivity->bind_param("ii", $idActividad, $idBootcamp);
        $stmtAssignActivity->execute();

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

                // Asignar el material a la actividad y bootcamp en `asignacion_material`
                $sqlAssignMaterial = "INSERT INTO asignacion_material (Id_material, Id_bootcamp, Id_actividad, Created_at) VALUES (?, ?, ?, NOW())";
                $stmtAssignMaterial = $conn->prepare($sqlAssignMaterial);
                $stmtAssignMaterial->bind_param("iii", $idMaterial, $idBootcamp, $idActividad);
                $stmtAssignMaterial->execute();
            }
        }

        echo "Actividad creada exitosamente.";
    } else {
        echo "Error: Código de bootcamp no encontrado.";
    }

    // Cerrar conexiones
    $stmtBootcamp->close();
    $stmtCount->close();
    $stmtInsertActivity->close();
    $stmtAssignActivity->close();
    $stmtInsertMaterial->close();
    $stmtAssignMaterial->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva actividad</title>
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
                <li class="nav-item mb-2">
                    <!--! Cambiar el active en la clase para indicar en la acción en la que está actualmente -->
                    <!--! El aria-current es para indicar que se encuentra en la página actual, también cambiarla según sea el caso -->
                    <a class="nav-link mb-2 ms-2 mt-2" href="actividades_admin.php?cod=<?php echo $_GET['cod'] ?>">
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
                <li class="nav-item mb-2">
                    <a class="nav-link mb-2 ms-2 mt-2" href="Equipos.php?cod=<?php echo $_GET['cod'] ?>">
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
                        <!--! Cambiar el active en la clase para indicar en la acción en la que está actualmente -->
                        <!--! El aria-current es para indicar que se encuentra en la página actual, también cambiarla según sea el caso -->
                        <a class="nav-link" href="actividades_admin.php?cod=<?php echo $_GET['cod'] ?>">
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
                    <li class="nav-item">
                        <a class="nav-link" href="Equipos.php?cod=<?php echo $_GET['cod'] ?>">
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
                            <h1 class="position-absolute top-0 start-50 translate-middle-x">Nueva actividad</h1>
                        </div>
                    </div>
                    <div class="row">
                        <form action="nueva_actividad.php?cod=<?php echo $_GET['cod']; ?>" method="POST" enctype="multipart/form-data">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre de la actividad</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="orden" class="form-label">Número de actividad</label>
                                    <select class="form-control" id="orden" name="orden" required>
                                        <?php for($i = 1;  $i <= 20; $i++){ echo '<option value="' .$i .'">' .$i. '</option>'; } ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="container-fluid" id="hora-fecha">
                                        <div class="row">
                                            <div class="mb-3">
                                                <label for="fechaPublicacion" class="form-label">Fecha de publicación</label>
                                                <input type="date" class="form-control" id="fechaPublicacion" name="fechaPublicacion" required>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label for="fechaEntrega" class="form-label">Fecha de entrega</label>
                                                <input type="date" class="form-control" id="fechaEntrega" name="fechaEntrega" required>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label for="horaEntrega" class="form-label">Hora de entrega</label>
                                                <input type="time" class="form-control" id="horaEntrega" name="horaEntrega" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="video" class="form-label">Link del video de apoyo</label>
                                    <input type="text" class="form-control" id="video" name="video">
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="drop-area">
                                                <div class="container text-center" id="contenidoInputFiles">
                                                    <div class="row justify-content-md-center">
                                                        <div class="col-12">
                                                            <i class="bi bi-cloud-arrow-up-fill"></i>
                                                        </div>
                                                        <div class="col-12">
                                                            <h4>Arrastra y suelta archivos</h4>
                                                        </div>
                                                        <div class="col-12">
                                                            <span>o</span>
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="input-file" class="btn btn-primary">Selecciona archivos</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="file" name="archivos[]" id="input-file" hidden multiple>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 container">
                                            <div class="mb-3 row" id="preview">
                                                <div class="col-12">
                                                    <h5>Archivos subidos: </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <script src="js/files.js"></script>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">Crear Actividad</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>