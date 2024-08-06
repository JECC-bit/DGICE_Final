<?php
include 'scripts/db.php';

// Consultar los bootcamps
$sql = "SELECT b.Id_bootcamp, b.Codigo, b.Nombre_bootcamp, b.Descripcion, c.Campus AS Nombre_campus
        FROM bootcamp b
        JOIN campus c ON b.Id_campus = c.Id_campus";
$result = $conn->query($sql);

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
    <title>Bootcamps</title>
    <link rel="icon" href="img/UDC_logo.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootcamps_index.css">
</head>
<body>
    <nav class="navbar bg-body-tertiary fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#barraLateral" aria-controls="barraLateral" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="bootcamps_index.php" class="navbar-brand"><img src="img/logo.png" alt="Logo"></a>
            <a class="nav-link" href="#"><span class="bi bi-person-circle" title="Perfil"></span></a>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="barraLateral" aria-labelledby="barraLateralLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="barraLateralLabel">Acciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <div class="container">
                                <div class="row">
                                    <div class="col-2"><span class="bi bi-bullseye"></span></div>
                                    <div class="col-10"><a class="nav-link active" aria-current="page" href="crear_bootcamp.php">Crear bootcamp</a></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
      </nav>

    <div class="main-content">
        <h1>¡Hola! Bienvenido</h1>
        <h2>Bootcamps en curso</h2>
        <div class="bootcamp-list">
            <?php
            if ($result->num_rows > 0) {
                // Mostrar cada bootcamp
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="bootcamp-block">';
                    echo '<a href="actividades_admin.php?cod='. $row["Codigo"] .'" style="text-decoration: none; color: inherit;"><h2>' . $row["Nombre_bootcamp"] . '</h2></a>';
                    echo '<p class="description">' . $row["Descripcion"] . '</p>';
                    echo '<p class="code">Código del Bootcamp: ' . $row["Codigo"] . '</p>';
                    echo '<p class="dependencia">Dependencia: ' . $row["Nombre_campus"] . '</p>';
                    echo '<button class="edit-btn" data-id="' . $row["Id_bootcamp"] . '">Editar</button>';
                    echo '<button class="delete-btn" data-id="' . $row["Id_bootcamp"] . '">Eliminar</button>';
                    echo '<button class="list-btn" data-id="' . $row["Id_bootcamp"] . '">Generar listado de integrantes</button>';
                    echo '</div>';
                }
            } else {
                // No hay bootcamps disponibles
                echo '<p>No hay bootcamps disponibles. <a href="crear_bootcamp.php">Crear uno nuevo</a></p>';
            }
            // Cerrar la conexión
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Modal de edición -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Bootcamp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBootcampForm" action="scripts/editar_bootcamp.php" method="POST">
                        <input type="hidden" name="id_bootcamp" id="editBootcampId">
                        <div class="mb-3">
                            <label for="editNombreBootcamp" class="form-label">Nombre del bootcamp</label>
                            <input type="text" class="form-control" id="editNombreBootcamp" name="nombreBootcamp" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEncargadoBootcamp" class="form-label">Encargado(s) del bootcamp</label>
                            <select class="form-select" id="editEncargadoBootcamp" name="encargadoBootcamp" required>
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
                            <label for="editDependenciaBootcamp" class="form-label">Dependencia en la que se realiza el bootcamp</label>
                            <select class="form-select" id="editDependenciaBootcamp" name="dependenciaBootcamp" required>
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
                            <label for="editDescripcionBootcamp" class="form-label">Descripción del bootcamp</label>
                            <textarea class="form-control" id="editDescripcionBootcamp" name="descripcionBootcamp" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="editFechaInicioBootcamp" class="form-label">Fecha de inicio del bootcamp</label>
                                        <input type="date" class="form-control" id="editFechaInicioBootcamp" name="fechaInicioBootcamp" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="editFechaFinBootcamp" class="form-label">Fecha de fin del bootcamp</label>
                                        <input type="date" class="form-control" id="editFechaFinBootcamp" name="fechaFinBootcamp" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 mb-4">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const idBootcamp = this.getAttribute('data-id');
                    // Realizar una solicitud AJAX para obtener los datos del bootcamp
                    fetch('scripts/obtener_bootcamp.php?id=' + idBootcamp)
                        .then(response => response.json())
                        .then(data => {
                            // Llenar el formulario del modal con los datos obtenidos
                            document.getElementById('editBootcampId').value = data.Id_bootcamp;
                            document.getElementById('editNombreBootcamp').value = data.Nombre_bootcamp;
                            document.getElementById('editEncargadoBootcamp').value = data.Id_cuenta;
                            document.getElementById('editDependenciaBootcamp').value = data.Id_campus;
                            document.getElementById('editDescripcionBootcamp').value = data.Descripcion;
                            document.getElementById('editFechaInicioBootcamp').value = data.Fecha_inicio;
                            document.getElementById('editFechaFinBootcamp').value = data.Fecha_cierre;
                            // Mostrar el modal
                            new bootstrap.Modal(document.getElementById('editModal')).show();
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
    <script src="js/eliminar.js"></script>
</body>
</html>
