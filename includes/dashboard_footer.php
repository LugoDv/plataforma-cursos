      </div>
      <!-- End page content -->
      </div>

      <div class="drawer-side bg-base-200 is-drawer-close:overflow-visible">
        <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
        <div class="flex min-h-full flex-col items-start bg-base-200 is-drawer-close:w-14 is-drawer-open:w-64 border-3 border-black">
          <!-- Sidebar content here -->
          <div class="w-full flex is-drawer-close:justify-center is-drawer-open:justify-start px-2 py-1 is-drawer-close:tooltip is-drawer-close:tooltip-right " data-tip="Lugo Tech">
            <div class="py-3 px-1.5 flex items-center gap-2">
              <img src="<?php echo BASE_URL; ?>assets/img/logo.png" alt="Your Company" class="size-5" />
              <span class="is-drawer-close:hidden font-display font-semibold">Lugo Tech</span>
            </div>
          </div>
          <ul class="menu w-full grow">

            <!-- List item -->
            <li>
              <a href="<?php echo BASE_URL; ?>pages/dashboard.php" class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Dashboard">
                <!-- Home icon -->
                <i data-lucide="layout-dashboard" class="my-1.5 inline-block size-5 "></i>
                <span class="is-drawer-close:hidden">Dashboard</span>
              </a>
            </li>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
              <li>
                <a href="<?= BASE_URL ?>pages/users.php" class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Usuarios">
                  <!-- icon -->
                  <i data-lucide="users" class="my-1.5 inline-block size-5 "></i>

                  <span class="is-drawer-close:hidden">Usuarios</span>
                </a>
              </li>
              <li>
                <a href="<?= BASE_URL ?>pages/courses.php" class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Cursos">
                  <!-- icon -->
                  <i data-lucide="graduation-cap" class="my-1.5 inline-block size-5 "></i>

                  <span class="is-drawer-close:hidden">Cursos</span>
                </a>
              </li>
            <?php else: ?>
              <li>
                <a href="<?= BASE_URL ?>pages/teacherCourses.php" class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Mis Cursos">
                  <!-- icon -->
                  <i data-lucide="book-open" class="my-1.5 inline-block size-5 "></i>

                  <span class="is-drawer-close:hidden">Mis Cursos</span>
                </a>
              </li>

            <?php endif; ?>

            <!-- List item -->
            <!-- <li>
              <a href="#" class="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Settings">

                <i data-lucide="settings-2" class="my-1.5 inline-block size-5 "></i>
                <span class="is-drawer-close:hidden">Settings</span>
              </a>
            </li> -->
          </ul>
        </div>
      </div>
      </div>
      <script src="<?php echo BASE_URL; ?>assets/js/utils.js"></script>
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

      </body>

      </html>