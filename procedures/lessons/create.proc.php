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

$courseId = $_POST['course_id'] ?? null;
$title = cleanInput($_POST['title']) ?? "";
$videoUrl = cleanInput($_POST['video_url']) ?? "";
$orderIndex = isset($_POST['order_index']) ? (int)$_POST['order_index'] : 1;
$content = cleanInput($_POST['content']) ?? "";

if ($lessonDao->createLesson($courseId, $title, $videoUrl, $orderIndex, $content)) {
  $_SESSION['alert_message'] = [
    'type' => 'success',
    'message' => 'Lección creada.'
  ];
  redirect("pages/teacherCourse.php?course_id=$courseId");
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Error al crear la lección. Inténtalo de nuevo.'
  ];
  redirect("pages/teacherCourse.php?course_id=$courseId");
}
