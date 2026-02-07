<?php

// includes/connectDB.php
include_once __DIR__ . '/config.php';
// Crear conexión a la base de datos

$db = new SQLite3(DB_PATH);


if (!$db) {

  die("Error de conexión: ");
}
