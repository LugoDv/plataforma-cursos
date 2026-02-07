<?php
require_once __DIR__ . '/../includes/config.php';
?>
<!DOCTYPE html>
<html lang="es" data-theme="nord">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Lugo Tech</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/output.css">
</head>

<body class="bg-base-300 font-sans">
  <?php require_once __DIR__ . '/../includes/error.php'; ?>



  <div class="min-h-screen flex flex-col items-center justify-center p-4 py-6">
    <div class="card w-full max-w-md bg-base-100 shadow-neo border-3 border-black rounded-none">
      <div class="card-body p-6">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
          <div class="flex justify-center mb-4">
            <a href="<?= BASE_URL ?>index.php">
              <div class="p-3 bg-primary border-3 border-black shadow-neo-sm hover:-translate-y-1 hover:-translate-x-1 hover:shadow-neo transition-all duration-300">
                <i data-lucide="graduation-cap" class="w-12 h-12 stroke-1"></i>

              </div>
            </a>
          </div>

          <h2 class="mt-3 text-center text-3xl font-black font-display uppercase italic tracking-tighter">Lugo Tech</h2>
          <p class="text-center text-[10px] uppercase font-black opacity-50 tracking-[0.2em] mt-1">Acceso a la plataforma</p>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-sm">
          <form action="<?= BASE_URL ?>procedures/login.proc.php" method="POST" class="space-y-4">
            <div class="form-control">
              <label class="label pt-0">
                <span class="label-text font-black uppercase text-[11px] tracking-wider">Correo Electrónico</span>
              </label>
              <label class="input validator w-full input-bordered rounded-none border-2 border-black bg-base-200 flex items-center gap-3 h-12 shadow-neo-sm focus-within:border-primary">
                <i data-lucide="mail" class="size-5 opacity-50 stroke-[2.5]"></i>
                <input name="email" type="email" placeholder="nombre@ejemplo.com" class="grow font-bold bg-transparent" required />
              </label>
              <div class="validator-hint font-bold text-[10px] uppercase mt-1">Introduce un email válido</div>
            </div>


            <div class="form-control">
              <label class="label pt-0">
                <span class="label-text font-black uppercase text-[11px] tracking-wider">Contraseña</span>
              </label>
              <label class="input validator w-full input-bordered rounded-none border-2 border-black bg-base-200 flex items-center gap-3 h-12 shadow-neo-sm focus-within:border-primary">
                <i data-lucide="lock" class="size-5 opacity-50 stroke-[2.5]"></i>
                <input name="password" type="password" required placeholder="••••••••" minlength="3" class="grow font-bold bg-transparent" />
              </label>
              <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 7 caracteres</div>
            </div>



            <div class="py-6">
              <button type="submit" class="btn btn-primary w-full h-14 rounded-none border-3 border-black text-lg font-display font-black uppercase italic shadow-neo hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all flex items-center justify-center gap-3">
                Entrar <i data-lucide="arrow-right" class="size-5 stroke-3"></i>
              </button>
            </div>
          </form>
        </div>
      </div>


      <p class="py-6 text-center text-sm font-bold uppercase tracking-tight">
        ¿No tienes cuenta?
        <a href="<?= BASE_URL ?>pages/signup.php" class="text-primary font-black underline decoration-black decoration-2 underline-offset-4 hover:bg-black hover:text-white px-2 py-1 transition-colors">Regístrate</a>
      </p>
    </div>
  </div>


  <script src="<?php echo BASE_URL; ?>assets/js/utils.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();
  </script>

</body>

</html>