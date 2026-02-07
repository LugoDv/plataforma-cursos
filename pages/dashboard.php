<?php
require_once '../includes/config.php';
require_once '../includes/connectDB.php';
require_once '../includes/utils.php';
require_once '../includes/dao/CourseDao.php';
require_once '../includes/dao/UserDao.php';
require_once '../includes/dao/EnrollmentDao.php';
require_once '../includes/dao/LessonDao.php';

$page_title = 'Dashboard';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'teacher'])) {
  redirect('index.php');
}

$userRole = $_SESSION['user']['role'];
$userId = $_SESSION['user']['id'];

$courseDao = new CourseDao($db);
$userDao = new UserDao($db);
$enrollmentDao = new EnrollmentDao($db);
$lessonDao = new LessonDao($db);

$stats = [];

if ($userRole === 'admin') {
  // Estadísticas para Admin
  $stats = [
    'total_courses' => $courseDao->countAllCourses(),
    'total_students' => $userDao->countUsersByRole('student'),
    'total_teachers' => $userDao->countUsersByRole('teacher'),
    'pending_courses' => $courseDao->countCoursesByStatus('pending'),
    'published_courses' => $courseDao->countCoursesByStatus('published'),
    'total_enrollments' => $enrollmentDao->countTotalEnrollments()
  ];
} else {
  // Estadísticas para Teacher
  $teacherCourses = $courseDao->getCoursesByTeacher($userId);
  $totalLessons = 0;
  foreach ($teacherCourses as $course) {
    $totalLessons += $lessonDao->countLessonsByCourse($course['id']);
  }

  $publishedCourses = 0;
  $draftCourses = 0;

  foreach ($teacherCourses as $course) {

    if ($course['status'] === 'published') {
      $publishedCourses++;
    } elseif ($course['status'] === 'pending') {
      $draftCourses++;
    }
  }

  $stats = [
    'my_courses' => $courseDao->countCoursesByTeacher($userId),
    'my_students' => $enrollmentDao->countStudentsByTeacher($userId),
    'my_lessons' => $totalLessons,
    'published_courses' => $publishedCourses,
    'draft_courses' => $draftCourses
  ];
}


require_once '../includes/dashboard_header.php';
?>

<div class="p-6">
  <!-- Header -->
  <div class="mb-8">
    <h1 class="text-4xl font-black font-display uppercase italic tracking-tighter mb-2">
      Bienvenido, <?= htmlspecialchars($_SESSION['user']['username']) ?>
    </h1>
    <p class="text-sm font-bold uppercase tracking-wider opacity-70">
      <?php
      $roleLabels = ['admin' => 'Administrador', 'teacher' => 'Profesor'];
      echo $roleLabels[$userRole] ?? $userRole;
      ?>
    </p>
  </div>

  <?php if ($userRole === 'admin'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- Total Cursos -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Total Cursos</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['total_courses'] ?></h3>
            </div>
            <div class="p-4 bg-primary border-3 border-black shadow-neo-sm">
              <i data-lucide="book-open" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Estudiantes -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Estudiantes</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['total_students'] ?></h3>
            </div>
            <div class="p-4 bg-info border-3 border-black shadow-neo-sm">
              <i data-lucide="users" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Profesores -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Profesores</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['total_teachers'] ?></h3>
            </div>
            <div class="p-4 bg-secondary border-3 border-black shadow-neo-sm">
              <i data-lucide="user-check" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Cursos Pendientes -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Cursos pendientes</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['pending_courses'] ?></h3>
            </div>
            <div class="p-4 bg-warning border-3 border-black shadow-neo-sm">
              <i data-lucide="clock" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Cursos Publicados -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Publicados</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['published_courses'] ?></h3>
            </div>
            <div class="p-4 bg-success border-3 border-black shadow-neo-sm">
              <i data-lucide="check-circle" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Inscripciones -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Inscripciones</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['total_enrollments'] ?></h3>
            </div>
            <div class="p-4 bg-accent border-3 border-black shadow-neo-sm">
              <i data-lucide="user-plus" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php elseif ($userRole === 'teacher'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- Mis Cursos -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Mis Cursos</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['my_courses'] ?></h3>
            </div>
            <div class="p-4 bg-primary border-3 border-black shadow-neo-sm">
              <i data-lucide="book-open" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Mis Estudiantes -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Mis Estudiantes</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['my_students'] ?></h3>
            </div>
            <div class="p-4 bg-info border-3 border-black shadow-neo-sm">
              <i data-lucide="users" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Mis Lecciones -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Lecciones</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['my_lessons'] ?></h3>
            </div>
            <div class="p-4 bg-secondary border-3 border-black shadow-neo-sm">
              <i data-lucide="video" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Publicados -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Publicados</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['published_courses'] ?></h3>
            </div>
            <div class="p-4 bg-success border-3 border-black shadow-neo-sm">
              <i data-lucide="check-circle" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Borradores -->
      <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
        <div class="card-body p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-black uppercase tracking-wider opacity-60 mb-2">Borradores</p>
              <h3 class="text-4xl font-black font-display"><?= $stats['draft_courses'] ?></h3>
            </div>
            <div class="p-4 bg-warning border-3 border-black shadow-neo-sm">
              <i data-lucide="file-edit" class="size-8 text-base-100 stroke-[2.5]"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php endif; ?>

  <!-- Acciones rápidas -->
  <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
    <div class="card-body p-6">
      <h2 class="text-2xl font-black font-display uppercase italic tracking-tighter mb-4">Acceso Rápido</h2>
      <div class="flex flex-wrap gap-3">
        <?php if ($userRole === 'admin'): ?>
          <a href="<?= BASE_URL ?>pages/courses.php" class="btn btn-primary rounded-none border-3 border-black shadow-neo-sm hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all font-black uppercase">
            <i data-lucide="book-open" class="size-4"></i>
            Gestionar Cursos
          </a>
          <a href="<?= BASE_URL ?>pages/users.php" class="btn btn-secondary rounded-none border-3 border-black shadow-neo-sm hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all font-black uppercase">
            <i data-lucide="users" class="size-4"></i>
            Gestionar Usuarios
          </a>
        <?php elseif ($userRole === 'teacher'): ?>
          <a href="<?= BASE_URL ?>pages/teacherCourses.php" class="btn btn-primary rounded-none border-3 border-black shadow-neo-sm hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all font-black uppercase">
            <i data-lucide="book-open" class="size-4"></i>
            Mis Cursos
          </a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>pages/studentCourses.php" class="btn btn-primary rounded-none border-3 border-black shadow-neo-sm hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all font-black uppercase">
            <i data-lucide="book-open" class="size-4"></i>
            Mis Cursos
          </a>
          <a href="<?= BASE_URL ?>index.php" class="btn btn-secondary rounded-none border-3 border-black shadow-neo-sm hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all font-black uppercase">
            <i data-lucide="compass" class="size-4"></i>
            Explorar Cursos
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once '../includes/dashboard_footer.php'; ?>