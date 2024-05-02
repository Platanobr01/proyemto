<?php
include "../conexion.php";

// Realizar consulta para obtener datos de la tabla de servicios ordenados por ID
$sql = "SELECT * FROM servicios ORDER BY ID";
$result = $con->query($sql);

// Procesar el formulario para agregar un nuevo servicio
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar_servicio"])) {
    $nuevoConcepto = $_POST["nuevo_concepto"];
    $nuevoMonto = $_POST["nuevo_monto"];

    // Validar los datos (puedes agregar más validaciones según tus necesidades)
    if (!empty($nuevoConcepto) && !empty($nuevoMonto)) {
        // Agregar un nuevo servicio a la base de datos
        $agregarSql = "INSERT INTO servicios (Concepto, Monto) VALUES (?, ?)";
        $agregarStmt = $con->prepare($agregarSql);

        if ($agregarStmt) {
            $agregarStmt->bind_param("sd", $nuevoConcepto, $nuevoMonto);

            if ($agregarStmt->execute()) {
                $nuevoID = $agregarStmt->insert_id;  // Obtener el ID del servicio recién insertado
                echo '<div class="container mt-4 alert alert-success">Servicio agregado correctamente.</div>';
                
                // Actualizar los IDs de los servicios existentes
                $actualizarIDSql = "SET @nuevoID = 0; UPDATE servicios SET ID = @nuevoID := @nuevoID + 1; ALTER TABLE servicios AUTO_INCREMENT = @nuevoID;";
                if ($con->multi_query($actualizarIDSql)) {
                    // Mensaje de depuración
                    echo '<div class="container mt-4 alert alert-success">Reorganización de IDs realizada con éxito.</div>';
                } else {
                    // Mensaje de depuración
                    echo '<div class="container mt-4 alert alert-danger">Error al reorganizar los IDs: ' . $con->error . '</div>';
                }
            } else {
                // Mensajes de depuración
                echo '<div class="container mt-4 alert alert-danger">Error al agregar el servicio: ' . $agregarStmt->error . '</div>';
                echo '<div class="container mt-4 alert alert-danger">Error en la ejecución de la consulta de inserción: ' . $con->error . '</div>';
            }

            $agregarStmt->close();
        } else {
            // Mensaje de depuración
            echo '<div class="container mt-4 alert alert-danger">Error en la preparación de la sentencia de inserción: ' . $con->error . '</div>';
        }
    } else {
        echo '<div class="container mt-4 alert alert-danger">Todos los campos son obligatorios. Inténtalo de nuevo.</div>';
    }
}

// Procesar el formulario para cargar un servicio en la tabla de usuarios_servicios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cargar_servicio"])) {
    $servicioID = $_POST["servicio_id"];

    // Realizar consulta para obtener los detalles del servicio seleccionado
    $detalleSql = "SELECT Concepto, Monto FROM servicios WHERE ID = ?";
    $detalleStmt = $con->prepare($detalleSql);

    if ($detalleStmt) {
        $detalleStmt->bind_param("i", $servicioID);
        $detalleStmt->execute();
        $detalleStmt->bind_result($conceptoCargado, $montoCargado);
        $detalleStmt->fetch();
        $detalleStmt->close();
    }

    // Insertar el servicio cargado en la tabla de usuarios_servicios para cada usuario
    $insertarSql = "INSERT IGNORE INTO usuarios_servicios (Concepto, Monto) VALUES (?, ?)";
    $insertarStmt = $con->prepare($insertarSql);

    if ($insertarStmt) {
        $insertarStmt->bind_param("sd", $conceptoCargado, $montoCargado);

        try {
            $insertarStmt->execute();
            echo '<div class="container mt-4 alert alert-success">Servicio cargado en la tabla de usuarios_servicios correctamente.</div>';
        } catch (mysqli_sql_exception $e) {
            echo '<div class="container mt-4 alert alert-danger">Error al insertar el servicio en la tabla de usuarios_servicios: ' . $e->getMessage() . '</div>';
        }

        $insertarStmt->close();
    } else {
        echo '<div class="container mt-4 alert alert-danger">Error en la preparación de la sentencia de inserción: ' . $con->error . '</div>';
    }
}

// Procesar la eliminación de un servicio
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_servicio"])) {
    $eliminarServicioID = $_POST["eliminar_servicio_id"];

    $eliminarSql = "DELETE FROM servicios WHERE ID = ?";
    $eliminarStmt = $con->prepare($eliminarSql);

    if ($eliminarStmt) {
        $eliminarStmt->bind_param("i", $eliminarServicioID);

        if ($eliminarStmt->execute()) {
            echo '<div class="container mt-4 alert alert-success">Servicio eliminado correctamente.</div>';
        } else {
            echo '<div class="container mt-4 alert alert-danger">Error al eliminar el servicio: ' . $eliminarStmt->error . '</div>';
        }

        $eliminarStmt->close();
    } else {
        echo '<div class="container mt-4 alert alert-danger">Error en la preparación de la sentencia de eliminación: ' . $con->error . '</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Visualizar y Editar Servicios</title>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Tabla de Servicios</h1>

        <!-- Mostrar datos de la tabla de servicios -->
        <div class="mt-4 border p-3">
            <h2 class="mb-3">Servicios</h2>

            <!-- Botón para agregar un nuevo servicio -->
            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#agregarServicioModal" data-bs-backdrop="static" data-bs-keyboard="false">
                Agregar Nuevo Servicio
            </button>

            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Acciones</th>
                    </tr>

                    <?php while ($fila = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $fila['ID']; ?></td>
                            <td><?php echo $fila['Concepto']; ?></td>
                            <td>$<?php echo $fila['Monto']; ?> MXN mensuales</td>
                            <td>
                                <!-- Formulario para cargar el servicio en otra tabla -->
                                <form method="POST" action="">
                                    <input type="hidden" name="servicio_id" value="<?php echo $fila['ID']; ?>">
                                    <button type="submit" name="cargar_servicio" class="btn btn-info btn-sm">Cargar en Otra Tabla</button>
                                </form>
                            </td>
                            <td>
                            <!-- Botones de editar -->

    <form method="POST" action="edit_servicios.php">
        <input type="hidden" name="editar_servicio_id" value="<?php echo $fila['ID']; ?>">
        <button type="submit" name="editar_servicio" class="btn btn-warning btn-sm">Editar</button>
    </form>
</td>
                            <!-- Botones de eliminar -->
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="eliminar_servicio_id" value="<?php echo $fila['ID']; ?>">
                                    <button type="submit" name="eliminar_servicio" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                </table>
            <?php else: ?>
                <p>No hay datos en la tabla de servicios.</p>
            <?php endif; ?>
            <a href="admin.php" class="btn btn-danger">Volver al menu</a>
        </div>
    </div>

    <!-- Modal para agregar un nuevo servicio -->
    <div class="modal fade" id="agregarServicioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Contenido del formulario para agregar un nuevo servicio -->
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="nuevo_concepto" class="form-label">Concepto</label>
                            <input type="text" class="form-control" id="nuevo_concepto" name="nuevo_concepto" required>
                        </div>
                        <div class="mb-3">
                            <label for="nuevo_monto" class="form-label">Monto</label>
                            <input type="number" class="form-control" id="nuevo_monto" name="nuevo_monto" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="agregar_servicio">Agregar Servicio</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
