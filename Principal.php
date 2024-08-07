<?php
    session_start(); 
    require 'scripts/db.php'; 

    // Función para verificar si el usuario está autenticado
    function estaAutenticado() {
        return isset($_SESSION['rol']) && isset($_SESSION['numero_cuenta']) && isset($_SESSION['id_cuenta']);
    }

    // Función para verificar si el usuario está registrado en algún bootcamp
    function estaRegistradoEnBootcamp($pdo, $id_cuenta, $rol) {
        if ($rol === 'Administrador') {
            // Si es un administrador, no necesita estar registrado en un bootcamp
            $stmt = $pdo->prepare('SELECT b.Codigo FROM asignacion_encargado ae
                                JOIN bootcamp b ON ae.Id_bootcamp = b.Id_bootcamp 
                                WHERE ae.Id_cuenta = ?');
            $stmt->execute([$id_cuenta]);
            return $stmt->fetchColumn(); // Retorna el código del bootcamp si está registrado, o false si no lo está
        }
        else if ($rol === 'Usuario') {
            // Si es un estudiante, verificar si está registrado en algún bootcamp
            $stmt = $pdo->prepare('SELECT b.Codigo FROM asignacion_cuenta ac 
                                JOIN bootcamp b ON ac.Id_bootcamp = b.Id_bootcamp 
                                WHERE ac.Id_cuenta = ?');
            $stmt->execute([$id_cuenta]);
            return $stmt->fetchColumn(); // Retorna el código del bootcamp si está registrado, o false si no lo está
        }
    }

    // Verifica si existe el parámetro 'sesion' en la URL y si su valor es 'cerrar'
    if (isset($_GET['sesion']) && $_GET['sesion'] === 'cerrar') {
        // Destruye la sesión
        session_destroy();
        
        // Limpia la variable de sesión
        $_SESSION = array();
        
        // Redirige al usuario a la página de inicio o login
        header("Location: InicioSesion.php");
        exit();
    }

    // Proteger la página Principal.php
    if (!estaAutenticado()) {
        // Si el usuario no está autenticado, redirigir a la página de inicio de sesión
        header("Location: InicioSesion.php");
        exit();
    }

    // Verificar si el usuario está registrado en algún bootcamp
    $codigo_bootcamp = estaRegistradoEnBootcamp($pdo, $_SESSION['id_cuenta'], $_SESSION['rol']);
    if ($codigo_bootcamp) {
        if ($_SESSION['rol'] == 'Administrador') {
            // Si es un administrador, redirigir a actividades.php con el código del bootcamp
            header("Location: actividades_admin.php?cod=" . $codigo_bootcamp);
            exit();
        }
        elseif ($_SESSION['rol'] == 'Usuario') {
            // Si es un estudiante, redirigir a actividades.php con el código del bootcamp
            header("Location: actividades.php?cod=" . $codigo_bootcamp);
            exit();
        }
        exit();
    }

    // Si no está registrado en un bootcamp, permanecer en Principal.php
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DGICE</title>
    <link rel="icon" href="img/UDC_logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/ingresar_cod_bootcmp.css">
    
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <div class="container">
                <a href="Principal.php" class="navbar-brand"><img src="img/logo.png" alt="Logo"></a>
    
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbar-start" aria-controls="navbar-start" aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar-start">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 ">
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.facebook.com/dgiceUdeC">Nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home_bootcamp.php">¿Qué es un Bootcamp?</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Principal.php?sesion=cerrar"><span class="btn btn-outline-secondary"></spa>Cerrar Sesión</a>
                        </li>
                        <!--* Icono de Calendario -->
                        <!-- <li class="nav-item" id="calendarioActividades">
                            <div class="container">
                                <div class="row">
                                    <div class="col-1"><span class="bi bi-calendar-week"></span></div>
                                    <div class="col-11 d-block d-lg-none"><a class="nav-link" href="#">Actividades</a></div>
                                </div>
                            </div>
                        </li> -->
                        <!--* Icono de Perfil -->
                        <!-- <li class="nav-item" id="miPerfil">
                            <div class="container">
                                <div class="row">
                                    <div class="col-1"><span class="bi bi-person-circle"></span></div>
                                    <div class="col-11 d-block d-lg-none"><a class="nav-link" href="#">Mi perfil</a></div>
                                </div>
                            </div>
                        </li> -->
                        <!--* Icono de Notificaciones -->
                        <!-- <li class="nav-item">
                            <a href="#"><span class="bi bi-envelope" title="Notificaciones"></span></a>
                            <a href="#"><span class="bi bi-envelope-exclamation" title="Notificaciones"></span></a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="mt-5">
        <div id="carouselE1" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-idicators">
                <button type="button" data-bs-target="#carouselE1"
                        data-bs-slide-to="0" class="active" aria-current="true"
                        arial-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselE1"
                        data-bs-slide-to="1" class="active" aria-current="true"
                        arial-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselE1"
                        data-bs-slide-to="2" class="active" aria-current="true"
                        arial-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/BANNER INNOVACION (1)(1).png" class="d-block w-100" alt="...">
                </div>

                <div class="carousel-item">
                    <img src="img/BANNER COMUNICACION (1).png" class="d-block w-100" alt="...">
                </div>
    
                <div class="carousel-item">
                    <img src="img/BANNER EMPRENDIMIENTO (1).png" class="d-block w-100" alt="...">
                </div>

                <div class="carousel-item">
                    <img src="img/BANNER INTELIGENCIA COMPETITIVA.png" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselE1"
                    data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselE1"
                    data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <section class="nosotros">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-header text-center pb-5">
                            <h2>Acerca de nosotros</h2>
                            <p>En la Dirección General de Innovación y Cultura Emprendedora, 
                                fomentamos el emprendedurismo en nuestros estudiantes universitarios a través de valores, 
                                brindándoles herramientas de capacitación y desarrollo para su formación integral y que se 
                                conviertan en agentes de cambio para su comunidad y en el Estado de Colima, así mismo 
                                promoviendo la cultura del ahorro, pues es la base para un emprendimiento exitoso.
                            </p>
                            <p>
                                Nuestra función es proponer y fomentar el desarrollo de la cultura emprendedora, 
                                innovación y aplicación del conocimiento en la comunidad universitaria y en la sociedad, 
                                que inciten a formar alianzas estratégicas de los universitarios con los sectores sociales
                                para el desarrollo de acciones programas y proyectos con potencial interno y externo que 
                                fomenten la innovación y el emprendimiento.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="actividad">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-header text-center pb-5">
                            <form action="scripts/registrar_bootcamp.php" method="POST" id="loginForm" onsubmit="return validarFormulario()">
                                <h2>¡Iniciemos!</h2>
                                <p>Para comenzar, registrate en una actividad que se este llevando actualmente en tu campus.</p>
                                <div class="form-group">
                                    <label for="bootcamp_codg">Ingresa el código del bootcamp: </label>
                                    <input type="text" id="bootcamp_codg" name="bootcamp_codg" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Regístrame</button>
                            </form>
                        </div>
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