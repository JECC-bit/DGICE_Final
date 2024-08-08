<?php 
    // Verifica si existe el parámetro 'sesion' en la URL y si su valor es 'cerrar'
    if (isset($_GET['cerrar']) && $_GET['cerrar'] === 'yes') {
        // Destruye la sesión
        session_destroy();
        
        // Limpia la variable de sesión
        $_SESSION = array();
        
        // Redirige al usuario a la página de inicio o login
        header("Location: InicioSesion.php");
        exit();
    }

    include 'scripts/db.php';

    $codigoBootcamp = $_GET['cod'];

    // Obtener el ID del bootcamp usando el código
    $sqlBootcampId = "SELECT Id_bootcamp, Nombre_bootcamp FROM bootcamp WHERE Codigo = ?";
    $stmtBootcampId = $conn->prepare($sqlBootcampId);
    $stmtBootcampId->bind_param("s", $codigoBootcamp);
    $stmtBootcampId->execute();
    $resultBootcampId = $stmtBootcampId->get_result();
    $rowBootcampId = $resultBootcampId->fetch_assoc();
    $idBootcamp = $rowBootcampId['Id_bootcamp'];

    // Obtener las actividades del bootcamp en orden
    $sqlActivities = "
        SELECT a.Id_actividad, a.Titulo, a.Status, a.orden 
        FROM actividad a
        JOIN asignacion_actividad aa ON a.Id_actividad = aa.Id_actividad
        WHERE aa.Id_bootcamp = ?
        ORDER BY a.orden
    ";
    $stmtActivities = $conn->prepare($sqlActivities);
    $stmtActivities->bind_param("i", $idBootcamp);
    $stmtActivities->execute();
    $resultActivities = $stmtActivities->get_result();

    $stmtBootcampId->close();
    $stmtActivities->close();
    $conn->close();

    
    // Verificamos que el usuario esté dentro de un equipo
    session_start();
    include 'scripts/db.php';

    if ($_SESSION['rol'] == 'Usuario') {
        // Extraer el ID del equipo al que pertenece el usuario
        $sqlVerifyTeam = "SELECT ae.Id_equipo 
                        FROM asignacion_equipo as ae
                        JOIN asignacion_cuenta as ac ON ae.Id_cuenta_bootcamp = ac.Id_cuenta_bootcamp
                        WHERE ac.Id_bootcamp = ? AND ac.Id_cuenta= ?";
        $stmtVerifyTeam = $conn->prepare($sqlVerifyTeam);
        $stmtVerifyTeam->bind_param("ii", $idBootcamp, $_SESSION['id_cuenta']);
        $stmtVerifyTeam->execute();
        $resultVerifyTeam = $stmtVerifyTeam->get_result();
        $rowVerifyTeam = $resultVerifyTeam->fetch_assoc();
        $stmtVerifyTeam->close();

        // En caso de no tener un equipo asignado, redirigir a la página del bootcamp
        if ($_SESSION['rol'] == 'Usuario' && $rowVerifyTeam == null) {
            header("Location: bootcamp.php?cod=$codigoBootcamp");
            exit();
        }
    }
    else{
        header("Location: actividades_admin.php?cod=$codigoBootcamp");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades</title>
    <link rel="icon" href="img/UDC_logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/actividades.css">
    <link rel="stylesheet" href="css/crearBootcampCSS.css">

</head>
<body>
<!-- ! Barra de navegación -->
    <header>
        <nav class="navbar navbar-light bg-light fixed-top">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#barraLateralRetraible" aria-controls="barraLateralRetraible" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a href="Principal.php" class="navbar-brand"><img src="img/logo.png" alt="Logo"></a>
                <a class="nav-link" href="Principal.php?sesion=cerrar"><span class="btn btn-outline-secondary"></spa>Cerrar Sesión</a>
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
                    <li class="nav-item active mb-2 shadow rounded">
                        <!--! Cambiar el active en la clase para indicar en la acción en la que está actualmente -->
                        <!--! El aria-current es para indicar que se encuentra en la página actual, también cambiarla según sea el caso -->
                        <?php echo '<a class="nav-link active mb-2 ms-4 mt-2" aria-current="page" href="actividades.php?cod='.$_GET['cod'].'">';?>
                            <i class="bi bi-bookmark-check me-2"></i>
                            <span>Actividades asignadas</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <?php echo '<a class="nav-link mb-2 ms-2 mt-2" href="#">';?>
                            <i class="bi bi-people-fill me-2"></i>
                            <span>Mi equipo</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
<!-- ! Contenido -->
    <main class="mt-5" >
        <section class="content text-center mt-0 mt-md-2 mt-lg-2">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="mb-3">
                            <img src="img/UDC.png" alt="">
                        </div>
                        <div class="mb-3">
                            <h2 class="mb-3"><?php echo $rowBootcampId['Nombre_bootcamp'] ?></h2>
                            <h3 class="mb-3">Actividades</h3><br>
                        </div>
                        <div class="col-12 offset-md-5 content-list">
                            <div id="actividades-lista">
                                <ol>
                                    <?php while ($rowActivity = $resultActivities->fetch_assoc()): ?>
                                        <li class="<?php echo strtolower($rowActivity['Status']); ?>">
                                            <a href="<?php echo ($rowActivity['Status'] == 'pending' || $rowActivity['Status'] == 'completed') ? 'actividad.php?cod='.$_GET['cod'].'&id=' . $rowActivity['Id_actividad'] : '#'; ?>">
                                                <i class="<?php echo ($rowActivity['Status'] == 'completed') ? 'fas fa-check-circle' : (($rowActivity['Status'] == 'pending') ? 'far fa-clock' : 'fas fa-lock'); ?>"></i>
                                                <?php echo $rowActivity['orden'] . ".- " . $rowActivity['Titulo']; ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="bg-dark p-2 text-center" id="actividades-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="text-white">&copy; Derechos Reservados 2022 - 2025 Universidad de Colima</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
