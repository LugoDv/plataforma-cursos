<?php
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connectDB.php';
require_once __DIR__ . '/../includes/dao/CourseDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $courseDao = new CourseDao($db);

  $teacher_id = isset($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : $_SESSION['user']['id'];
  $title = cleanInput($_POST['title']) ?? "";
  $description = cleanInput($_POST['description']) ?? "";
  $category = cleanInput($_POST['category']) ?? "";
  $status = isset($_POST['status']) ? cleanInput($_POST['status']) : 'pending';
  $thumbnail = $_FILES['thumbnail'] ?? null;

  $newCourse = [
    'teacher_id' => $teacher_id,
    'title' => $title,
    'description' => $description,
    'category' => $category,
    'status' => $status
  ];

  if ($courseDao->createCourse($newCourse, $thumbnail)) {
    $_SESSION['alert_message'] = [
      'type' => 'success',
      'message' => 'Curso creado correctamente.'
    ];
  } else {
    $_SESSION['alert_message'] = [
      'type' => 'error',
      'message' => 'Error al crear el curso. Inténtalo de nuevo.'
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
