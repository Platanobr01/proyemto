<?php
include "../conexion.php";

$sql = "SELECT * FROM usuarios_servicios";
$result = $con->query($sql);

// Procesar la eliminación de un servicio
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_servicio"])) {
    $eliminarServicioID = $_POST["eliminar_servicio_id"];

    // Consulta SQL para eliminar el servicio
    $eliminarSQL = "DELETE FROM usuarios_servicios WHERE ID = $eliminarServicioID";
    if ($con->query($eliminarSQL) === TRUE) {
        echo "Servicio pagado correctamente.";
    } else {
        echo "Error al pagar el servicio: " . $con->error;
    }
}

// Inicializar la suma total
$sumaTotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('https://images6.alphacoders.com/130/1305664.jpeg');
            background-size: cover;
        }

        .textodecolor {
            color: darkblue;
            text-shadow: 4px 4px 4px #FFFFFF;
        }
        .suma{
            color: white;
            text-shadow: #000 4px 4px 4px ;
            background-color: #000;
        }
    </style>
    <title>Usuarios y Servicios</title>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4 textodecolor">Tabla de Usuarios y Servicios:</h1>
        <div class="mt-4 border p-3 textodecolor">
            <h1 class="p">Usuarios y Servicios</h1>

            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <tr>
                        <th>Concepto</th>
                        <th>Monto</th>
                    </tr>
                    <?php while ($fila = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $fila['Concepto']; ?></td>
                            <td><?php echo $fila['Monto']; ?> MXM mensuales</td>
                            <?php
                                // Sumar al total
                                $sumaTotal += $fila['Monto'];
                            ?>
                        </tr>
                    <?php endwhile; ?>
                </table>

                <!-- Mostrar la suma total -->
                <p class="font-weight-bold suma">Suma Total: <?php echo $sumaTotal; ?> MXM</p>
                
            <?php else: ?>
                <p>No hay datos</p>
            <?php endif; ?>
            <a href="usuarios.php" class="btn btn-danger mb-3">
                Regresar al menú
            </a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
