<?php
require_once __DIR__ . '/../includes/config.php';
?>
<!DOCTYPE html>
<html lang="es" data-theme="nord">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup - Lugo Tech</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/output.css">
</head>

<body class="bg-base-300 font-sans">
  <?php require_once __DIR__ . '/../includes/error.php'; ?>



  <div class="min-h-screen flex flex-col items-center justify-center p-4 py-6">
    <div class="card w-full max-w-md bg-base-100 shadow-neo border-3 border-black rounded-none">
      <div class="card-body p-6">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
          <div class="flex justify-center mb-4">
            <div class="p-3 bg-primary border-3 border-black shadow-neo-sm hover:-translate-y-1 hover:-translate-x-1 hover:shadow-neo transition-all duration-300">
              <i data-lucide="graduation-cap" class="w-12 h-12 stroke-1"></i>
            </div>
          </div>

          <h2 class="mt-3 text-center text-3xl font-black font-display uppercase italic tracking-tighter">Lugo Tech</h2>
          <p class="text-center text-[10px] uppercase font-black opacity-50 tracking-[0.2em] mt-1">Registro</p>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-sm">
          <form action="<?= BASE_URL ?>procedures/auth/signup.proc.php" enctype="multipart/form-data" method="POST" class="space-y-4">
            <div class="form-control">

              <label class="label pt-0">
                <span class="label-text font-black uppercase text-[11px] tracking-wider">Nombre</span>
              </label>
              <label class="input validator w-full input-bordered rounded-none border-2 border-black bg-base-200 flex items-center gap-3 h-12 shadow-neo-sm focus-within:border-primary">
                <i data-lucide="user" class="size-5 opacity-50 stroke-[2.5]"></i>
                <input name="name" type="text" placeholder="fabian" class="grow font-bold bg-transparent" required />
              </label>
              <div class="validator-hint font-bold text-[10px] uppercase mt-1">Introduce un nombre válido</div>
            </div>


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


            <div class="w-full">
              <div class="flex items-center gap-3 p-3 bg-base-200 border-2 border-black shadow-neo-sm rounded-none">
                <div class="avatar">
                  <div class="w-16 h-16 rounded-none border-2 border-black bg-base-300">
                    <img src="<?= BASE_URL ?>assets/images/placeholder_img.jpg" id="preview-img-new" class="object-cover" alt="Preview" />
                  </div>
                </div>
                <div class="flex-1">
                  <h4 class="text-[11px] font-black uppercase tracking-widest mb-1">Imagen de Perfil</h4>
                  <input type="file" name="avatar" id="avatar-input-new" class="file-input file-input-bordered rounded-none border-2 border-black file-input-xs w-full bg-base-100" accept="image/*" onchange="previewNewAvatar(event)" />
                  <p class="text-error text-[10px] font-bold mt-1 hidden" id="avatar-error-new">⚠ El archivo no debe superar 2 MB</p>
                </div>
              </div>
            </div>



            <div class="pt-6">
              <button type="submit" class="btn btn-primary w-full h-14 rounded-none border-3 border-black text-lg font-display font-black uppercase italic shadow-neo hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all flex items-center justify-center gap-3">
                Registrarse <i data-lucide="arrow-right" class="size-5 stroke-3"></i>
              </button>
            </div>
          </form>
        </div>
      </div>


      <p class="mb-6 text-center text-sm font-bold uppercase tracking-tight">
        ¿Ya tienes cuenta?
        <a href="<?= BASE_URL ?>pages/login.php" class="text-primary  font-black underline decoration-black decoration-2 underline-offset-4 hover:bg-black hover:text-white px-2 py-1 transition-colors">Inicia sesión</a>
      </p>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();

    function previewNewAvatar(event) {
      const file = event.target.files[0];
      const maxSize = 2 * 1024 * 1024; // 2 MB en bytes
      const inputId = event.target.id;
      const previewImg = inputId === 'avatar-input-new' ?
        document.getElementById('preview-img-new') :
        document.getElementById('preview-img');
      const errorLabel = inputId === 'avatar-input-new' ?
        document.getElementById('avatar-error-new') :
        document.getElementById('avatar-error-edit');

      if (file) {

        if (file.size > maxSize) {

          errorLabel.classList.remove('hidden');
          event.target.value = ''; // Limpiar el input file
          // Restaurar placeholder si es el modal de nuevo usuario
          if (inputId === 'avatar-input-new') {
            previewImg.src = '<?= BASE_URL ?>assets/images/placeholder_img.jpg';
          } else {
            // Para el modal de edición, mantener la imagen actual
            const currentAvatar = document.getElementById('user_edit_avatar').value;
            previewImg.src = '<?= BASE_URL ?>assets/upload/avatars/' + currentAvatar;
          }


          return;

        }

        const reader = new FileReader();

        if (reader) {
          reader.onload = function(e) {
            previewImg.src = e.target.result;
          };
          reader.readAsDataURL(file);
        }

        // Ocultar error si estaba visible
        errorLabel.classList.add('hidden');
      }
    }
  </script>

</body>

</html>