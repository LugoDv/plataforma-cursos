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
$lessonId = $_POST['lesson_id'] ?? null;
$title = cleanInput($_POST['title']) ?? "";
$videoUrl = cleanInput($_POST['video_url']) ?? "";
$orderIndex = isset($_POST['order_index']) ? (int)$_POST['order_index'] : 1;
$content = cleanInput($_POST['content']) ?? "";

if ($lessonDao->updateLesson($lessonId, $title, $videoUrl, $orderIndex, $content)) {
  $_SESSION['alert_message'] = [
    'type' => 'success',
    'message' => 'Lección actualizada.'
  ];
  redirect("pages/teacherCourse.php?course_id=" . $_POST['course_id']);
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Error al actualizar la lección. Inténtalo de nuevo.'
  ];
  redirect("pages/teacherCourse.php?course_id=" . $_POST['course_id']);
}
