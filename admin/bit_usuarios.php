<?php
include "../conexion.php";
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

// Obtener el rol del usuario autenticado
$rol = $_SESSION["rol"];

// Verificar si el usuario tiene permisos para acceder a esta página
if ($rol !== "administrador") {
    echo "Acceso no autorizado.";
    exit();
}

// Función para ejecutar consultas SQL y obtener resultados
function ejecutarConsulta($conexion, $sql)
{
    $result = $conexion->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conexion->error);
    }

    return $result;
}

// Función para obtener todos los usuarios de la tabla
function obtenerUsuarios($conexion)
{
    $sql = "SELECT * FROM usuarios";
    $result = ejecutarConsulta($conexion, $sql);

    $usuarios = [];

    while ($fila = $result->fetch_assoc()) {
        $usuarios[] = $fila;
    }

    return $usuarios;
}

// Función para actualizar un usuario en la tabla
function actualizarUsuario($conexion, $id, $usuario, $contrasena, $rol)
{
    $sql = "UPDATE usuarios SET Usuario = '$usuario', Contraseña = '$contrasena', Rol = '$rol' WHERE ID = $id";
    $result = ejecutarConsulta($conexion, $sql);

    return $result;
}

// Obtener la lista de usuarios
$listaUsuarios = obtenerUsuarios($con);

// Procesar la actualización de usuario si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idUsuarioActualizar = $_POST["id"];
    $nuevoUsuario = $_POST["usuario"];
    $nuevaContrasena = $_POST["contrasena"];
    $nuevoRol = $_POST["rol"];

    actualizarUsuario($con, $idUsuarioActualizar, $nuevoUsuario, $nuevaContrasena, $nuevoRol);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Visualizar y Editar Usuarios</title>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Visualizar y Editar Usuarios</h1>

        <!-- Mostrar datos de la tabla de usuarios -->
        <div class="mt-4 border p-3">
            <h2 class="mb-3">Usuarios</h2>

            <?php if (!empty($listaUsuarios)): ?>
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Contraseña</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>

                    <?php foreach ($listaUsuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['ID']; ?></td>
                            <td><?php echo $usuario['Usuario']; ?></td>
                            <td><?php echo $usuario['Contraseña']; ?></td>
                            <td><?php echo $usuario['Rol']; ?></td>
                            <td>
                                <!-- Botón para abrir el formulario de edición -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarUsuario<?php echo $usuario['ID']; ?>">
                                    Editar
                                </button>

                                <!-- Modal de edición de usuario -->
                                <div class="modal fade" id="editarUsuario<?php echo $usuario['ID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Formulario de edición -->
                                                <form method="POST" action="">
                                                    <input type="hidden" name="id" value="<?php echo $usuario['ID']; ?>">
                                                    <div class="mb-3">
                                                        <label for="usuario" class="form-label">Usuario:</label>
                                                        <input type="text" id="usuario" name="usuario" class="form-control" value="<?php echo $usuario['Usuario']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="contrasena" class="form-label">Contraseña:</label>
                                                        <input type="password" id="contrasena" name="contrasena" class="form-control" value="<?php echo $usuario['Contraseña']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="rol" class="form-label">Rol:</label>
                                                        <select id="rol" name="rol" class="form-select" required>
                                                            <option value="administrador" <?php echo ($usuario['Rol'] === 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                                                            <option value="usuario" <?php echo ($usuario['Rol'] === 'usuario') ? 'selected' : ''; ?>>Usuario</option>
                                                        </select>
                                                    </div>
                                                    <div class="d-grid gap-2">
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            <?php else: ?>
                <p>No hay datos en la tabla de usuarios.</p>
            <?php endif; ?>
            <a href="admin.php" class="btn btn-success">Volver al menu</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
