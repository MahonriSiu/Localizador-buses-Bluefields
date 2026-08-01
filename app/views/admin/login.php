<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Acceso Administrador</title>

    <link rel="stylesheet" href="/Localizador-buses-Bluefields/public/asset.php?tipo=css&archivo=estilos.css" />
</head>
<body class="pagina-login">

    <div class="caja-login">
        <h2>MiBus - Administrador</h2>
        <input type="email" id="correo" placeholder="Correo">
        <input type="password" id="contrasena" placeholder="Contrasena">
        <button onclick="iniciarSesion()">Ingresar</button>
        <p id="mensaje" class="texto-error"></p>
    </div>

    <script>
        function iniciarSesion() {
            const correo = document.getElementById('correo').value;
            const contrasena = document.getElementById('contrasena').value;

            const datos = new FormData();
            datos.append('correo', correo);
            datos.append('contrasena', contrasena);

            fetch('/Localizador-buses-Bluefields/public/admin_login.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                if (resultado.exito) {
                    window.location.href = '/Localizador-buses-Bluefields/public/admin/panel.php';
                } else {
                    document.getElementById('mensaje').innerText = resultado.mensaje;
                }
            });
        }
    </script>

</body>
</html>