<?php
require_once __DIR__ . '/../../includes/utils.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connectDB.php';
require_once __DIR__ . '/../../includes/dao/EnrollmentDao.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Solicitud inválida.'
  ];
  redirect('index.php');
}



if (!isset($_SESSION['user'])) {

  redirect("pages/login.php");
}
$enrollmentDao = new EnrollmentDao($db);

$userId = $_SESSION['user']['id'];
$courseId = intval($_POST['courseId']) ?? 0;

if ($enrollmentDao->subscribeStudent($userId, $courseId)) {
  $_SESSION['alert_message'] = [
    'type' => 'success',
    'message' => 'Te has suscrito al curso correctamente.'
  ];
  redirect("pages/course.php?id=$courseId");
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Error al suscribirse al curso. Inténtalo de nuevo.'
  ];
  redirect("pages/course.php?id=$courseId");
}
