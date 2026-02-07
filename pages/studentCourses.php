<?php require_once '../includes/config.php';
require_once '../includes/connectDB.php';

require_once '../includes/header.php';
include_once '../includes/dao/CourseDao.php';
include_once '../includes/dao/LessonDao.php';
include_once '../includes/dao/EnrollmentDao.php';
$courseDao = new CourseDao($db);
$lessonDao = new LessonDao($db);
$enrollmentDao = new EnrollmentDao($db);


$courses = $enrollmentDao->getCoursesByStudent($_SESSION['user']['id']);
?>

<div class="max-w-7xl mx-auto px-6 lg:px-20 py-16 lg:py-8 space-y-1 ">

  <h1 class="text-3xl font-display uppercase italic tracking-tighter leading-none mb-6">
    Mis Cursos
  </h1>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if ($courses): ?>
      <?php foreach ($courses as $course): ?>
        <a href="<?= BASE_URL ?>pages/studentCourse.php?id=<?= $course['id'] ?> "
          class="block bg-base-100 border-2 border-black shadow-neo-sm hover:shadow-neo group transition-all overflow-hidden ">
          <div class="w-full h-40 border-b-2 border-black overflow-hidden bg-base-300">
            <img src="<?= BASE_URL . 'assets/upload/thumbnails/' . $course['thumbnail'] ?>"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Course thumb">
          </div>
          <div class="p-4">
            <h2 class="text-xl font-display font-black uppercase leading-tight mb-2">
              <?= htmlspecialchars($course['title']) ?>
            </h2>
            <p class="text-sm font-medium opacity-80">
              <?= htmlspecialchars($course['description']) ?>
            </p>
          </div>
          <div class="p-4 flex items-center justify-between ">
            <i data-lucide="notebook" class="size-4"></i>
            <p class="text-sm font-medium opacity-80">
              Lecciones: <?= $lessonDao->countLessonsByCourse($course['id']) ?>
            </p>
          </div>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-lg font-medium opacity-80">No tienes cursos inscritos.</p>
    <?php endif; ?>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>