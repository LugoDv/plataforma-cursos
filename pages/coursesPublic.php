<?php
require_once '../includes/config.php';
require_once '../includes/connectDB.php';
require_once '../includes/utils.php';
include_once '../includes/dao/CourseDao.php';
include_once '../includes/dao/LessonDao.php';

$courseDao = new CourseDao($db);
$lessonDao = new LessonDao($db);
$courses = $courseDao->getAllCoursesByStatus();

?>
<?php include_once '../includes/header.php'; ?>

<div class="max-w-7xl mx-auto px-6 lg:px-20 py-16 lg:py-8 space-y-6">

  <h1 class="text-3xl font-display uppercase italic tracking-tighter leading-none mb-6">
    Cursos Disponibles
  </h1>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php if ($courses): ?>
      <?php foreach ($courses as $course): ?>
        <a href="<?= BASE_URL ?>pages/course.php?id=<?= $course['id'] ?>" class="group transition-all">
          <div class="card w-full bg-base-100 border-3 border-black rounded-none shadow-neo">
            <figure class="h-40 bg-primary border-b-3 border-black relative overflow-hidden">
              <div class="absolute inset-0 flex items-center justify-center">
                <img src="<?= BASE_URL ?>assets/upload/thumbnails/<?= $course['thumbnail'] ?>" 
                     class="group-hover:scale-105 transition-transform duration-500" 
                     alt="miniatura del curso">
              </div>
            </figure>
            <div class="card-body p-5">
              <div class="badge badge-primary border-2 border-black rounded-none font-bold">
                <?= htmlspecialchars($course['category']) ?>
              </div>
              <h4 class="card-title font-display text-lg">
                <?= htmlspecialchars($course['title']) ?>
              </h4>
              <p class="text-sm opacity-70">
                <?= htmlspecialchars($course['description']) ?>
              </p>
              <div class="flex items-center gap-2 text-xs mt-2">
                <i data-lucide="user" class="size-4 opacity-70"></i>
                <span class="opacity-70"><?= htmlspecialchars($course['teacher_name']) ?></span>
              </div>
              <div class="flex items-center gap-2 text-xs">
                <span class="font-bold flex gap-1 items-center">4.9
                  <i data-lucide="star" class="text-warning size-4"></i>
                  <i data-lucide="star" class="text-warning size-4"></i>
                  <i data-lucide="star" class="text-warning size-4"></i>
                  <i data-lucide="star" class="text-warning size-4"></i>
                  <i data-lucide="star-half" class="text-warning size-4"></i>
                </span>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="col-span-full text-center text-lg font-medium opacity-80">No hay cursos disponibles.</p>
    <?php endif; ?>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
