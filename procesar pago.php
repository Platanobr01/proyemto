<?php
// Conectar a la base de datos (ajusta los detalles de conexión según tu entorno)
$conexion = new mysqli("localhost", "root", "", "privada");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si el formulario se ha enviado y el campo "monto" está presente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["monto"])) {
    // Obtener el monto del formulario
    $monto = $_POST['monto'];

    // Obtener el ID de usuario (puedes obtenerlo de la sesión o cualquier otra fuente)
    $usuario_id = 1; // Ejemplo: usuario con ID 1

    // Verificar si ya existe un registro para el usuario en la tabla de pagos
    $consultaExistencia = "SELECT * FROM pagos WHERE usuario_id = '$usuario_id'";
    $resultadoExistencia = $conexion->query($consultaExistencia);

    if ($resultadoExistencia->num_rows > 0) {
        // Si ya existe, actualizar el monto existente
        $filaExistencia = $resultadoExistencia->fetch_assoc();
        $nuevoMonto = $filaExistencia['monto'] + $monto;

        $actualizarPago = "UPDATE pagos SET monto = '$nuevoMonto' WHERE usuario_id = '$usuario_id'";

        if ($conexion->query($actualizarPago) === TRUE) {
            echo "Pago actualizado correctamente";
        } else {
            echo "Error al actualizar el pago: " . $conexion->error;
        }
    } else {
        // Si no existe, insertar un nuevo registro
        $insertarPago = "INSERT INTO pagos (usuario_id, monto) VALUES ('$usuario_id', '$monto')";

        if ($conexion->query($insertarPago) === TRUE) {
            echo "Pago realizado correctamente";
        } else {
            echo "Error al procesar el pago: " . $conexion->error;
        }
    }
} else {
    echo "Error: Datos del formulario incompletos.";
}

// Cerrar la conexión
$conexion->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="usuario/usuarios.php" class="btn btn-danger">Regresar al menu principal</a>
</body>
</html>