<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiBus - Acceso Emisor</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .caja-login {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            width: 300px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #1565C0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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