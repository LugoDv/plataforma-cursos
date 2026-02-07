<?php
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connectDB.php';
require_once __DIR__ . '/../includes/dao/CourseDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $courseDao = new CourseDao($db);

  $courseId = cleanInput($_POST['course_id']) ?? "";
  $teacherId = $_SESSION['user']['role'] === 'teacher' ? $_SESSION['user']['id'] : null;

  if ($courseDao->deleteCourse($courseId, $teacherId)) {
    $_SESSION['alert_message'] = [
      'type' => 'success',
      'message' => 'Curso eliminado correctamente.'
    ];
  } else {
    $_SESSION['alert_message'] = [
      'type' => 'error',
      'message' => 'Error al eliminar el curso o no tienes permisos.'
    ];
  }

  $redirectPage = $_SESSION['user']['role'] === 'admin' ? 'pages/courses.php' : 'pages/teacherCourses.php';

  redirect($redirectPage);
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Solicitud inválida.'
  ];
  redirect('index.php');
}
