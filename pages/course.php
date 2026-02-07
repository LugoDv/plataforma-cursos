<?php
require_once '../includes/config.php';
require_once '../includes/connectDB.php';
require_once '../includes/header.php';
include_once '../includes/dao/CourseDao.php';
include_once '../includes/dao/LessonDao.php';
include_once '../includes/dao/EnrollmentDao.php';


$courseDao = new CourseDao($db);
$lessonDao = new LessonDao($db);
$enrollmentDao = new EnrollmentDao($db);
$courseId = isset($_GET['id']) ? $_GET['id'] : 0;
$course = $courseDao->getCourseById($courseId);
$lessons = $lessonDao->getLessonsByCourse($courseId);


?>

<div class="max-w-7xl mx-auto px-6 lg:px-20 py-16 lg:py-8 space-y-1 ">

  <!-- resumen del curso -->
  <div class="w-full pb-5  ">
    <div class="bg-base-100 border-3 border-black shadow-neo-lg p-6 flex flex-col md:flex-row justify-between items-center gap-6 rounded-none">
      <div class="flex items-center gap-6">
        <div class="hidden md:block w-24 h-24 border-2 border-black shadow-neo-sm overflow-hidden bg-base-300">
          <img src="<?= BASE_URL . 'assets/upload/thumbnails/' . $course['thumbnail'] ?>"
            class="w-full h-full object-cover" alt="Course thumb">
        </div>
        <div>
          <div class="flex items-center gap-3 mb-1">
            <span class="bg-secondary px-2 py-0.5 border-2 border-black text-[10px] font-black uppercase shadow-neo-sm">
              <?= $course['category'] ?>
            </span>
            <span class="text-[10px] font-black opacity-50 uppercase tracking-widest">ID: #<?= $course['id'] ?></span>
          </div>
          <h1 class="text-3xl md:text-4xl font-display uppercase italic tracking-tighter leading-none">
            <?= $course['title'] ?>
          </h1>
          <p class="text-sm font-medium mt-2 max-w-xl opacity-80"><?= $course['description'] ?></p>
        </div>
      </div>

      <?php if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != $course['teacher_id']):  ?>
        <?php if (!isset($_SESSION['user']) || !$enrollmentDao->isSubscribed($_SESSION['user']['id'], $courseId)): ?>


          <form action="<?= BASE_URL ?>procedures/enrollment/subscribe.proc.php" method="post">

            <input type="text" name="courseId" value="<?= $courseId ?>" hidden>

            <button
              class="btn btn-primary flex-1 md:flex-none border-2 border-black shadow-neo-sm rounded-none font-display italic ">
              Suscribirse
          </form>
          </button>
        <?php else: ?>
          <a href="<?= BASE_URL ?>pages/studentCourse.php?id=<?= $courseId ?>" class="btn btn-primary flex-1 md:flex-none border-2 border-black shadow-neo-sm rounded-none font-display italic  ">
            Ir al curso
          </a>
        <?php endif; ?>
      <?php endif; ?>


    </div>

  </div>



  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- lesseons -->
    <section class="lg:col-span-8">
      <?php include __DIR__ . '/../includes/lesson_list.php'; ?>
    </section>

    <aside class="lg:col-span-4">
      <div class="bg-base-200 border-3 border-black shadow-neo p-6 rounded-none sticky top-6">
        <h3 class="font-display uppercase italic text-xl mb-4 tracking-tight">Este curso incluye</h3>

        <div class="space-y-4">
          <div class="flex items-center gap-3 p-3 bg-base-100 border-2 border-black rounded-none">
            <div class="bg-accent border-2 border-black p-2 shrink-0">
              <i data-lucide="user" class="size-5 text-black stroke-[2.5]"></i>
            </div>
            <div>
              <p class="font-black uppercase text-[10px] opacity-60">Creado por</p>
              <p class="font-bold text-sm"><?= htmlspecialchars($course['teacher_name'] ?? 'Instructor') ?></p>
            </div>
          </div>

          <div class="flex items-center gap-3 p-3 bg-base-100 border-2 border-black rounded-none">
            <div class="bg-primary border-2 border-black p-2 shrink-0">
              <i data-lucide="book-open" class="size-5 text-black stroke-[2.5]"></i>
            </div>
            <div>
              <p class="font-black uppercase text-[10px] opacity-60">Lecciones</p>
              <p class="font-bold text-sm"><?= count($lessons) ?> <?= count($lessons) === 1 ? 'lección' : 'lecciones' ?></p>
            </div>
          </div>

          <div class="flex items-center gap-3 p-3 bg-base-100 border-2 border-black rounded-none">
            <div class="bg-secondary border-2 border-black p-2 shrink-0">
              <i data-lucide="smartphone" class="size-5 text-black stroke-[2.5]"></i>
            </div>
            <div>
              <p class="font-black uppercase text-[10px] opacity-60">Acceso</p>
              <p class="font-bold text-sm">Disponible en móvil</p>
            </div>
          </div>

          <div class="flex items-center gap-3 p-3 bg-base-100 border-2 border-black rounded-none">
            <div class="bg-warning border-2 border-black p-2 shrink-0">
              <i data-lucide="trophy" class="size-5 text-black stroke-[2.5]"></i>
            </div>
            <div>
              <p class="font-black uppercase text-[10px] opacity-60">Al finalizar</p>
              <p class="font-bold text-sm">Certificado de curso</p>
            </div>
          </div>
        </div>
      </div>
    </aside>

  </div>

  <?php require_once '../includes/footer.php'; ?>