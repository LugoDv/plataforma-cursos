<?php
require_once '../includes/config.php';
require_once '../includes/connectDB.php';
require_once '../includes/utils.php';
require_once '../includes/dao/CourseDao.php';
require_once '../includes/dao/EnrollmentDao.php';
require_once '../includes/dao/LessonDao.php';

$courseDao = new CourseDao($db);
$courseId = $_GET['course_id'] ?? null;
$teacherId = $_SESSION['user']['id'];

$course = $courseDao->getCourseById($courseId);
if (!$course || $course['teacher_id'] != $teacherId) {
  redirect('pages/teacherCourses.php');
}

require_once '../includes/dashboard_header.php';
$enrollmentDao = new EnrollmentDao($db);
$lessonDao = new LessonDao($db);
$lessons = $lessonDao->getLessonsByCourse($courseId) ?? [];
$students = $enrollmentDao->getStudentsByCourse($courseId) ?? [];
$totalStudents = $enrollmentDao->countStudentsByCourse($courseId);

?>


<main class=" space-y-6 ">
  <div class="flex items-center justify-between mb-4">
    <div>
      <h2 class="text-3xl text-base-content font-display uppercase italic">Curso</h2>
      <p class="text-sm font-bold opacity-50 mt-1 uppercase">Gestiona contenido y estudiantes</p>
    </div>
    <div class="breadcrumbs hidden sm:block font-sans text-xs uppercase font-bold opacity-60">
      <ul>
        <li><a href="<?= BASE_URL ?>pages/dashboard.php">Lugo Tech</a></li>
        <li><a href="<?= BASE_URL ?>pages/teacherCourses.php">Mis cursos</a></li>
        <li>Curso</li>
      </ul>
    </div>
  </div>

  <section class="w-full">
    <?php include __DIR__ . '/../includes/course_summary.php'; ?>
  </section>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <section class="lg:col-span-8">
      <?php include __DIR__ . '/../includes/lesson_manager.php'; ?>
    </section>

    <aside class="lg:col-span-4">
      <?php include __DIR__ . '/../includes/student_table.php'; ?>
    </aside>

  </div>
</main>

<?php require_once '../includes/dashboard_footer.php'; ?>