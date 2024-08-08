<?php
    // Verificamos que haya una sesión activa
    session_start();
    if (!isset($_SESSION['id_cuenta'])) {
        header("Location: InicioSesion.php");
        exit();
    }

    // Verificamos si el usuario quiere cerrar sesión
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
    $sqlBootcampId = "SELECT * FROM bootcamp WHERE Codigo = ?";
    $stmtBootcampId = $conn->prepare($sqlBootcampId);
    $stmtBootcampId->bind_param("s", $codigoBootcamp);
    $stmtBootcampId->execute();
    $resultBootcampId = $stmtBootcampId->get_result();
    $rowBootcamp = $resultBootcampId->fetch_assoc();
    $idBootcamp = $rowBootcamp['Id_bootcamp'];
    
    if ($_SESSION['rol'] === 'Usuario'){
        // Verificamos que el usuario esté inscrito en el bootcamp
        $sqlVerifyUser = "SELECT * FROM asignacion_cuenta WHERE Id_cuenta = ? AND Id_bootcamp = ?";
        $stmtVerifyUser = $conn->prepare($sqlVerifyUser);
        $stmtVerifyUser->bind_param("ii", $_SESSION['id_cuenta'], $idBootcamp);
        $stmtVerifyUser->execute();
        $resultVerifyUser = $stmtVerifyUser->get_result();
        $rowVerifyUser = $resultVerifyUser->fetch_assoc();
        $stmtVerifyUser->close();

        // En caso de no estar inscrito retorna a la página principal
        if (!$rowVerifyUser) {
            header("Location: Principal.php");
            exit();
        }
    }

    //TODO Extraer el material del bootcamp
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo'<title>'.$rowBootcamp['Nombre_bootcamp'].'</title>' ?>
    <link rel="icon" href="img/UDC_logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/actividades.css">
    <script src="js/actividad.js"></script>

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
                <a class="nav-link" href="actividad.php?cerrar=yes"><span class="btn btn-outline-secondary"></spa>Cerrar Sesión</a>
            </div>
        </nav>
        <!--? Contenido barra lateral retraible -->
        <?php
            if ($_SESSION['rol'] == 'Usuario'){
                echo '<div class="offcanvas offcanvas-start" tabindex="-1" id="barraLateralRetraible" aria-labelledby="barraLateralLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="barraLateralLabel">Acciones</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <li class="nav-item mb-2">
                                    <!--! Cambiar el active en la clase para indicar en la acción en la que está actualmente -->
                                    <!--! El aria-current es para indicar que se encuentra en la página actual, también cambiarla según sea el caso -->
                                    <a class="nav-link mb-2 ms-2 mt-2" href="actividades.php?cod='.$_GET['cod'].'">
                                        <i class="bi bi-bookmark-check me-2"></i>
                                        <span>Actividades asignadas</span>
                                    </a>
                                </li>
                                <li class="nav-item mb-2">
                                    <a class="nav-link mb-2 ms-2 mt-2" href="#">
                                        <i class="bi bi-people-fill me-2"></i>
                                        <span>Mi equipo</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>';
            }
            elseif ($_SESSION['rol'] == 'Administrador'){
                echo '
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
                                    <span>Regresar a la vista actividades</span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <!--! Cambiar el active en la clase para indicar en la acción en la que está actualmente -->
                                <!--! El aria-current es para indicar que se encuentra en la página actual, también cambiarla según sea el caso -->
                                <a class="nav-link active mb-2 ms-2 mt-2" href="actividades_admin.php?cod='.$_GET['cod'].'">
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
                                <a class="nav-link mb-2 ms-2 mt-2" href="Equipos.php?cod='.$_GET['cod'].'">
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
                        </ul>
                    </div>
                </div>';
            }
        ?>
    </header>
    <!-- ! Contenido -->
    <main class="mt-5">
        <section class="content text-center mt-0 mt-md-5 mt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="mb-3">
                            <img src="img/UDC.png" alt="">
                        </div>
                        <?php
                            echo '<h2 class="mb-3">'.$rowBootcamp['Nombre_bootcamp'].'</h2><br>';
                        ?>
                    </div>
                    <!--? TEXTO DE INSTRUCCIONES -->
                    <div class="content-left" id="texto">
                        <h3>Bienvenid@, espera a que se genere tu equipo</h3>
                        <p><?php echo nl2br($rowBootcamp['Descripcion']) ?></p>
                    </div>
                    <!-- ? Material -->
                    <div class="documento">
                        <?php
                            // while ($material = $resultMaterial->fetch_assoc()) {
                            //     echo '<iframe class="file-preview" src="'.$material['Material'].'"></iframe>';
                            // }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="bg-dark p-2 text-center">
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