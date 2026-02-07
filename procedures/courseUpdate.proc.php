<?php
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connectDB.php';
require_once __DIR__ . '/../includes/dao/CourseDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $courseDao = new CourseDao($db);

  $id = cleanInput($_POST['course_id']) ?? "";
  $teacher_id = cleanInput($_POST['teacher_id']) ?? "";
  $title = cleanInput($_POST['title']) ?? "";
  $description = cleanInput($_POST['description']) ?? "";
  $category = cleanInput($_POST['category']) ?? "";
  $status = isset($_POST['status']) ? cleanInput($_POST['status']) : 'pending';
  $thumbnail = $_FILES['thumbnail'] ?? null;



  $courseData = [
    'id' => $id,
    'teacher_id' => $teacher_id,
    'title' => $title,
    'description' => $description,
    'category' => $category,
    'status' => $status
  ];

  $teacherId = $_SESSION['user']['role'] === 'teacher' ? $_SESSION['user']['id'] : null;

  if ($courseDao->updateCourse($courseData, $thumbnail, $teacherId)) {
    $_SESSION['alert_message'] = [
      'type' => 'success',
      'message' => 'Curso actualizado correctamente.'
    ];
  } else {
    $_SESSION['alert_message'] = [
      'type' => 'error',
      'message' => 'Error al actualizar el curso o no tienes permisos.'
    ];
  }

  // Redirigir a la página desde donde se envió el formulario
  if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  } else {
    // Fallback si no hay referer
    $redirectPage = $_SESSION['user']['role'] === 'admin' ? 'pages/courses.php' : 'pages/teacherCourses.php';
    redirect($redirectPage);
  }
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Solicitud inválida.'
  ];
  redirect('index.php');
}
