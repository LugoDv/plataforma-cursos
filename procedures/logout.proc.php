<?php
include_once '../includes/config.php';
include_once '../includes/utils.php';

// Destruir la sesión
if (isset($_SESSION['user'])) {
  session_unset();
  session_destroy();



  session_start();
  $_SESSION['alert_message'] = [
    "type" => "info",
    "message" => "Adios.Vuelve pronto."
  ];

  redirect("index.php");
}
