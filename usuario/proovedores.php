<?php
include "../conexion.php"; // Asegúrate de incluir tu archivo de conexión

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopilar datos del formulario
    $nombre = $_POST["nombre"];
    $fecha = $_POST["fecha"];
    $asunto = $_POST["asunto"];
    $horaE = $_POST["horaE"];
    $horaS = $_POST["horaS"];

    // Validar los datos
    if (empty($nombre) || empty($fecha) || empty($asunto) || empty($horaE)) {
        echo '<div class="container mt-4 alert alert-danger">Todos los campos son obligatorios. Inténtalo de nuevo.</div>';
    } else {
        // Preparar la consulta SQL para la inserción
        $sql = "INSERT INTO proovedores (nombre, fecha, asunto, horaE, horaS) VALUES (?, ?, ?, ?, ?)";
        
        // Preparar la sentencia
        $stmt = $con->prepare($sql);

        if ($stmt) {
            // Vincular los parámetros
            $stmt->bind_param("sssss", $nombre, $fecha, $asunto, $horaE, $horaS);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Inserción exitosa
                echo '<div class="container mt-4 alert alert-success">Registro exitoso.</div>';

                // Redirigir a admin.php después de 3 segundos
                echo '<script>
                        setTimeout(function() {
                            window.location.href = "admin.php";
                        }, 3000);
                      </script>';
            } else {
                // Error en la ejecución de la consulta SQL
                echo '<div class="container mt-4 alert alert-danger">Error en la ejecución de la consulta SQL: ' . $stmt->error . '</div>';
            }

            // Cerrar la sentencia
            $stmt->close();
        } else {
            // Error en la preparación de la sentencia
            echo '<div class="container mt-4 alert alert-danger">Error en la preparación de la sentencia: ' . $con->error . '</div>';
        }
    }

    // Cerrar la conexión
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Registrar Proveedor</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center">Registrar Proveedor</h2>
                <form action="" method="POST"> <!-- Cambiado para enviar el formulario a sí mismo -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto:</label>
                        <input type="text" id="asunto" name="asunto" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="horaE" class="form-label">Hora de Entrada:</label>
                        <input type="time" id="horaE" name="horaE" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="horaS" class="form-label">Hora de Salida:</label>
                        <input type="time" id="horaS" name="horaS" class="form-control">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
