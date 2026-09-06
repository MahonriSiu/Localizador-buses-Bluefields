<?php
require_once __DIR__ . '/../config/rutas.php';
require_once __DIR__ . '/../app/utilidades/AccesoUsuario.php';

AccesoUsuario::exigirRegistro(URL_BASE);

require __DIR__ . '/../app/views/usuario/mapa.php';