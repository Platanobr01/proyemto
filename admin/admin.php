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

// Array asociativo con nombres de tablas y nombres de archivos administrativos
$tablas = [
    "pagos" => ["nombre" => "Pagos", "archivoAdmin" => "pagos.php"],
    "proovedores" => ["nombre" => "Proveedores", "archivoAdmin" => "proovedores.php"],
    "servicios" => ["nombre" => "Servicios", "archivoAdmin" => "servicios.php"],
    "usuarios" => ["nombre" => "Usuarios", "archivoAdmin" => "usuarios.php"]
];

// Función para mostrar una tabla con enlace de administración y fondo de color
function mostrarTabla($tablaInfo, $colorFondo)
{
    $nombreTabla = $tablaInfo["nombre"];
    $archivoAdmin = $tablaInfo["archivoAdmin"];

    echo "<div class='mt-4 border p-3' style='background-color: $colorFondo;'>";
    echo "<h2 class='mb-3'>$nombreTabla</h2>";

    // Enlace de registrar
    if ($nombreTabla === "Proveedores") {
        echo "<a href='$archivoAdmin' class='btn btn-success mb-3'>Registrar</a>";
        echo "&nbsp;&nbsp;"; // Agregado espacio entre botones
    }

    // Enlace de administrar
    echo "<a href='bit_$archivoAdmin' class='btn btn-primary mb-3'>Administrar</a>";

    echo "</div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Administracion HuTao</title>
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
        <h1 class="text-center mb-4">Administracion HuTao</h1>

        <!-- Botón de cerrar sesión fijo -->
        <a href="../login.php" class="btn btn-danger fixed-button">Cerrar Sesión</a>

        <!-- Mostrar tablas con enlaces de administración y fondos de color -->
        <div class="row">
            <?php
            $coloresFondo = ["#f0f8ff", "#ffe4e1", "#f0e68c", "#dda0dd"]; // Puedes cambiar estos colores
            $indexColor = 0;

            foreach ($tablas as $nombreTabla => $tablaInfo) {
                echo "<div class='col-md-6'>";
                mostrarTabla($tablaInfo, $coloresFondo[$indexColor]);
                echo "</div>";
                $indexColor = ($indexColor + 1) % count($coloresFondo);
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
