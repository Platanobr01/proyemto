<?php
include "../conexion.php";
session_start();


// Obtener el rol del usuario autenticado
$rol = $_SESSION["rol"];

// Verificar si el usuario tiene permisos para acceder a esta página
if ($rol !== "administrador") {
    echo "Acceso no autorizado.";
    exit();
}

// Realizar consulta para obtener datos de la tabla proovedores
$sql = "SELECT ID, nombre, Apellido, fecha, asunto, Empresa, Correo, horaE, horaS FROM proovedores";
$result = $con->query($sql);

// Nombres personalizados para las columnas
$columnasPersonalizadas = array(
    "ID" => "ID",
    "nombre" => "Nombre",
    "Apellido" => "Apellido",
    "fecha" => "Fecha",
    "asunto" => "Asunto",
    "Empresa" => "Empresa",
    "Correo" => "Correo",
    "horaE" => "Hora de Entrada",
    "horaS" => "Hora de Salida"
);

// Procesar la actualización de la columna horaS
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_horaS"])) {
        $proveedorID = $_POST["proveedor_id"];
        $nuevaHoraS = $_POST["nueva_horaS"];

        // Validar los datos (puedes agregar más validaciones según tus necesidades)
        if (!empty($proveedorID) && !empty($nuevaHoraS)) {
            // Actualizar la columna horaS en la base de datos
            $updateSql = "UPDATE proovedores SET horaS = ? WHERE ID = ?";
            $updateStmt = $con->prepare($updateSql);

            if ($updateStmt) {
                $updateStmt->bind_param("si", $nuevaHoraS, $proveedorID);

                if ($updateStmt->execute()) {
                    echo '<div class="container mt-4 alert alert-success">Actualización exitosa.</div>';
                } else {
                    echo '<div class="container mt-4 alert alert-danger">Error al actualizar: ' . $updateStmt->error . '</div>';
                }

                $updateStmt->close();
            } else {
                echo '<div class="container mt-4 alert alert-danger">Error en la preparación de la sentencia de actualización: ' . $con->error . '</div>';
            }
        } else {
            echo '<div class="container mt-4 alert alert-danger">Todos los campos son obligatorios. Inténtalo de nuevo.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Visualizar y Editar Proveedores</title>
    <style>
        .fixed-button {
            position: fixed;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Bitácora de proveedores</h1>

        <!-- Botón de cerrar sesión fijo -->
        <a href="../login.php" class="btn btn-danger fixed-button">Cerrar Sesión</a>

        <!-- Mostrar datos de la tabla proovedores -->
        <div class="mt-4 border p-3">
            <h2 class="mb-3">Datos de Proveedores</h2>

            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <tr>
                        <?php
                        // Obtener nombres personalizados de columnas
                        foreach ($columnasPersonalizadas as $columna => $nombre) {
                            echo "<th>{$nombre}</th>";
                        }
                        ?>
                        <th>Actualizar Hora de salida</th>
                    </tr>

                    <?php while ($fila = $result->fetch_assoc()): ?>
                        <tr>
                            <?php foreach ($fila as $clave => $valor): ?>
                                <td><?php echo $valor; ?></td>
                            <?php endforeach; ?>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="proveedor_id" value="<?php echo $fila['ID']; ?>">
                                    <input type="time" name="nueva_horaS" required>
                                    <button type="submit" name="update_horaS" class="btn btn-primary btn-sm">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                </table>
            <?php else: ?>
                <p>No hay datos en la tabla.</p>
            <?php endif; ?>
            <a href="admin.php" class="btn btn-success">Volver al menu</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
    <!-- Script para redirigir al usuario a admin.php al presionar retroceso -->
    <script>
        window.addEventListener('popstate', function() {
            window.location.href = 'admin.php';
        });
        history.pushState({}, '');
    </script>
</body>
</html>
