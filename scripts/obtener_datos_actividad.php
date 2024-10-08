<?php
include 'db.php';

$idActividad = $_GET['id'];

$sqlActivity = "SELECT * FROM actividad WHERE Id_actividad = ?";
$stmtActivity = $conn->prepare($sqlActivity);
$stmtActivity->bind_param("i", $idActividad);
$stmtActivity->execute();
$resultActivity = $stmtActivity->get_result();
$rowActivity = $resultActivity->fetch_assoc();

// Obtener los materiales asociados a la actividad
$sqlMaterials = "SELECT m.Id_material, m.Material FROM material m
                 INNER JOIN asignacion_material am ON m.Id_material = am.Id_material
                 WHERE am.Id_actividad = ?";
$stmtMaterials = $conn->prepare($sqlMaterials);
$stmtMaterials->bind_param("i", $idActividad);
$stmtMaterials->execute();
$resultMaterials = $stmtMaterials->get_result();
?>


<main class="container-fluid" id="main">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la actividad</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo $rowActivity['Titulo']; ?>">
    </div>
    <div class="mb-3">
        <label for="orden" class="form-label">Número de actividad</label>
        <select class="form-control" id="orden" name="orden" required>
            <?php 
                for($i = 1;  $i <= 20; $i++){ 
                    if ($i == $rowActivity['orden']) {
                        echo '<option value="' .$i .'" selected>' .$i. '</option>';
                    } else {
                        echo '<option value="' .$i .'" >' .$i. '</option>';
                    }
                } 
            ?>
        </select>
    </div>
    <div class="mb-3">
        <div class="container-fluid" id="hora-fecha">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label for="fechaEntrega" class="form-label">Fecha de entrega</label>
                    <input type="date" class="form-control" id="fechaEntrega" name="fechaEntrega" required value="<?php echo explode(' ', $rowActivity['Fecha_entrega'])[0]; ?>">
                </div>
                <div class="col-md-6 col-sm-12">
                    <label for="horaEntrega" class="form-label">Hora de entrega</label>
                    <input type="time" class="form-control" id="horaEntrega" name="horaEntrega" required value="<?php echo explode(' ', $rowActivity['Fecha_entrega'])[1]; ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo $rowActivity['Descripcion']; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="video" class="form-label">Link del video de apoyo</label>
        <input type="text" class="form-control" id="video" name="video" value="<?php echo $rowActivity['Video']; ?>">
    </div>
    <!-- Agregar el campo para subir archivos aquí -->
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
                    <input type="file" name="archivos[]" id="input-file" hidden multiple>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 container">
                <div class="mb-3 row" id="preview">
                    <div class="col-12">
                        <h5>Archivos subidos: </h5>
                        <ul>
                            <?php while ($rowMaterial = $resultMaterials->fetch_assoc()): ?>
                                <li><a href="<?php echo $rowMaterial['Material']; ?>" target="_blank"><?php echo basename($rowMaterial['Material']); ?></a></li>
                                <?php endwhile; ?>
                        </ul>
                        <h5>Nuevos archivos: </h5>
                    </div>
                </div>
                <script src="../js/files.js"></script>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Actualizar Actividad</button>
</main>
