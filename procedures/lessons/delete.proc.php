<?php
require_once __DIR__ . '/../../includes/utils.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connectDB.php';
require_once __DIR__ . '/../../includes/dao/LessonDao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Solicitud inválida.'
  ];
  redirect('index.php');
}

$lessonDao = new LessonDao($db);
$lessonId = $_POST['lesson_id'] ?? "";
$courseId = $_POST['course_id'] ?? null;

if ($lessonDao->deleteLesson($lessonId)) {
  $_SESSION['alert_message'] = [
    'type' => 'success',
    'message' => 'Lección eliminada correctamente.'
  ];
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Error al eliminar la lección. Inténtalo de nuevo.'
  ];
}
redirect("pages/teacherCourse.php?course_id=$courseId");
