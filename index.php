<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'includes/dao/CourseDao.php';
require_once 'includes/connectDB.php';

$courseDao = new CourseDao($db);
$courseBackend = $courseDao->getCoursesByCategory('backend')?? [];
$courseFrontend = $courseDao->getCoursesByCategory('frontend') ?? [];


?>

<!-- Hero Section -->
<section class="hero min-h-screen bg-base-300 relative overflow-hidden">
  <div class=" hero-content px-6 lg:px-20 py-16 lg:py-10">
    <div class="grid lg:grid-cols-2 gap-12 items-center">

      <!-- texto -->
      <div class="space-y-8 z-10">
        <h1 class="font-display font-black text-4xl lg:text-4xl xl:text-5xl leading-tight">
          Aprende una Nueva Habilidad Todos los Días.
        </h1>

        <p class="text-base lg:text-lg opacity-70 max-w-lg">
          Cursos que cubren todos los dominios tecnológicos para que aprendas y explores nuevas oportunidades.
        </p>

        <!-- Buttons -->
        <div class="flex flex-wrap gap-4">
          <button class="btn btn-primary w-full lg:w-auto btn-lg rounded-none border-3 border-black font-black uppercase text-sm px-8 shadow-neo-lg  transition-all">
            Empezar
          </button>

        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-6 pt-8">
          <div>
            <div class="text-3xl lg:text-4xl font-black text-warning">1000+</div>
            <div class="text-xs lg:text-sm font-bold uppercase tracking-wider opacity-70 mt-1">Cursos para<br>elegir</div>
          </div>
          <div>
            <div class="text-3xl lg:text-4xl font-black text-info">5000+</div>
            <div class="text-xs lg:text-sm font-bold uppercase tracking-wider opacity-70 mt-1">Estudiantes<br>Capacitados</div>
          </div>
          <div>
            <div class="text-3xl lg:text-4xl font-black text-error">200+</div>
            <div class="text-xs lg:text-sm font-bold uppercase tracking-wider opacity-70 mt-1">Instructores<br>Profesionales</div>
          </div>
        </div>
      </div>

      <!-- Image Section -->
      <div class="relative flex items-center justify-center lg:justify-end pt-16 lg:pt-24">
        <div class="relative w-[320px] h-80 lg:w-112.5 lg:h-112.5">

          <div class="absolute inset-0 rounded-full  bg-primary border-4 border-black z-0 shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]"></div>

          <div class="absolute inset-0 overflow-hidden rounded-full z-10">
            <img
              src="<?= BASE_URL ?>assets/img/student-hero.svg"
              alt="Student"
              class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[85%] h-auto object-contain origin-bottom scale-110" />
          </div>

          <div class="absolute inset-0 z-20 pointer-events-none">
            <img
              src="<?= BASE_URL ?>assets/img/student-hero.svg"
              alt="Student"
              class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[85%] h-auto object-contain origin-bottom scale-110"
              style="clip-path: inset(-20% 0 40% 0);" />
          </div>
        </div>

        <div class="absolute top-0 left-0 lg:-top-5 lg:left-20 animate-float z-30">
          <img src="<?= BASE_URL ?>assets/img/robot1.svg" class="w-24 h-24 lg:w-40 lg:h-40 bg-transparent" />
        </div>

        <div class="absolute bottom-10 right-0 lg:bottom-10 lg:-right-5 animate-float animation-delay-400 z-30">
          <img src="<?= BASE_URL ?>assets/img/programacion1.svg" class="w-20 h-20 lg:w-36 lg:h-36" />
        </div>
      </div>

    </div>
  </div>
</section>

<main class="max-w-7xl mx-auto px-6 lg:px-20 py-16 lg:py-24 space-y-16">

  <!-- Título Principal -->
  <div class="space-y-4">
    <h2 class="font-display font-black text-2xl lg:text-3xl">¿Qué vas a aprender hoy?</h2>
  </div>

  <!-- Frontend Carousel -->
  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <h3 class="font-display italic font-black text-2xl lg:text-3xl  ">Frontend</h3>
      <a href="#" class="btn btn-sm btn-ghost border-2 border-black rounded-none shadow-neo-sm">Ver todos <i data-lucide="arrow-right"></i></a>
    </div>

    <div class="carousel carousel-center w-full space-x-4 p-4 bg-base-200 rounded-none border-3 border-black">
      <!-- Card  -->
      <?php foreach ($courseFrontend as $course): ?>
        <div class="carousel-item">
          <a href="pages/course.php?id=<?= $course['id'] ?>" class="group transition-all">

            <div class="card w-64 lg:w-72 bg-base-100 border-3 border-black rounded-none shadow-neo">
              <figure class="h-40 bg-primary border-b-3 border-black relative overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                  <img src="assets/upload/thumbnails/<?= $course['thumbnail'] ?>" class="group-hover:scale-105 transition-transform duration-500" alt="miniatura del curso">
                </div>
              </figure>
              <div class="card-body p-5">
                <div class="badge badge-primary border-2 border-black rounded-none font-bold">Frontend</div>
                <h4 class="card-title font-display text-lg"><?php echo htmlspecialchars($course['title']); ?></h4>
                <p class="text-sm opacity-70"><?php echo htmlspecialchars($course['description']); ?></p>
                <div class="flex items-center gap-2 text-xs mt-2">
                  <i data-lucide="user" class="size-4 opacity-70"></i>
                  <span class="opacity-70"><?php echo htmlspecialchars($course['teacher_name']); ?></span>
                </div>
                <div class="flex items-center gap-2 text-xs">
                  <span class="font-bold flex gap-1 items-center">4.9
                    <i data-lucide="star"
                      class="text-warning size-4"></i>
                    <i data-lucide="star"
                      class="text-warning size-4"></i>
                    <i data-lucide="star"
                      class="text-warning size-4"></i>
                    <i data-lucide="star"
                      class="text-warning size-4"></i>
                    <i data-lucide="star-half"
                      class="text-warning size-4"></i>
                  </span>

                </div>

              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Backend Carousel -->
  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <h3 class="font-display italic font-black text-2xl lg:text-3xl ">Backend</h3>
      <a href="#" class="btn btn-sm btn-ghost border-2 border-black rounded-none shadow-neo-sm">Ver todos <i data-lucide="arrow-right"></i></a>
    </div>

    <div class="carousel carousel-center w-full space-x-4 p-4 bg-base-200 rounded-none border-3 border-black">
      <!-- Card  -->
      <?php foreach ($courseBackend as $course): ?>
        <div class="carousel-item">
          <a href="pages/course.php?id=<?= $course['id'] ?>" class="group transition-all">

            <div class="card w-64 lg:w-72 bg-base-100 border-3 border-black rounded-none shadow-neo">
              <figure class="h-40 bg-primary border-b-3 border-black relative overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                  <img src=" assets/upload/thumbnails/<?= $course['thumbnail'] ?>" class="group-hover:scale-105 transition-transform duration-500" alt="miniatura del curso">
                </div>
              </figure>
              <div class="card-body p-5">
                <div class="badge badge-primary border-2 border-black rounded-none font-bold">Backend</div>
                <h4 class="card-title font-display text-lg"><?php echo htmlspecialchars($course['title']); ?></h4>
                <p class="text-sm opacity-70"><?php echo htmlspecialchars($course['description']); ?></p>
                <div class="flex items-center gap-2 text-xs mt-2">
                  <i data-lucide="user" class="size-4 opacity-70"></i>
                  <span class="opacity-70"><?php echo htmlspecialchars($course['teacher_name']); ?></span>
                </div>
                <div class="flex items-center gap-2 text-xs">
                  <span class="font-bold flex gap-1 items-center">4.9
                    <i data-lucide="star"
                      class="text-warning size-4"></i>
                    <i data-lucide="star"
                      class="text-warning size-4"></i>
                    <i data-lucide="star"
                      class="text-warning size-4"></i>
                    <i data-lucide="star"
                      class="text-warning size-4"></i>
                    <i data-lucide="star-half"
                      class="text-warning size-4"></i>
                  </span>

                </div>

              </div>
          </a>
        </div>
    </div>
  <?php endforeach; ?>


  </div>
  </section>

</main>

<?php require_once 'includes/footer.php'; ?>