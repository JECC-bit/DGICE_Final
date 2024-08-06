<?php
session_start();
include 'scripts/db.php';

// Consultar los encargados (administradores)
$sql_encargados = "SELECT c.Id_cuenta, n.Nombres, n.Apellido_paterno, n.Apellido_materno FROM cuenta c JOIN usuario u ON c.No_cuenta = u.No_cuenta JOIN nombre n ON u.Id_nombre = n.Id_nombre WHERE c.Rol = 'Administrador'";
$result_encargados = $conn->query($sql_encargados);

// Consultar las dependencias (campus)
$sql_dependencias = "SELECT Id_campus, Campus FROM campus";
$result_dependencias = $conn->query($sql_dependencias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear bootcamp</title>
    <link rel="icon" href="img/UDC_logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/file.css">
    <link rel="stylesheet" href="css/crearBootcampCSS.css">
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
            
    </nav>
    <!--? Barra lateral retraible -->
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="barraLateralRetraible" aria-labelledby="barraLateralLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="barraLateralLabel">Acciones</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <!--! Cambiar el active en la clase para indicar en la acción en la que está actualmente -->
                <!--! El aria-current es para indicar que se encuentra en la página actual, también cambiarla según sea el caso -->
                <li class="nav-item active mb-2 shadow rounded"><a href="crear_bootcamp.php" class="nav-link active mb-2 ms-4 mt-2" aria-current="page"><i class="bi bi-plus-circle me-2"></i><span>Crear bootcamp</span></a></li>
                <li class="nav-item mb-2"><a href="bootcamps_Index.php" class="nav-link mb-2 ms-2 mt-2"><i class="bi bi-list-stars me-2"></i><span>Bootcamps existentes</span> </a></li>
            </ul>
        </div>
    </div>
    <div class="container-fluid" id="main">
        <div class="row">
            <!--? Barra lateral fija -->
            <div class="col-2 bg-body-tertiary d-none d-lg-block" id="barraLateralFija">
                <ul class="nav flex-column">
                    <li class="nav-item active shadow rounded">
                        <a class="nav-link active" aria-current="page" href="crear_bootcamp.php">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span> Crear bootcamp</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bootcamps_Index.php">
                            <i class="bi bi-list-stars me-2"></i>
                            <span> Bootcamps existentes</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!--? Contenido del formulario -->
            <div class="col-9 col-lx-10" id="contenido">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h1>Crear bootcamp</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-11 col-lx-12">
                            <form action="crear_bootcamp.php" method="POST">
                                <div class="mb-3">
                                    <label for="nombreBootcamp" class="form-label">Nombre del bootcamp</label>
                                    <input type="text" class="form-control" id="nombreBootcamp" name="nombreBootcamp" required>
                                </div>
                                <div class="mb-3">
                                    <label for="encargadoBootcamp" class="form-label">Encargado(s) del bootcamp</label>
                                    <select class="form-select" id="encargadoBootcamp" name="encargadoBootcamp" required>
                                        <?php
                                        if ($result_encargados->num_rows > 0) {
                                            while($row = $result_encargados->fetch_assoc()) {
                                                echo '<option value="' . $row["Id_cuenta"] . '">' . $row["Nombres"] . ' ' . $row["Apellido_paterno"] . ' ' . $row["Apellido_materno"] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No hay encargados disponibles</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="dependenciaBootcamp" class="form-label">Dependencia en la que se realiza el bootcamp</label>
                                    <select class="form-select" id="dependenciaBootcamp" name="dependenciaBootcamp" required>
                                        <?php
                                        if ($result_dependencias->num_rows > 0) {
                                            while($row = $result_dependencias->fetch_assoc()) {
                                                echo '<option value="' . $row["Id_campus"] . '">' . $row["Campus"] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No hay dependencias disponibles</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcionBootcamp" class="form-label">Descripción del bootcamp</label>
                                    <textarea class="form-control" id="descripcionBootcamp" name="descripcionBootcamp" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="fechaInicioBootcamp" class="form-label">Fecha de inicio del bootcamp</label>
                                                <input type="date" class="form-control" id="fechaInicioBootcamp" name="fechaInicioBootcamp" required>
                                            </div>
                                            <div class="col-6">
                                                <label for="fechaFinBootcamp" class="form-label">Fecha de fin del bootcamp</label>
                                                <input type="date" class="form-control" id="fechaFinBootcamp" name="fechaFinBootcamp" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--* Agregamos la opción de subir varios archivos -->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class=" drop-area">
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
                                                <input type="file" name="#" id="input-file" hidden multiple>
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
                                <div class="mt-3 mb-4">
                                    <button type="submit" class="btn btn-primary">Crear bootcamp</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
include 'scripts/db.php';

// Función para generar un código aleatorio
function generarCodigo($longitud = 6) {
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longitud_caracteres = strlen($caracteres);
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, $longitud_caracteres - 1)];
    }
    return $codigo;
}

// Función para verificar que el código sea único
function esCodigoUnico($codigo, $pdo) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM bootcamp WHERE codigo = ?');
    $stmt->execute([$codigo]);
    return $stmt->fetchColumn() == 0;
}

// Generar un código único
function generarCodigoUnico($pdo, $longitud = 6) {
    do {
        $codigo = generarCodigo($longitud);
    } while (!esCodigoUnico($codigo, $pdo));
    return $codigo;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombreBootcamp = $_POST["nombreBootcamp"];
    $encargadoBootcamp = $_POST["encargadoBootcamp"];
    $dependenciaBootcamp = $_POST["dependenciaBootcamp"];
    $descripcionBootcamp = $_POST["descripcionBootcamp"];
    $fechaInicioBootcamp = $_POST["fechaInicioBootcamp"];
    $fechaFinBootcamp = $_POST["fechaFinBootcamp"];
    $codigo_unico = generarCodigoUnico($pdo);

    // Insertar el nuevo bootcamp en la tabla `bootcamp`
    $sql = "INSERT INTO bootcamp (Codigo, Nombre_bootcamp, Fecha_inicio, Fecha_cierre, Descripcion, Id_campus, Status, Created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'activo', NOW())";

    // Preparar la declaración
    if ($stmt = $conn->prepare($sql)) {
        // Enlazar parámetros
        $stmt->bind_param("sssssi", $codigo_unico, $nombreBootcamp, $fechaInicioBootcamp, $fechaFinBootcamp, $descripcionBootcamp, $dependenciaBootcamp);

        // Ejecutar la declaración
        if ($stmt->execute()) {
            // Obtener el ID del nuevo bootcamp insertado
            $nuevoIdBootcamp = $stmt->insert_id;

            // Asignar el encargado al bootcamp en la tabla `asignacion_encargado`
            $sql_asignacion_encargado = "INSERT INTO asignacion_encargado (Id_cuenta, Id_bootcamp) VALUES (?, ?)";
            if ($stmt_asignacion_encargado = $conn->prepare($sql_asignacion_encargado)) {
                $stmt_asignacion_encargado->bind_param("ii", $encargadoBootcamp, $nuevoIdBootcamp);
                $stmt_asignacion_encargado->execute();
                $stmt_asignacion_encargado->close();
            }

            echo "Bootcamp creado exitosamente.";
           
        } else {
            echo "Error: " . $stmt->error;
        }
        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la declaración: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>
