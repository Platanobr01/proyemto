<?php
include "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar_servicio"])) {
    $editarServicioID = $_POST["editar_servicio_id"];

    // Realizar consulta para obtener los detalles del servicio seleccionado
    $detalleSql = "SELECT Concepto, Monto FROM servicios WHERE ID = ?";
    $detalleStmt = $con->prepare($detalleSql);

    if ($detalleStmt) {
        $detalleStmt->bind_param("i", $editarServicioID);
        $detalleStmt->execute();
        $detalleStmt->bind_result($conceptoEditado, $montoEditado);
        $detalleStmt->fetch();
        $detalleStmt->close();
    }
}

// Procesar la actualización del servicio
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardar_edicion"])) {
    $conceptoEditado = $_POST["edit_concepto"];
    $montoEditado = $_POST["edit_monto"];
    $editarServicioID = $_POST["servicio_id"];

    // Realizar la actualización en la base de datos
    $actualizarSql = "UPDATE servicios SET Concepto = ?, Monto = ? WHERE ID = ?";
    $actualizarStmt = $con->prepare($actualizarSql);

    if ($actualizarStmt) {
        $actualizarStmt->bind_param("sdi", $conceptoEditado, $montoEditado, $editarServicioID);

        if ($actualizarStmt->execute()) {
            echo '<div class="container mt-4 alert alert-success">Servicio actualizado correctamente.</div>';
            // Agregar script de redirección después de 5 segundos
            echo '<script>
                    setTimeout(function(){
                        window.location.href = "bit_servicios.php";
                    }, 500);
                </script>';
        } else {
            echo '<div class="container mt-4 alert alert-danger">Error al actualizar el servicio: ' . $actualizarStmt->error . '</div>';
        }

        $actualizarStmt->close();
    } else {
        echo '<div class="container mt-4 alert alert-danger">Error en la preparación de la sentencia de actualización: ' . $con->error . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Editar Servicio</title>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Editar Servicio</h1>

        <div class="mt-4 border p-3">
            <h2 class="mb-3">Detalles del Servicio</h2>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="edit_concepto" class="form-label">Concepto</label>
                    <input type="text" class="form-control" id="edit_concepto" name="edit_concepto" value="<?php echo $conceptoEditado; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="edit_monto" class="form-label">Monto</label>
                    <input type="number" class="form-control" id="edit_monto" name="edit_monto" value="<?php echo $montoEditado; ?>" required>
                </div>
                <input type="hidden" name="servicio_id" value="<?php echo $editarServicioID; ?>">
                <button type="submit" class="btn btn-primary" name="guardar_edicion">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
