<?php
include "../conexion.php";
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit();
}


$rol = $_SESSION["rol"];

$tablas = [
    "pagos" => ["nombre" => "Pagos", "archivoUsuario" => "pagos.php"],
    "proovedores" => ["nombre" => "Proveedores", "archivoUsuario" => "proovedores.php"],
    "servicios" => ["nombre" => "Servicios disponbles:", "archivoUsuario" => "servicios.php"]
];

function mostrarTabla($tablaInfo, $colorFondo)
{
    $nombreTabla = $tablaInfo["nombre"];
    $archivoUsuario = $tablaInfo["archivoUsuario"];

    echo "<div class='mt-4 border p-3' style='background-color: $colorFondo;'>";
    echo "<h2 class='mb-3'>$nombreTabla</h2>";

        // Enlace de administrar
        echo "<a href='bit_$archivoUsuario' class='btn btn-primary mb-3'>Consultar</a>";
    
    echo "</div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Document</title>
    <style>
        body {
            background-image: url('https://images8.alphacoders.com/133/1337700.jpeg');
            background-size: cover;
        }
        .fixed-button {
            position: fixed;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Bienvenido a su residencia</h1>

        <a href="../login.php" class="btn btn-danger fixed-button">Cerrar Sesion</a>

        <div class="row">
            <?php
            $coloresFondo = ["", "", "",];
            $indexColor = 0;

            foreach ($tablas as $nombreTabla => $tablaInfo) {
                echo "<div class='col-md-6'>";
                mostrarTabla($tablaInfo, $coloresFondo[$indexColor]);
                echo"</div>";
                $indexColor = ($indexColor + 1) % count($coloresFondo);

            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>