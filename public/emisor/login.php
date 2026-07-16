<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Acceso Emisor</title>

    <link rel="stylesheet" href="../css/estilos.css" />

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        #mensaje {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="caja-login">
        <h2>MiBus - Emisor</h2>
        <p>Ingrese su codigo de acceso</p>
        <input type="text" id="codigo" placeholder="Codigo de acceso">
        <button onclick="iniciarSesion()">Ingresar</button>
        <p id="mensaje"></p>
    </div>

    <script>
        function iniciarSesion() {
            const codigo = document.getElementById('codigo').value;

            const datos = new FormData();
            datos.append('codigo', codigo);

            fetch('../emisor_login.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                if (resultado.exito) {
                    window.location.href = 'panel.php';
                } else {
                    document.getElementById('mensaje').innerText = 'Codigo invalido';
                }
            });
        }
    </script>

</body>
</html>