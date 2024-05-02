<?php
include "../conexion.php";

$sql = "SELECT * FROM usuarios_servicios";
$result = $con->query($sql);

// Procesar el pago de un servicio
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_servicio"])) {
    $eliminarServicioID = $_POST["eliminar_servicio_id"];

    // Obtener el monto del servicio
    $consultaMonto = "SELECT Monto FROM usuarios_servicios WHERE ID = $eliminarServicioID";
    $resultadoMonto = $con->query($consultaMonto);

    if ($resultadoMonto->num_rows > 0) {
        $filaMonto = $resultadoMonto->fetch_assoc();
        $montoServicio = $filaMonto['Monto'];

        // Consulta SQL para restar el monto del servicio pagado
        $actualizarSQL = "UPDATE usuarios_servicios SET Monto = Monto - $montoServicio WHERE ID = $eliminarServicioID";

        if ($con->query($actualizarSQL) === TRUE) {
            // Verificar si el monto es igual o menor a cero y eliminar el servicio
            $consultaSaldo = "SELECT Monto FROM usuarios_servicios WHERE ID = $eliminarServicioID";
            $resultadoSaldo = $con->query($consultaSaldo);

            if ($resultadoSaldo->num_rows > 0) {
                $filaSaldo = $resultadoSaldo->fetch_assoc();
                $saldoActual = $filaSaldo['Monto'];

                if ($saldoActual <= 0) {
                    // Eliminar el servicio si el saldo es cero o negativo
                    $eliminarSQL = "DELETE FROM usuarios_servicios WHERE ID = $eliminarServicioID";
                    if ($con->query($eliminarSQL) === TRUE) {
                        echo "Servicio pagado y eliminado correctamente.";
                    } else {
                        echo "Error al eliminar el servicio: " . $con->error;
                    }
                } else {
                    echo "Servicio pagado correctamente.";
                }
            } else {
                echo "Error al verificar el saldo del servicio: " . $con->error;
            }
        } else {
            echo "Error al pagar el servicio: " . $con->error;
        }
    } else {
        echo "Error al obtener el monto del servicio: " . $con->error;
    }
}
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
                        <th>Acciones</th>
                    </tr>
                    <?php while ($fila = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $fila['Concepto']; ?></td>
                            <td><?php echo $fila['Monto']; ?> MXM mensuales</td>
                            <!-- Botones para pagar -->
                            <td>
                                <form id="eliminar_servicio_form_<?php echo $fila['ID']; ?>" method="POST" action="pagos.php">
                                    <input for="monto" type="hidden" name="eliminar_servicio_id" value="<?php echo $fila['ID']; ?>">
                                    <button type="submit" name="eliminar_servicio" class="btn btn-success btn-sm">Pagar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No hay datos</p>
            <?php endif; ?>
            <a href="usuarios.php" class="btn btn-success mb-3">
                Regresar al menú
            </a>
        </div>
    </div>
</body>
</html>
