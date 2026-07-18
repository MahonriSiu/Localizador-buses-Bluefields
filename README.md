# Localizador de Buses - Bluefields (MiBus / SIGBU)

## Descripcion General
Aplicacion web que permite a los usuarios de Bluefields localizar en tiempo real
la ubicacion de los buses de transporte urbano, similar al funcionamiento de
apps como InDrive. El usuario puede ver por donde viene el bus, que ruta sigue,
y decidir si le conviene interceptarlo segun su destino, evitando asi esperar
parado sin saber si el bus ya paso o cuanto falta para que llegue.

El sistema cuenta con tres modulos, cada uno con su propio control de acceso:

- Vista Usuario: interfaz publica donde cualquier persona ve el mapa con
  la posicion en vivo de los buses y sus rutas, sin necesidad de registro.
- Vista Emisor: interfaz protegida con codigo de acceso simple, usada por
  el celular a bordo del bus para transmitir su ubicacion en tiempo real.
- Vista Administrador: panel protegido con autenticacion, de uso exclusivo
  del equipo organizador, con acceso a las 3 vistas del sistema.

## Tecnologias Utilizadas
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

Tecnologia: Geolocation API del navegador
Uso: Obtencion temporal de ubicacion GPS via celular

## Arquitectura del Proyecto (MVC)
El proyecto sigue el patron Modelo-Vista-Controlador (MVC), con separacion
completa de acceso entre los 3 roles del sistema.

Modelo: gestiona el acceso a la base de datos (buses, rutas, usuarios, emisores).
Vista: interfaces HTML y PHP que el usuario ve (mapa, panel admin, emisor).
Controlador: recibe las peticiones, consulta al Modelo, y entrega la respuesta a la Vista.

Estructura de carpetas del proyecto:

- app
  - controllers
    - BusController.php
    - EmisorController.php
    - AdminController.php
  - models
    - Bus.php
    - Ruta.php
    - Usuario.php
    - Emisor.php
  - views
    - usuario (mapa.php)
    - emisor (panel.php)
    - admin (panel.php)

- config
  - database.php
  - database.sql

- public
  - usuario (index.php)
  - emisor (login.php)
  - admin (login.php)
  - css
  - js
 
## Instalacion
1. Instalar XAMPP desde apachefriends.org (incluye PHP, MySQL y Apache).

2. Clonar este repositorio dentro de la carpeta htdocs de XAMPP:
   git clone https://github.com/MahonriSiu/Localizador-buses-Bluefields.git

3. Iniciar los servicios Apache y MySQL desde el panel de XAMPP.

4. Crear la base de datos desde phpMyAdmin (localhost/phpmyadmin)
   usando el script incluido en config/database.sql.

5. Configurar las credenciales de conexion en config/database.php.
   
## Ejecucion
Con Apache y MySQL corriendo, acceder desde el navegador a:

localhost/Localizador-buses-Bluefields/public/usuario/

## Roles del Sistema
Admin: acceso protegido con login (correo y contrasena). Gestiona rutas,
buses, emisores, y visualiza las 3 vistas del sistema desde su panel.

Emisor: acceso protegido con codigo de acceso propio asignado al bus.
Solo puede transmitir su ubicacion, sin otras funciones.

Usuario: acceso publico, sin registro. Solo puede consultar el mapa
en tiempo real.

Cada rol tiene su propia ruta de acceso independiente, evitando que un
usuario pueda acceder a las funciones de otro rol.

## Video de Navegacion
Enlace al video demostrativo (agregar link una vez grabado)

## Nota sobre cambios tecnicos
El proyecto inicio utilizando Firebase Realtime Database. Tras evaluar
la arquitectura, el equipo decidio migrar a un modelo MVC con PHP y
MySQL, buscando mayor control sobre la logica del backend y reforzar
las buenas practicas de separacion de responsabilidades en el codigo.

## Equipo
Proyecto desarrollado para Hackathon Nicaragua 2026, Categoria Aficionado.

Nombre tecnico del sistema: SIGBU (Sistema Inteligente de Gestion de Buses Urbanos)
Nombre comercial de la app: MiBus
 
