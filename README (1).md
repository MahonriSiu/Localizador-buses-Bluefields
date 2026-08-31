# Localizador de Buses - Bluefields (MiBus).

## Descripcion General
Aplicacion web que permite a los usuarios de Bluefields localizar en tiempo real la ubicacion de los buses de transporte urbano, similar al funcionamiento de apps como InDrive. El usuario puede ver por donde viene el bus, que ruta sigue, y decidir si le conviene interceptarlo segun su destino, evitando asi esperar parado sin saber si el bus ya paso o cuanto falta para que llegue.

El sistema cuenta con cinco modulos, cada uno con su propio control de acceso:

Vista Usuario: interfaz publica donde cualquier persona ve el mapa con la posicion en vivo de los buses y sus rutas. El registro con numero de telefono nicaraguense es obligatorio antes de poder ver el mapa.

Vista Emisor: interfaz protegida con codigo de acceso propio por bus, usada por el celular a bordo del bus para transmitir su ubicacion cada 5 segundos mientras esta en ruta.

Vista Propietario: panel protegido con correo y contrasena, de uso exclusivo del dueno de uno o varios buses. Ve unicamente sus propios buses y su ubicacion en un mapa disponible las 24 horas.

Vista Auditor: panel protegido con correo y contrasena. Consulta estadisticas de uso, la lista de usuarios finales registrados, y un mapa con todos los buses del sistema, sin restriccion de horario.

Vista Administrador: panel protegido con correo y contrasena, de uso exclusivo del equipo organizador. Crea cuentas, crea buses con su ruta y dueno, gestiona paradas y horario del sistema, y tiene acceso a las demas vistas internas.

## Tecnologias Utilizadas
<pre>
Tecnologia: HTML5 / CSS3 / JavaScript
Uso: Estructura y logica del frontend

Tecnologia: PHP 
Uso: Logica del backend, arquitectura MVC

Tecnologia: MySQL 
Uso: Base de datos relacional

Tecnologia: Leaflet.js 
Uso: Renderizado del mapa interactivo

Tecnologia: XAMPP 
Uso: Entorno de desarrollo local (Apache, MySQL y PHP)

Tecnologia: InfinityFree 
Uso: Hosting de produccion del sistema

Tecnologia: Geolocation API del navegador 
Uso: Obtencion de ubicacion GPS via celular

Tecnologia: Wake Lock API del navegador 
Uso: Evita que la pantalla del emisor se apague mientras transmite
</pre>

## Arquitectura del Proyecto (MVC)
El proyecto sigue el patron Modelo-Vista-Controlador (MVC), con separacion completa de acceso entre los 5 roles del sistema.

Modelo: gestiona el acceso a la base de datos (usuarios, buses, paradas, emisores, historial de posiciones, usuarios finales, solicitudes de reseteo). Vista: interfaces HTML y PHP que el usuario ve (mapa, paneles de cada rol). Controlador: recibe las peticiones, consulta al Modelo, y entrega la respuesta a la Vista.

<pre>
Localizador-buses-Bluefields
|
|---config
|    |---rutas.php
|    |---database.php
|    |---database.sql
|
|---app
|    |
|    |---controllers
|    |    |---AdminController.php
|    |    |---AuditorController.php
|    |    |---PropietarioController.php
|    |    |---EmisorController.php
|    |    |---BusController.php
|    |    |---PerfilController.php
|    |    |---ResenaController.php
|    |
|    |---models
|    |    |---Usuario.php
|    |    |---Bus.php
|    |    |---Parada.php
|    |    |---Emisor.php
|    |    |---Propietario.php
|    |    |---Configuracion.php
|    |    |---HistorialPosicion.php
|    |    |---RegistroAcceso.php
|    |    |---SolicitudReseteo.php
|    |    |---UsuarioFinal.php
|    |    |---Resena.php
|    |
|    |---views
|         |
|         |---usuario
|         |    |---mapa.php
|         |    |---registro.php
|         |
|         |---emisor
|         |    |---login.php
|         |    |---panel.php
|         |
|         |---admin
|         |    |---login.php
|         |    |---panel.php
|         |    |---gestion.php
|         |    |---cuentas.php
|         |    |---usuarios.php
|         |    |---mapa.php
|         |
|         |---auditor
|         |    |---login.php
|         |    |---panel.php
|         |    |---mapa.php
|         |    |---usuarios.php
|         |
|         |---propietario
|         |    |---login.php
|         |    |---panel.php
|         |    |---mapa.php
|         |
|         |---partials
|         |    |---encabezado_admin.php
|         |    |---encabezado_auditor.php
|         |    |---encabezado_propietario.php
|         |    |---perfil_widget.php
|         |
|         |---cambiar_contrasena.php
|
|---assets
|    |
|    |---css
|    |    |---estilos.css
|    |
|    |---js
|    |    |---admin.js
|    |    |---mapa.js
|    |    |---emisor.js
|    |    |---perfil.js
|    |    |---utilidades.js
|    |    |---tutorial.js
|    |
|    |---img
|         |---logo.png
|         |---perfiles
|
|---public
|    |
|    |---index.php
|    |---asset.php
|    |
|    |---usuario
|    |    |---index.php
|    |    |---registro.php
|    |
|    |---emisor
|    |    |---login.php
|    |    |---panel.php
|    |
|    |---admin
|    |    |---login.php
|    |    |---panel.php
|    |    |---gestion.php
|    |    |---cuentas.php
|    |    |---usuarios.php
|    |    |---mapa.php
|    |
|    |---auditor
|    |    |---login.php
|    |    |---panel.php
|    |    |---mapa.php
|    |    |---usuarios.php
|    |
|    |---propietario
|    |    |---login.php
|    |    |---panel.php
|    |    |---mapa.php
|    |
|    |---(endpoints AJAX sueltos: admin_*.php, auditor_*.php, propietario_*.php, emisor_*.php, perfil_*.php)
|
|---index.php
</pre>
 
## Instalacion
1. Instalar XAMPP desde apachefriends.org (incluye PHP, MySQL y Apache).

2. Clonar este repositorio dentro de la carpeta htdocs de XAMPP: git clone https://github.com/MahonriSiu/Localizador-buses-Bluefields.git

3. Iniciar los servicios Apache y MySQL desde el panel de XAMPP.

4. Crear la base de datos desde phpMyAdmin (localhost/phpmyadmin) usando el script incluido en config/database.sql.

5. Confirmar que config/rutas.php tenga la constante URL_BASE apuntando a la carpeta publica correcta. Es el unico archivo que se edita si el proyecto se mueve de carpeta o de servidor.

6. Crear la cuenta de administrador inicial: no existe una via automatica por diseno. Se sube temporalmente un script de un solo uso a config/ que inserta el primer admin con contrasena encriptada, se ejecuta una vez desde el navegador, y se borra del servidor de inmediato.
   
## Ejecucion
Con Apache y MySQL corriendo, acceder desde el navegador a:

localhost/Localizador-buses-Bluefields/public/index.php

En produccion (InfinityFree), el mismo sistema corre en:

mibus.infinityfreeapp.com

## Roles del Sistema
Admin: acceso protegido con login (correo y contrasena). Crea cuentas de los demas roles, crea buses con su ruta, descripcion y dueno, gestiona paradas y horario del sistema, resetea contrasenas, y consulta el codigo de acceso del emisor de cualquier bus.

Auditor: acceso protegido con login propio. Consulta estadisticas de uso, la lista completa de usuarios finales registrados (nombre y telefono), y un mapa con todos los buses del sistema, 24 horas al dia.

Propietario: acceso protegido con login propio. Ve unicamente los buses que le pertenecen, su estado y su ubicacion en un mapa disponible las 24 horas, sin restriccion del horario publico.

Emisor: acceso protegido con codigo de acceso propio asignado al bus. Solo puede transmitir su ubicacion GPS cada 5 segundos, sin otras funciones. El administrador puede consultar o regenerar ese codigo si se pierde.

Usuario: acceso publico mediante registro obligatorio con numero de telefono nicaraguense. Consulta el mapa en tiempo real solo dentro del horario configurado por el administrador, y recibe avisos cuando el bus esta cerca de su parada.

Cada rol tiene su propia ruta de acceso independiente, evitando que un usuario pueda acceder a las funciones de otro rol. Toda cuenta creada por el administrador (Propietario o Auditor) inicia con la contrasena admin123 y debe cambiarla en su primer ingreso..

## Video de navegacion.
https://youtube.com/shorts/osvL0CZWvUc?si=6ewywdJdy3T3W34E
## Equipo
Proyecto desarrollado para Hackathon Nicaragua 2026, Categoria Aficionado.

Nombre del proyecto: MiBus

Equipo DesingX:

Justo Mahonri Siu Manzanarez - Desarrollo
Brinelly Briann Hammond Hodgson - Marketing
Deylan Miguel Britton Saenz - Diseno
