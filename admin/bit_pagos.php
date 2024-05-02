<?php
include "../conexion.php";

// Verificar la conexión a la base de datos
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

// Realizar consulta para obtener datos de los pagos y usuarios
$sql = "SELECT pagos.ID, usuarios.Usuario as nombre, pagos.monto FROM pagos INNER JOIN usuarios ON pagos.usuario_id = usuarios.ID";
$result = $con->query($sql);

// Verificar si la consulta se ejecutó correctamente
if (!$result) {
    die("Error en la consulta: " . $con->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Visualizar Pagos</title>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Tabla de Pagos</h1>

        <!-- Mostrar datos de la tabla de pagos -->
        <div class="mt-4 border p-3">
            <h2 class="mb-3">Pagos</h2>

            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Saldo Abonado</th>
                        <th>Por Pagar</th>
                    </tr>

                    <?php while ($fila = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $fila['ID']; ?></td>
                            <td><?php echo $fila['nombre']; ?></td>
                            <td>$<?php echo $fila['monto']; ?></td>
                            <td>$<?php echo calcularPorPagar($fila['ID']); ?></td>
                        </tr>
                    <?php endwhile; ?>

                </table>
            <?php else: ?>
                <p>No hay datos en la tabla de pagos.</p>
            <?php endif; ?>
            <a href="admin.php" class="btn btn-success">Regresar al menú principal</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Función para calcular el monto por pagar
function calcularPorPagar($pagoID) {
    // Lógica para calcular el monto por pagar según tus requerimientos
    // Puedes hacer una nueva consulta SQL o utilizar datos existentes en tu aplicación
    // En este ejemplo, simplemente se devuelve un valor fijo de $500 para fines de demostración
    return 510.00;
}
?>