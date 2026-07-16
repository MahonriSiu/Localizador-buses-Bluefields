<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Acceso Administrador</title>

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
        <h2>MiBus - Administrador</h2>
        <input type="email" id="correo" placeholder="Correo">
        <input type="password" id="contrasena" placeholder="Contrasena">
        <button onclick="iniciarSesion()">Ingresar</button>
        <p id="mensaje"></p>
    </div>

    <script>
        function iniciarSesion() {
            const correo = document.getElementById('correo').value;
            const contrasena = document.getElementById('contrasena').value;

            const datos = new FormData();
            datos.append('correo', correo);
            datos.append('contrasena', contrasena);

            fetch('../admin_login.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                if (resultado.exito) {
                    window.location.href = 'panel.php';
                } else {
                    document.getElementById('mensaje').innerText = resultado.mensaje;
                }
            });
        }
    </script>

</body>
</html>