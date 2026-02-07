<!-- Footer -->
<footer class="bg-base-300 border-t-4 border-black mt-24">
  <div class="max-w-7xl mx-auto px-6 lg:px-20 py-16">

    <!-- Main Footer Content -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">

      <!-- Brand Section -->
      <div class="space-y-6">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 bg-primary border-3 border-black flex items-center justify-center shadow-neo">
            <img src="<?= BASE_URL ?>assets/img/logo.png" alt="">
          </div>
          <span class="font-display font-black text-2xl">LugoTech</span>
        </div>
        <p class="text-sm opacity-70 leading-relaxed">
          La plataforma de aprendizaje que te ayuda a crecer profesionalmente con cursos de calidad.
        </p>
        <!-- Social Links -->
        <div class="flex gap-3">

          <a href="https://www.linkedin.com/in/fabian-lugo-/" class="btn btn-square btn-sm border-2 border-black shadow-neo-sm hover:shadow-neo transition-all">
            <i data-lucide="linkedin" class="size-4"></i>
          </a>
          <a href="https://github.com/LugoDv" class="btn btn-square btn-sm border-2 border-black shadow-neo-sm hover:shadow-neo transition-all">
            <i data-lucide="github" class="size-4"></i>
          </a>
          <a href="#" class="btn btn-square btn-sm border-2 border-black shadow-neo-sm hover:shadow-neo transition-all">
            <i data-lucide="youtube" class="size-4"></i>
          </a>
        </div>
      </div>

      <!-- Courses Section -->
      <div class="space-y-4">
        <h3 class="font-display font-black text-lg uppercase">Cursos</h3>
        <ul class="space-y-3">
          <li><a href="<?= BASE_URL ?>pages/coursesPublic.php" class="text-sm opacity-70 hover:opacity-100 hover:underline transition-all">Todos los Cursos</a></li>
          <li><a href="<?= BASE_URL ?>pages/coursesPublic.php" class="text-sm opacity-70 hover:opacity-100 hover:underline transition-all">Frontend</a></li>
          <li><a href="<?= BASE_URL ?>pages/coursesPublic.php" class="text-sm opacity-70 hover:opacity-100 hover:underline transition-all">Backend</a></li>
        </ul>
      </div>

      <!-- bottom nav -->
      <div class="space-y-4">
        <h3 class="font-display font-black text-lg uppercase">Navegación</h3>
        <ul class="space-y-3">
          <li><a href="<?= BASE_URL ?>index.php" class="text-sm opacity-70 hover:opacity-100 hover:underline transition-all">inicio</a></li>
          <li><a href="<?= BASE_URL ?>pages/coursesPublic.php" class="text-sm opacity-70 hover:opacity-100 hover:underline transition-all">Cursos</a></li>
        </ul>
      </div>

    </div>



    <!-- Bottom Bar -->
    <div class="pt-8 border-t-2 border-black">
      <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-sm opacity-70">
          © <?= date('Y') ?> LugoTech. Todos los derechos reservados.
        </p>
        <div class="flex items-center gap-6">
          <a href="#" class="text-sm opacity-70 hover:opacity-100 transition-all">Términos</a>
          <a href="#" class="text-sm opacity-70 hover:opacity-100 transition-all">Privacidad</a>
          <a href="#" class="text-sm opacity-70 hover:opacity-100 transition-all">Cookies</a>
        </div>
      </div>
    </div>

  </div>
</footer>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
  // Inicializar iconos de Lucide
  lucide.createIcons({

    attrs: {
      'stroke-width': 2.5,
      class: 'lucide-icon '
    }
  });
</script>
<script src="<?php echo BASE_URL; ?>assets/js/utils.js"></script>

</body>

</html>