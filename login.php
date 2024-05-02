<?php
include "conexion.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = isset($_POST["usuario"]) ? $_POST["usuario"] : "";
    $contrasena = isset($_POST["contrasena"]) ? $_POST["contrasena"] : "";

    if (!empty($usuario) && !empty($contrasena)) {
        $sql = "SELECT ID, Usuario, Rol FROM Usuarios WHERE Usuario = ? AND Contraseña = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $usuario, $contrasena);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $_SESSION["id"] = $row["ID"];
            $_SESSION["nombre"] = $row["Usuario"];
            $_SESSION["rol"] = $row["Rol"];

            if ($usuario === "admin" && $contrasena === "admin") {
                header("Location: admin/admin.php");
                exit();
            } else {
                header("Location: usuario/usuarios.php");
                exit();
            }
        } else {
            $error_message = "Credenciales inválidas. Inténtalo de nuevo.";
        }
    } else {
        $error_message = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Iniciar Sesión privada</title>
    <style>
        body {
            background-image: url('https://wallpaperwaifu.com/wp-content/uploads/2021/09/hu-tao-genshin-impact-4k-thumb.jpg');
            background-size: cover;
            background-position: center;
        }
        .form-container {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="form-container">
            <h1 class="text-center mb-4">Privada con privacidad</h1>
            <?php
            if (isset($error_message)) {
                echo '<div class="container mt-4 alert alert-danger">' . $error_message . '</div>';
            }
            ?>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Password:</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </form>
            <div class="d-flex justify-content-between mt-3">
                <a href="registro.php" class="btn btn-secondary">Registrarse</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
