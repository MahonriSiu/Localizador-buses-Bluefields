# 🚌 MiBus — Sistema Inteligente de Gestión y Localización de Buses Urbanos

> **Una plataforma digital para mejorar el acceso a la información, localización y gestión del transporte urbano en Bluefields, Nicaragua.**

![PHP](https://img.shields.io/badge/PHP-Backend-777BB4?logo=php\&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Base%20de%20datos-4479A1?logo=mysql\&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Frontend-F7DF1E?logo=javascript\&logoColor=black)
![Leaflet](https://img.shields.io/badge/Leaflet-Mapas-199900?logo=leaflet\&logoColor=white)
![MVC](https://img.shields.io/badge/Arquitectura-MVC-blue)
![Estado](https://img.shields.io/badge/Estado-En%20desarrollo-yellow)

---

# 📖 Descripción General

**MiBus**, es una plataforma web desarrollada para contribuir a la modernización y digitalización de la información relacionada con el transporte urbano en **Bluefields, Nicaragua**.

El sistema permite visualizar información relacionada con las unidades de transporte mediante mapas interactivos, gestionar buses, rutas, paradas y otros recursos administrativos, así como transmitir y consultar información de ubicación desde diferentes módulos del sistema.

La propuesta surge como respuesta a una problemática frecuente para los usuarios del transporte urbano: la falta de información sobre la ubicación de los buses y la incertidumbre al momento de esperar una unidad.

MiBus busca proporcionar una base tecnológica que permita a los usuarios acceder a información útil sobre el transporte, mientras que los administradores, propietarios, emisores y auditores disponen de módulos específicos según sus responsabilidades.

Además de la localización de buses, la plataforma ha evolucionado incorporando funcionalidades relacionadas con:

* 🚌 Gestión de buses.
* 📍 Actualización y consulta de ubicación.
* 🗺️ Visualización mediante mapas.
* 🚏 Gestión y consulta de paradas.
* ⏱️ Estimación de información relacionada con recorridos.
* 👤 Gestión de usuarios y perfiles.
* 📡 Módulo de emisión de ubicación.
* 🚌 Paneles para propietarios.
* 🔎 Módulos de auditoría.
* ⭐ Sistema de reseñas.
* 📢 Anuncios locales.
* 📅 Eventos.
* 🎨 Ruta Creativa y puntos creativos.
* 🔐 Gestión de accesos y contraseñas.
* 📜 Historial de posiciones y accesos.

---

# 🎯 Objetivo General

Diseñar y desarrollar una plataforma digital para la gestión y localización de buses urbanos en Bluefields, utilizando tecnologías web, bases de datos y herramientas de geolocalización, con el propósito de facilitar el acceso a información relacionada con la movilidad urbana y mejorar la organización de los diferentes actores vinculados al sistema.

---

# 🚀 Características Principales

* 🗺️ Visualización de información mediante mapas interactivos.
* 🚌 Gestión de unidades de transporte.
* 📍 Actualización de ubicación desde dispositivos compatibles.
* 🚏 Gestión y consulta de paradas.
* 📡 Módulo de emisión de ubicación.
* 👤 Interfaz para usuarios finales.
* 🛠️ Panel de administración.
* 🚌 Panel para propietarios.
* 🔎 Panel de auditoría.
* ⭐ Sistema de reseñas.
* 📢 Gestión de anuncios locales.
* 📅 Gestión de eventos.
* 🎨 Ruta Creativa y puntos creativos.
* 📜 Historial de posiciones.
* 🔐 Gestión de cuentas y accesos.
* 🔑 Recuperación y cambio de contraseñas.
* 👤 Gestión de perfiles.
* 📱 Interfaces orientadas a diferentes tipos de usuarios.
* 🗄️ Gestión centralizada mediante base de datos MySQL.

---

# 🛠️ Tecnologías Utilizadas

| Tecnología         | Uso                                                   |
| ------------------ | ----------------------------------------------------- |
| 🌐 HTML5           | Estructura de las interfaces                          |
| 🎨 CSS3            | Diseño y presentación visual                          |
| ⚡ JavaScript       | Interactividad y funcionalidades del cliente          |
| 🐘 PHP             | Lógica del backend y procesamiento del sistema        |
| 🗄️ MySQL          | Base de datos relacional                              |
| 🗺️ Leaflet.js     | Visualización de mapas interactivos                   |
| 📍 Geolocation API | Obtención de ubicación desde dispositivos compatibles |
| 🧩 MVC             | Organización y separación de responsabilidades        |
| 🖥️ XAMPP          | Entorno de desarrollo local                           |
| 🔧 Git y GitHub    | Control de versiones y alojamiento del código         |

---

# 🏗️ Arquitectura del Proyecto

El proyecto sigue una organización basada en el patrón **Modelo–Vista–Controlador (MVC)**.

Esta arquitectura permite separar las responsabilidades principales de la aplicación:

### 🧩 Modelo

Los modelos gestionan la información y las operaciones relacionadas con la base de datos.

Entre los datos gestionados se encuentran:

* Buses.
* Usuarios.
* Usuarios finales.
* Emisores.
* Propietarios.
* Paradas.
* Historial de posiciones.
* Eventos.
* Anuncios.
* Reseñas.
* Configuraciones.
* Registros de acceso.
* Solicitudes de reseteo.
* Puntos creativos.

### 🖥️ Vista

Las vistas representan las interfaces disponibles para los diferentes tipos de usuarios del sistema.

Actualmente existen interfaces destinadas a:

* Usuario.
* Emisor.
* Administrador.
* Propietario.
* Auditor.

### ⚙️ Controlador

Los controladores reciben las solicitudes realizadas desde las interfaces, coordinan la lógica correspondiente y utilizan los modelos necesarios para procesar o consultar la información.

---

## Arquitectura funcional

```text
┌───────────────────────────────────────────────┐
│                   USUARIOS                    │
│                                               │
│ Usuario • Emisor • Propietario                │
│ Administrador • Auditor                       │
└───────────────────────┬───────────────────────┘
                        │
                        ▼
┌───────────────────────────────────────────────┐
│                    VISTAS                     │
│                                               │
│ Interfaces específicas según el rol           │
│                                               │
│ Usuario • Emisor • Propietario                │
│ Administrador • Auditor                       │
└───────────────────────┬───────────────────────┘
                        │
                        ▼
┌───────────────────────────────────────────────┐
│                 CONTROLADORES                 │
│                                               │
│ Procesamiento de solicitudes y                │
│ coordinación de la lógica del sistema         │
│                                               │
│ Buses • Emisores • Propietarios               │
│ Eventos • Anuncios • Perfiles                 │
│ Auditoría • Reseñas • Ruta Creativa           │
│ Administración                                │
└───────────────────────┬───────────────────────┘
                        │
                        ▼
┌───────────────────────────────────────────────┐
│                    MODELOS                    │
│                                               │
│ Gestión y operaciones relacionadas con        │
│ la información del sistema                    │
│                                               │
│ Buses • Usuarios • Paradas                    │
│ Posiciones • Eventos • Anuncios               │
│ Reseñas • Configuración • Accesos             │
└───────────────────────┬───────────────────────┘
                        │
                        ▼
┌───────────────────────────────────────────────┐
│                 BASE DE DATOS                 │
│                                               │
│                     MySQL                     │
└───────────────────────────────────────────────┘
```

---

# 📁 Estructura del Proyecto

La estructura actual del sistema se encuentra organizada para separar la lógica de negocio, los modelos, las interfaces, los recursos visuales, la configuración y los diferentes puntos de acceso.

```text
/
│
├── index.php
│   └── Punto de acceso principal de la aplicación.
│
├── README.md
│   └── Documentación general del proyecto.
│
├── app/
│   │
│   ├── controllers/
│   │   ├── AdminController.php
│   │   ├── AnuncioController.php
│   │   ├── AuditorController.php
│   │   ├── BusController.php
│   │   ├── EmisorController.php
│   │   ├── EventoController.php
│   │   ├── PerfilController.php
│   │   ├── PropietarioController.php
│   │   ├── ResenaController.php
│   │   └── RutaCreativaController.php
│   │
│   │   └── Controladores encargados de procesar
│   │       las solicitudes y coordinar la lógica
│   │       de los diferentes módulos.
│   │
│   ├── models/
│   │   ├── AnuncioLocal.php
│   │   ├── Bus.php
│   │   ├── Configuracion.php
│   │   ├── Emisor.php
│   │   ├── Evento.php
│   │   ├── HistorialPosicion.php
│   │   ├── Parada.php
│   │   ├── Propietario.php
│   │   ├── PuntoCreativo.php
│   │   ├── RegistroAcceso.php
│   │   ├── Resena.php
│   │   ├── SolicitudReseteo.php
│   │   ├── Usuario.php
│   │   └── UsuarioFinal.php
│   │
│   │   └── Modelos encargados de gestionar
│   │       la información y operaciones
│   │       relacionadas con la base de datos.
│   │
│   └── views/
│       ├── admin/
│       │   └── Interfaces del módulo administrativo.
│       │
│       ├── auditor/
│       │   └── Interfaces destinadas a auditoría
│       │       y consulta de información.
│       │
│       ├── emisor/
│       │   └── Interfaces para la emisión
│       │       y actualización de ubicación.
│       │
│       ├── propietario/
│       │   └── Interfaces destinadas a propietarios.
│       │
│       ├── usuario/
│       │   └── Interfaces para usuarios finales.
│       │
│       ├── partials/
│       │   └── Componentes reutilizables.
│       │
│       └── cambiar_contrasena.php
│
├── assets/
│   │
│   ├── css/
│   │   └── estilos.css
│   │       └── Estilos generales de la plataforma.
│   │
│   ├── img/
│   │   ├── anuncios/
│   │   ├── perfiles/
│   │   └── ruta-creativa/
│   │
│   │   └── Recursos gráficos organizados
│   │       según los módulos del sistema.
│   │
│   └── js/
│       ├── admin.js
│       ├── anuncios.js
│       ├── emisor.js
│       ├── mapa.js
│       ├── perfil.js
│       ├── tutorial.js
│       └── utilidades.js
│
├── config/
│   │
│   ├── database.php
│   │   └── Configuración de conexión
│   │       con la base de datos.
│   │
│   ├── database.sql
│   │   └── Estructura y configuración
│   │       inicial de la base de datos.
│   │
│   └── rutas.php
│       └── Configuración de rutas internas.
│
└── public/
    │
    ├── admin/
    │   ├── panel.php
    │   ├── mapa.php
    │   ├── gestion.php
    │   ├── usuarios.php
    │   ├── cuentas.php
    │   ├── anuncios.php
    │   ├── resenas.php
    │   ├── ruta-creativa.php
    │   └── trazar-ruta.php
    │
    ├── auditor/
    │   ├── panel.php
    │   ├── mapa.php
    │   ├── usuarios.php
    │   └── resenas.php
    │
    ├── emisor/
    │   ├── login.php
    │   └── panel.php
    │
    ├── propietario/
    │   ├── login.php
    │   ├── panel.php
    │   └── mapa.php
    │
    ├── usuario/
    │   ├── index.php
    │   └── registro.php
    │
    └── Endpoints y archivos de procesamiento
        ├── Localización y GPS
        ├── Buses y recorridos
        ├── Paradas
        ├── Estimaciones
        ├── Autenticación
        ├── Perfiles
        ├── Administración
        ├── Auditoría
        ├── Anuncios
        ├── Eventos
        ├── Reseñas
        ├── Ruta Creativa
        └── Gestión de accesos
```

> La estructura está diseñada para mantener separadas las responsabilidades del sistema y facilitar el mantenimiento, la ampliación de funcionalidades y la organización del código.

---

# 👥 Roles del Sistema

MiBus cuenta actualmente con diferentes módulos de acceso según las responsabilidades de cada tipo de usuario.

## 🧑 Usuario

El módulo de usuario está orientado al público y a los usuarios finales de la plataforma.

Entre sus funciones se encuentran la consulta de información disponible, el acceso a mapas y otras funcionalidades públicas o destinadas al usuario final.

---

## 📡 Emisor

El emisor utiliza un módulo específico destinado a la transmisión o actualización de información relacionada con la ubicación.

Su función principal consiste en:

* Acceder mediante las credenciales o mecanismos asignados.
* Actualizar la información de ubicación.
* Transmitir datos relacionados con la unidad correspondiente.

---

## 🚌 Propietario

El propietario cuenta con interfaces destinadas a la consulta y gestión de información relacionada con las unidades asociadas a su acceso.

El sistema incluye:

* Panel de propietario.
* Visualización de información.
* Consulta mediante mapas.

---

## 🛠️ Administrador

El administrador cuenta con el módulo de gestión más amplio dentro de la plataforma.

Entre sus funcionalidades se encuentran módulos relacionados con:

* Gestión de buses.
* Gestión de cuentas.
* Gestión de usuarios.
* Visualización de mapas.
* Gestión de paradas.
* Gestión de eventos.
* Gestión de anuncios.
* Gestión de reseñas.
* Ruta Creativa.
* Gestión de puntos creativos.
* Configuración.
* Horarios.
* Solicitudes.
* Historiales y registros.
* Gestión de emisores.

---

## 🔎 Auditor

El auditor cuenta con un módulo destinado a la consulta y supervisión de información disponible dentro del sistema.

Entre las áreas disponibles se encuentran:

* Panel de auditoría.
* Visualización de mapas.
* Consulta de usuarios.
* Consulta de reseñas.
* Historiales y registros disponibles según el sistema.

---

# 📡 Flujo General de Localización

La funcionalidad de localización puede representarse conceptualmente mediante el siguiente flujo:

```text
┌───────────────────────┐
│  DISPOSITIVO EMISOR   │
│                       │
│   📍 Ubicación GPS    │
└───────────┬───────────┘
            │
            ▼
┌───────────────────────┐
│ ACTUALIZACIÓN DE DATOS│
│                       │
│    gps_actualizar     │
└───────────┬───────────┘
            │
            ▼
┌───────────────────────┐
│      BACKEND PHP      │
│                       │
│ Procesamiento de la   │
│ información recibida  │
└───────────┬───────────┘
            │
            ▼
┌───────────────────────┐
│    BASE DE DATOS      │
│                       │
│       MySQL           │
└───────────┬───────────┘
            │
            ▼
┌───────────────────────┐
│   PLATAFORMA MiBus    │
│                       │
│ 🗺️ Mapas y consultas │
└───────────────────────┘
```

---

# 🗺️ Módulos Principales

## 🚌 Gestión de Buses

El sistema incorpora funcionalidades relacionadas con:

* Registro de buses.
* Consulta de unidades.
* Visualización en mapas.
* Activación o desactivación.
* Gestión desde el panel administrativo.

---

## 📍 Localización y Posiciones

La plataforma cuenta con archivos y modelos destinados a:

* Actualización de ubicación.
* Consulta de buses.
* Historial de posiciones.
* Recorridos.
* Estimaciones.
* Visualización mediante mapas.

---

## 🚏 Paradas

El sistema incorpora funcionalidades para la gestión y consulta de paradas.

Entre las operaciones disponibles se encuentran:

* Agregar paradas.
* Consultar paradas.
* Eliminar paradas.
* Visualizar información relacionada.

---

## 📢 Anuncios Locales

MiBus incluye un módulo de anuncios que permite:

* Crear anuncios.
* Consultar anuncios.
* Activar o desactivar publicaciones.
* Eliminar anuncios.
* Gestionar contenido relacionado.

---

## 📅 Eventos

El módulo de eventos permite gestionar información relacionada con eventos disponibles dentro de la plataforma.

Incluye funcionalidades como:

* Crear eventos.
* Consultar eventos.
* Actualizar eventos.
* Activar o desactivar eventos.
* Eliminar eventos.
* Consultar eventos activos.

---

## ⭐ Reseñas

El sistema incluye un módulo para:

* Enviar reseñas.
* Consultar reseñas.
* Visualizar información desde módulos administrativos y de auditoría.

---

## 🎨 Ruta Creativa

La plataforma incorpora una funcionalidad adicional denominada **Ruta Creativa**, acompañada de un sistema de gestión de puntos creativos.

Entre sus funcionalidades se encuentran:

* Crear puntos creativos.
* Consultar puntos.
* Actualizar información.
* Eliminar puntos.
* Subir imágenes.
* Gestionar imágenes.
* Consultar rutas relacionadas.

---

## 👤 Perfiles y Cuentas

El sistema incorpora funcionalidades relacionadas con:

* Consulta de perfil.
* Actualización de nombre.
* Cambio de contraseña.
* Subida de fotografía de perfil.
* Gestión de cuentas.
* Recuperación de acceso.

---

# ⚙️ Instalación

## Requisitos

Para ejecutar MiBus en un entorno local se requiere:

* Apache.
* PHP.
* MySQL o MariaDB.
* Un navegador web moderno.

Para desarrollo local puede utilizarse **XAMPP**.

---

## 1. Obtener el proyecto

Clonar o descargar el repositorio:

```bash
git clone https://github.com/MahonriSiu/Localizador-buses-Bluefields.git
```

---

## 2. Colocar los archivos correctamente

La estructura utilizada por el proyecto requiere que los archivos y carpetas principales queden directamente dentro del directorio público configurado para la aplicación.

Por ejemplo, en XAMPP:

```text
xampp/
└── htdocs/
    ├── index.php
    ├── README.md
    │
    ├── app/
    ├── assets/
    ├── config/
    └── public/
```

> El contenido principal del proyecto debe conservar su estructura interna. No debe añadirse una carpeta adicional que modifique las rutas relativas utilizadas por la aplicación.

---

## 3. Configurar la Base de Datos

Importar el archivo:

```text
config/database.sql
```

Posteriormente, verificar las credenciales y la configuración de conexión en:

```text
config/database.php
```

---

## 4. Iniciar los Servicios

Desde XAMPP:

1. Iniciar **Apache**.
2. Iniciar **MySQL**.
3. Verificar la conexión con la base de datos.

---

# ▶️ Ejecución

Con Apache y MySQL en funcionamiento, acceder al dominio o servidor local configurado para el proyecto.

La estructura principal de acceso se encuentra organizada mediante:

```text
/
│
├── index.php
│
└── public/
    ├── usuario/
    ├── emisor/
    ├── propietario/
    ├── auditor/
    └── admin/
```

Cada módulo cuenta con puntos de acceso independientes según el tipo de usuario.

---

# 🌐 Despliegue en Hosting

Para desplegar MiBus en un hosting compatible con PHP y MySQL, se debe subir directamente el contenido principal del proyecto al directorio público proporcionado por el servicio de hosting.

La estructura debe conservarse:

```text
Directorio público/
│
├── index.php
├── app/
├── assets/
├── config/
└── public/
```

> No se debe agregar una carpeta adicional como `Localizador-buses-Bluefields` dentro del directorio público si esto modifica las rutas configuradas por la aplicación.

Posteriormente se debe:

1. Crear la base de datos desde el panel del hosting.
2. Importar `config/database.sql`.
3. Configurar las credenciales correspondientes en `config/database.php`.
4. Verificar las rutas utilizadas por el sistema.
5. Comprobar el funcionamiento de los módulos principales.

---

# 🧪 Pruebas

El proyecto contempla pruebas relacionadas con:

* Funcionamiento de las interfaces.
* Autenticación de usuarios.
* Separación de accesos por rol.
* Gestión de buses.
* Visualización de mapas.
* Obtención y actualización de ubicación.
* Consulta de paradas.
* Consulta de recorridos.
* Estimaciones.
* Gestión de anuncios.
* Gestión de eventos.
* Sistema de reseñas.
* Ruta Creativa.
* Gestión de perfiles.
* Cambio y recuperación de contraseñas.
* Registro de información.
* Funcionamiento de la base de datos.
* Compatibilidad con dispositivos móviles.

---

# 🔄 Evolución Técnica del Proyecto

El proyecto inició utilizando **Firebase Realtime Database** como solución para la gestión de información en tiempo real.

Posteriormente, tras evaluar la arquitectura y las necesidades del sistema, se realizó una migración hacia una estructura basada en:

* PHP.
* MySQL.
* Arquitectura MVC.
* Controladores y modelos separados.
* Módulos organizados según los diferentes roles del sistema.

Esta evolución permitió contar con una mayor separación de responsabilidades dentro del código y ampliar progresivamente las funcionalidades de la plataforma.

---

# 🗺️ Roadmap

* [x] Definición de la problemática.
* [x] Diseño inicial de la propuesta.
* [x] Desarrollo del prototipo inicial.
* [x] Implementación de arquitectura MVC.
* [x] Integración de PHP y MySQL.
* [x] Implementación de gestión de buses.
* [x] Implementación de mapas.
* [x] Módulo de emisor.
* [x] Módulo de administración.
* [x] Módulo de usuario.
* [x] Módulo de propietario.
* [x] Módulo de auditoría.
* [x] Gestión de paradas.
* [x] Historial de posiciones.
* [x] Sistema de reseñas.
* [x] Gestión de anuncios.
* [x] Gestión de eventos.
* [x] Ruta Creativa.
* [x] Gestión de perfiles y cuentas.
* [ ] Pruebas ampliadas con usuarios.
* [ ] Optimización de rendimiento.
* [ ] Validación de funcionamiento en condiciones reales.
* [ ] Mejoras en la actualización de ubicación.
* [ ] Ampliación de funcionalidades.
* [ ] Evaluación para implementación piloto.
* [ ] Escalabilidad hacia otros municipios.

---

# 🌎 Impacto Esperado

## 🚌 Movilidad

* Facilitar el acceso a información relacionada con el transporte urbano.
* Reducir la incertidumbre de los usuarios al esperar una unidad.
* Facilitar la consulta de información mediante herramientas digitales.
* Contribuir a la modernización progresiva del transporte.

## 💻 Tecnológico

* Aplicación de tecnologías web.
* Integración de bases de datos.
* Uso de mapas interactivos.
* Aplicación de conceptos de geolocalización.
* Desarrollo mediante arquitectura MVC.
* Creación de una plataforma modular y escalable.

## 🏙️ Local

* Desarrollo de una solución enfocada inicialmente en Bluefields.
* Digitalización de información relacionada con la movilidad urbana.
* Posibilidad de evolución hacia nuevas funcionalidades.
* Base tecnológica adaptable a otros contextos o municipios.

---

# 🎥 Video de Navegación

El proyecto contempla la incorporación de un video demostrativo donde se presentará la navegación general de MiBus y sus principales módulos.

📌 **Enlace al video:** 

---> https://youtube.com/shorts/-CSKuJAp6Yc?si=fTM7oMY8l992OZ1E

# 👥 Equipo

Proyecto desarrollado para **Hackathon Nicaragua 2026**.

### Categoría

**Aficionado**


### Nombre de la plataforma

**MiBus**

---

# 🏫 Institución

**Universidad de las Regiones Autónomas de la Costa Caribe Nicaragüense — URACCAN**

Centro Universitario Regional Bluefields

---

# 🚌 MiBus

> **Tecnología para una mejor información sobre la movilidad urbana.**

**MiBus — Sistema Inteligente de Gestión y Localización de Buses Urbanos.** 🚌📍🇳🇮
