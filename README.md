# Localizador de Buses.

## Descripción General
Aplicación web que permite a los usuarios de Bluefields localizar en tiempo real 
la ubicación de los buses de transporte urbano, similar al funcionamiento de 
apps como InDrive. El usuario puede ver por dónde viene el bus, qué ruta sigue, 
y decidir si le conviene subirse según su destino, evitando así esperar 
parado sin saber si el bus ya pasó o cuánto falta para que llegue.

El sistema cuenta con tres módulos:

- **Vista Usuario**: interfaz pública donde cualquier persona ve el mapa con 
  la posición en vivo de los buses y sus rutas.
- **Vista Emisor**: interfaz simplificada usada por el celular a bordo del bus, 
  que transmite su ubicación en tiempo real.
- **Vista Administrador**: panel de gestión de rutas y buses activos, de uso 
  exclusivo del equipo organizador.

## Tecnologías Utilizadas

| Tecnología | Uso |
|-----|
| HTML5 / CSS3 / JavaScript (Vanilla) | Estructura y lógica de la aplicación |
| Leaflet.js | Renderizado del mapa interactivo |
| Firebase Realtime Database | Almacenamiento y sincronización en tiempo real |
| Firebase Authentication | Gestión de roles (Admin, Usuario, Auditor) |
| Firebase Hosting | Despliegue de la aplicación |
| Geolocation API (navegador) | Obtención temporal de ubicación GPS vía celular |

## Estructura del Proyecto
proyecto/
|-- index.html
|-- css/
|   '-- style.css
|-- js/
|   |-- firebase-config.js
|   |-- usuario.js
|   |-- emisor.js
|   '-- admin.js
|
'-- README.md

## Instalación
1. Clonar el repositorio: https://github.com/MahonriSiu/Localizador-buses-Bluefields.git
cd Localizador-buses-Bluefields
2. Crear un proyecto en Firebase Console (console.firebase.google.com) 
   y habilitar Realtime Database y Authentication.
3. Copiar las credenciales del proyecto de Firebase y pegarlas en 
   'js/firebase-config.js'.
4. No requiere instalación de dependencias adicionales.

## Ejecución
**Opción 1 - Local:**
Abrir el archivo `index.html` directamente en el navegador, o servirlo con 
Live Server (extensión de VS Code) para evitar restricciones de CORS con 
Firebase.

**Opción 2 - Desplegado:**
Acceder directamente vía Firebase Hosting: `https://proyecto.web.app`

## Roles del Sistema
- **Admin**: gestiona rutas, buses activos y usuarios (acceso a `admin.html`)
- **Usuario**: consulta el mapa en tiempo real (acceso público a `index.html`)
- **Auditor**: revisa historial de recorridos y reportes (acceso restringido)

## Video de Navegación
Enlace al video demostrativo *(agregar link una vez grabado)*
