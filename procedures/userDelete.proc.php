<?php
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connectDB.php';
require_once __DIR__ . '/../includes/dao/UserDao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Solicitud inválida.'
  ];
  redirect('index.php');
}

$userDao = new UserDao($db);
$id = cleanInput($_POST['user_id']) ?? "";
if ($userDao->deleteUser($id)) {
  $_SESSION['alert_message'] = [
    'type' => 'success',
    'message' => 'Usuario eliminado correctamente.'
  ];
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Error al eliminar el usuario. Inténtalo de nuevo.'
  ];
}
redirect('pages/users.php');
