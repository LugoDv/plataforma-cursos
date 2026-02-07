<?php
require_once __DIR__ . '/config.php';

if (isset($_SESSION['alert_message'])) :
  $type = $_SESSION['alert_message']['type'];
  $message = $_SESSION['alert_message']['message'];

  $alertClass = "bg-info";
  $icon = "info";

  if ($type === 'success') {
    $alertClass = "bg-success";
    $icon = "check-circle";
  }
  if ($type === 'error') {
    $alertClass = "bg-error";
    $icon = "alert-octagon";
  }
  if ($type === 'warning') {
    $alertClass = "bg-warning";
    $icon = "alert-triangle";
  }
?>

  <div id="toast-container" class="toast toast-top toast-end z-99 p-6">
    <div id="notification-alert"
      class="alert <?= $alertClass ?> rounded-none border-3 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] p-4 min-w-75 transition-all duration-300 ease-out">

      <div class="flex items-center gap-4 text-black">
        <div class="bg-white/20 border-2 border-black p-1 shadow-neo-sm">
          <i data-lucide="<?= $icon ?>" class="size-5 stroke-3"></i>
        </div>

        <div class="flex flex-col">
          <span class="text-[10px] uppercase font-black tracking-[0.15em] leading-none mb-1 opacity-80">
            <?= strtoupper($type) ?>
          </span>
          <span class="text-sm font-bold font-sans tracking-tight">
            <?= $message ?>
          </span>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }

      const alert = document.getElementById('notification-alert');

      alert.style.opacity = '0';
      alert.style.transform = 'translateX(50px)';

      setTimeout(() => {
        alert.style.opacity = '1';
        alert.style.transform = 'translateX(0)';
      }, 50);
      // Salida
      setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(20px)';

        setTimeout(() => {
          const container = document.getElementById('toast-container');
          if (container) container.remove();
        }, 300);
      }, 4000);
    });
  </script>

  <?php unset($_SESSION['alert_message']); ?>
<?php endif; ?>