<?php
include "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["simular_pago"])) {
    // Obtener el usuario que inició sesión (ajusta según tu implementación de inicio de sesión)
    session_start();
    $usuarioID = $_SESSION["usuario_id"];  // Ajusta la clave de sesión según tu implementación

    // Calcular la suma total del monto nuevamente (puedes optimizar esto según tu estructura)
    $sql = "SELECT * FROM usuarios_servicios";
    $result = $con->query($sql);
    $sumaTotal = 0;

    while ($fila = $result->fetch_assoc()) {
        $sumaTotal += $fila['Monto'];
    }

    // Actualizar la tabla "privada" con el pago simulado
    $actualizarSql = "INSERT INTO pagos (usuario_id, saldo_abonado) VALUES (?, ?)";
    $actualizarStmt = $con->prepare($actualizarSql);

    if ($actualizarStmt) {
        $actualizarStmt->bind_param("id", $usuarioID, $sumaTotal);

        if ($actualizarStmt->execute()) {
            echo '<div class="container mt-4 alert alert-success">Pago simulado correctamente.</div>';
        } else {
            echo '<div class="container mt-4 alert alert-danger">Error al simular el pago: ' . $actualizarStmt->error . '</div>';
        }

        $actualizarStmt->close();
    } else {
        echo '<div class="container mt-4 alert alert-danger">Error en la preparación de la sentencia de actualización: ' . $con->error . '</div>';
    }
}
?>
