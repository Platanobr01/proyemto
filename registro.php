<!DOCTYPE html>
<html>
<head>
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center">Registro de Usuario</h2>
                <form action="registro.php" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for "usuario" class="form-label">Usuario:</label>
                        <input type="text" id="usuario" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Password:</label>
                        <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmarContrasena" class="form-label">Confirmar password:</label>
                        <input type="password" id="confirmarContrasena" name="confirmarContrasena" class="form-control" required>
                    </div>
                    <!-- Agrega un campo oculto para el rol de "usuario" -->
                    <input type="hidden" id="rol" name="rol" value="usuario">
                    <!-- Botón de Registrarse -->
                    <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                </form>
            </div>
        </div>
    </div>

    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Archivo de conexión a la base de datos (conexion.php)
    include "conexion.php";

    // Recopila los datos del formulario
    $nombre = $_POST["nombre"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $rol = $_POST["rol"]; // El rol se establece como "usuario"

    // Verifica que las contraseñas coincidan
    if ($_POST["contrasena"] !== $_POST["confirmarContrasena"]) {
        echo '<div class="container mt-4 alert alert-danger">Las contraseñas no coinciden. Inténtalo de nuevo.</div>';
    } else {
        // Inserta el nuevo usuario en la base de datos
        $sql = "INSERT INTO Usuarios (Usuario, Contraseña, Rol) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $usuario, $contrasena, $rol);
        
        if ($stmt->execute()) {
            echo '<div class="container mt-4 alert alert-success">Registro exitoso.</div>';
            // Redirige a login.php después de 3 segundos
            echo '<script>setTimeout(function() { window.location = "login.php"; }, 3000);</script>';
        } else {
            echo '<div class="container mt-4 alert alert-danger">Hubo un problema en el registro. Inténtalo de nuevo.</div>';
        }
    }
}
?>

    <!-- Incluye Bootstrap 5 JS (jQuery y Popper.js son requeridos) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
