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
            color: white;
            text-shadow: 4px 4px 4px #000;
        }
    </style>
    <title>Document</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <form class="textodecolor" action="../procesar pago.php" method="post">
            <label for="monto">Monto a pagar:</label>
            <input type="text" name="monto" id="monto" required>
            <input type="submit" class="btn btn-success" value="Realizar pago">
            <a href="bit_pagos.php" class="btn btn-danger">Volver</a>
        </form>
    </div>
</body>
</html>