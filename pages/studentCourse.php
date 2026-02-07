<?php
require_once '../includes/config.php';
require_once '../includes/connectDB.php';
require_once '../includes/utils.php';
include_once '../includes/dao/CourseDao.php';
include_once '../includes/dao/LessonDao.php';
include_once '../includes/dao/EnrollmentDao.php';

if (!isset($_SESSION['user'])) {
  redirect('pages/login.php');
}

$courseId = isset($_GET['id']) ? $_GET['id'] : 0;
$enrollmentDao = new EnrollmentDao($db);

if (!$enrollmentDao->isSubscribed($_SESSION['user']['id'], $courseId)) {
  redirect('pages/course.php?id=' . $courseId);
}
require_once '../includes/header.php';

$courseDao = new CourseDao($db);
$lessonDao = new LessonDao($db);
$lessonId = isset($_GET['lesson']) ? $_GET['lesson'] : null;

$course = $courseDao->getCourseById($courseId);
$lessons = $lessonDao->getLessonsByCourse($courseId);

if (!$enrollmentDao->isSubscribed($_SESSION['user']['id'], $courseId)) {
  redirect('pages/course.php?id=' . $courseId);
}

if (!$lessonId && !empty($lessons)) {
  $currentLesson = $lessons[0];
} else {
  $currentLesson = null;
  foreach ($lessons as $lesson) {
    if ($lesson['id'] == $lessonId) {
      $currentLesson = $lesson;
      break;
    }
  }
  if (!$currentLesson && !empty($lessons)) {
    $currentLesson = $lessons[0];
  }
}



?>

<div class="max-w-7xl mx-auto px-4 lg:px-8 py-6">



  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <div class="lg:col-span-8 space-y-6 mb-10 lg:mb-0">

      <!-- Video -->
      <div class="bg-base-200 border-3 border-black shadow-neo-lg rounded-none overflow-hidden">
        <?php if ($currentLesson && $currentLesson['video_url']): ?>
          <div class="relative w-full" style="padding-bottom: 56.25%;">
            <iframe
              class="absolute top-0 left-0 w-full h-full border-0"
              src="<?= getYouTubeEmbedUrl($currentLesson['video_url']) ?>"
              title="<?= htmlspecialchars($currentLesson['title']) ?>"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen>
            </iframe>
          </div>
        <?php else: ?>
          <div class="aspect-video flex items-center justify-center bg-base-300 border-2 border-black">
            <div class="text-center">
              <i data-lucide="video-off" class="size-16 text-black/20 stroke-2 mx-auto mb-4"></i>
              <p class="font-black uppercase text-sm opacity-40">No hay video disponible</p>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Detalles de la lección -->
      <div class="bg-base-100 border-3 border-black shadow-neo-lg p-6 rounded-none">
        <?php if ($currentLesson): ?>
          <div class="flex items-start gap-4 mb-6">
            <div class="bg-black text-white px-4 py-2 border-2 border-black font-display text-2xl shrink-0">
              <?= $currentLesson['order_index'] ?>
            </div>
            <div class="flex-1">
              <h2 class="text-2xl md:text-3xl font-display uppercase italic tracking-tight leading-tight">
                <?= htmlspecialchars($currentLesson['title']) ?>
              </h2>
            </div>
          </div>

          <?php if ($currentLesson['content']): ?>
            <div class="prose prose-sm max-w-none">
              <div class="bg-base-200 border-2 border-black p-6 rounded-none">
                <h3 class="font-black uppercase text-xs text-secondary mb-3 tracking-wider">Descripción de la lección</h3>
                <div class="text-base leading-relaxed font-normal">
                  <?= nl2br(htmlspecialchars($currentLesson['content'])) ?>
                </div>
              </div>
            </div>
          <?php else: ?>
            <div class="border-2 border-dashed border-black/20 p-8 text-center">
              <p class="font-bold opacity-40 uppercase text-xs">No hay descripción disponible para esta lección</p>
            </div>
          <?php endif; ?>



        <?php else: ?>
          <div class="text-center py-12">
            <i data-lucide="alert-circle" class="size-16 text-black/20 stroke-2 mx-auto mb-4"></i>
            <p class="font-black uppercase text-sm opacity-40">No se encontró la lección</p>
          </div>
        <?php endif; ?>
      </div>

    </div>

    <!-- Sidebar: Lista de lecciones -->
    <aside class="lg:col-span-4">
      <div class="bg-base-200 border-3 border-black shadow-neo-lg rounded-none sticky top-6">
        <div class="p-6 border-b-2 border-black bg-base-300">
          <h3 class="font-display uppercase italic text-xl tracking-tight">Lecciones</h3>
          <p class="text-xs font-bold opacity-60 mt-1"><?= count($lessons) ?> <?= count($lessons) === 1 ? 'lección' : 'lecciones' ?></p>
        </div>

        <div class="p-4 max-h-[calc(100vh-12rem)] overflow-y-auto">
          <?php if (empty($lessons)): ?>
            <div class="border-2 border-dashed border-black/20 p-8 text-center">
              <p class="font-bold opacity-40 uppercase text-xs">No hay lecciones disponibles</p>
            </div>
          <?php else: ?>
            <div class="space-y-2">
              <?php foreach ($lessons as $lesson): ?>
                <?php $isActive = $currentLesson && $currentLesson['id'] == $lesson['id']; ?>
                <a href="?id=<?= $courseId ?>&lesson=<?= $lesson['id'] ?>"
                  class="block bg-base-100 border-2 border-black shadow-neo-sm hover:shadow-neo transition-all rounded-none overflow-hidden ">

                  <div class="flex items-center">
                    <div class="<?= $isActive ? 'bg-primary' : 'bg-black' ?> text-white w-12 h-16 flex items-center justify-center font-display text-xl shrink-0">
                      <?= $lesson['order_index'] ?>
                    </div>

                    <div class="flex-1 px-3 py-2">
                      <h4 class="font-black uppercase text-xs leading-tight <?= $isActive ? 'text-primary' : '' ?>">
                        <?= htmlspecialchars($lesson['title']) ?>
                      </h4>
                      <div class="flex gap-2 mt-1.5">
                        <?php if ($lesson['video_url']): ?>
                          <span class="flex items-center gap-1 text-[10px] font-bold opacity-60">
                            <i data-lucide="play-circle" class="size-3 stroke-3"></i>
                            Video
                          </span>
                        <?php endif; ?>
                        <?php if ($lesson['content']): ?>
                          <span class="flex items-center gap-1 text-[10px] font-bold opacity-60">
                            <i data-lucide="file-text" class="size-3 stroke-3"></i>
                            Contenido
                          </span>
                        <?php endif; ?>
                      </div>
                    </div>


                    <?php if ($isActive): ?>
                      <div class="px-3">
                        <i data-lucide="play" class="size-5 text-primary stroke-3"></i>
                      </div>
                    <?php endif; ?>
                  </div>

                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </aside>

  </div>

</div>

<?php require_once '../includes/footer.php'; ?>