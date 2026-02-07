<?php

$page_title = 'Mi Perfil';

$rolStyles = [
  'admin' => [
    'badge' => 'badge badge-soft badge-primary badge-sm',
    'icon'  => 'shield-check',
    'label' => 'Admin'
  ],
  'student' => [
    'badge' => 'badge badge-soft badge-info badge-sm',
    'icon'  => 'users',
    'label' => 'Estudiante'
  ],
  'teacher' => [
    'badge' => 'badge badge-soft badge-secondary badge-sm',
    'icon'  => 'user-check',
    'label' => 'Profesor'
  ],

];


$userRole = $_SESSION['user']['role'];
$style = $rolStyles[$userRole] ?? $rolStyles['student'];
?>

<div class="card w-full rounded-none min-h-screen bg-base-300">
  <div class="card-body bg-base-300">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-3xl text-base-content font-display font-black uppercase italic tracking-tighter">Mi Perfil</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Card de Avatar y Foto de Perfil -->
      <div class="md:col-span-1">
        <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
          <div class="card-body items-center text-center p-6">
            <div class="avatar mb-4">
              <div class="w-32 h-32 rounded-none border-3 border-black shadow-neo-sm">
                <img src="<?= BASE_URL . 'assets/upload/avatars/' . $_SESSION['user']['avatar'] ?>" alt="Avatar" id="profile-avatar-preview" class="object-cover w-full h-full" />
              </div>
            </div>

            <h3 class="font-display text-lg font-black text-base-content uppercase tracking-tight"><?= $_SESSION['user']['username'] ?></h3>

            <div class="<?= $style['badge'] ?> font-sans gap-1 mt-2 rounded-none border-2 border-black">
              <i data-lucide="<?= $style['icon'] ?>" class="size-3 stroke-[2.5]"></i>
              <span class="text-[10px] uppercase font-black tracking-wide">
                <?= $style['label'] ?>
              </span>
            </div>

            <div class="divider my-4"></div>

            <button onclick="change_avatar_modal.showModal()" class="btn btn-primary btn-sm w-full gap-2 rounded-none border-3 border-black shadow-neo-sm hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all font-black uppercase">
              <i data-lucide="camera" class="size-4"></i>
              Cambiar Foto
            </button>
          </div>
        </div>
      </div>

      <!-- Card de Información del Usuario -->
      <div class="md:col-span-2">
        <div class="card bg-base-100 shadow-neo border-3 border-black rounded-none">
          <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-6">
              <div class="p-3 bg-primary border-3 border-black shadow-neo-sm">
                <i data-lucide="user-circle" class="text-base-100 size-6 stroke-[2.5]"></i>
              </div>
              <h3 class="font-display text-xl font-black uppercase text-base-content tracking-tighter italic">Información Personal</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Email -->
              <div class="form-control">
                <label class="label pt-0">
                  <span class="label-text font-black uppercase text-[11px] tracking-wider">Correo Electrónico</span>
                </label>
                <div class="input input-bordered flex items-center gap-3 bg-base-200 border-2 border-black rounded-none shadow-neo-sm h-12">
                  <i data-lucide="mail" class="size-5 opacity-50 stroke-[2.5]"></i>
                  <span class="text-sm font-bold"><?= $_SESSION['user']['email'] ?></span>
                </div>
              </div>

              <!-- Rol -->
              <div class="form-control">
                <label class="label pt-0">
                  <span class="label-text font-black uppercase text-[11px] tracking-wider">Rol Administrativo</span>
                </label>
                <div class="input input-bordered flex items-center gap-3 bg-base-200 border-2 border-black rounded-none shadow-neo-sm h-12">
                  <i data-lucide="<?= $style['icon'] ?>" class="size-5 opacity-50 stroke-[2.5]"></i>
                  <span class="text-sm font-bold capitalize"><?= $style['label'] ?></span>
                </div>
              </div>

              <!-- ID de Usuario -->
              <div class="form-control">
                <label class="label pt-0">
                  <span class="label-text font-black uppercase text-[11px] tracking-wider">ID de Usuario</span>
                </label>
                <div class="input input-bordered flex items-center gap-3 bg-base-200 border-2 border-black rounded-none shadow-neo-sm h-12">
                  <i data-lucide="hash" class="size-5 opacity-50 stroke-[2.5]"></i>
                  <span class="text-sm font-bold font-mono"><?= str_pad($_SESSION['user']['id'], 4, '0', STR_PAD_LEFT) ?></span>
                </div>
              </div>
              <!-- courses -->
              <div class="form-control">
                <label class="label pt-0">
                  <span class="label-text font-black uppercase text-[11px] tracking-wider">Cursos Inscritos</span>
                </label>
                <div class="input input-bordered flex items-center gap-3 bg-base-200 border-2 border-black rounded-none shadow-neo-sm h-12">
                  <i data-lucide="hash" class="size-5 opacity-50 stroke-[2.5]"></i>
                  <span class="text-sm font-bold font-mono"><?= $countCourses ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Cambiar Avatar -->
<dialog id="change_avatar_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box bg-base-100 border-3 border-black rounded-none shadow-neo p-0 overflow-hidden max-w-md">
    <div class="flex items-center gap-3 p-6 border-b-3 border-black bg-base-200">
      <div class="p-3 bg-primary border-3 border-black shadow-neo-sm">
        <i data-lucide="camera" class="text-base-100 size-5 stroke-[2.5]"></i>
      </div>
      <h3 class="font-display text-xl font-black uppercase text-base-content tracking-tighter italic">Cambiar Foto de Perfil</h3>
    </div>

    <form action="<?= BASE_URL ?>procedures/profile/updateAvatar.proc.php" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
      <!-- Campo oculto para indicar el origen -->
      <input type="hidden" name="source" value="<?= isset($profile_source) ? $profile_source : 'dashboard' ?>">

      <div class="flex flex-col items-center gap-4">
        <div class="avatar">
          <div class="w-32 h-32 rounded-none border-3 border-black shadow-neo">
            <img src="<?= BASE_URL . 'assets/upload/avatars/' . $_SESSION['user']['avatar'] ?>" alt="Preview" id="modal-avatar-preview" class="object-cover w-full h-full" />
          </div>
        </div>

        <div class="w-full">
          <label class="label mb-1 pt-0">
            <span class="label-text font-black uppercase text-[11px] tracking-wider">Seleccionar Nueva Foto</span>
          </label>
          <input type="file" class="file-input file-input-bordered file-input-primary w-full rounded-none border-2 border-black shadow-neo-sm" name="avatar" accept="image/*" required onchange="previewNewAvatar(event)" />
          <label class="label">
            <span class="label-text-alt text-[10px] font-bold opacity-70">Tamaño máximo: 2MB. Formatos: JPG, PNG, GIF</span>
          </label>
          <label for="" id="avatar-error" class="label text-xs text-error font-bold hidden">⚠ El archivo seleccionado excede el tamaño máximo de 2MB.</label>
        </div>
      </div>

      <div class="modal-action gap-3 pt-4">
        <button type="button" class="btn btn-ghost font-black uppercase text-[11px] tracking-widest px-6 border-2 border-black rounded-none shadow-neo-sm hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all" onclick="change_avatar_modal.close()">
          Cancelar
        </button>
        <button type="submit" class="btn btn-primary font-black uppercase text-[11px] tracking-widest px-10 border-3 border-black rounded-none shadow-neo hover:translate-y-0.5 hover:translate-x-0.5 hover:shadow-none transition-all">
          Actualizar Foto
        </button>
      </div>
    </form>
  </div>
  <form method="dialog" class="modal-backdrop bg-black/50 backdrop-blur-sm">
    <button>close</button>
  </form>
</dialog>




<script>
  function previewNewAvatar(event) {
    const file = event.target.files[0];
    const maxSize = 2 * 1024 * 1024; // 2 MB en bytes

    if (file) {

      if (file.size > maxSize) {

        document.getElementById('avatar-error').classList.remove('hidden');
        event.target.value = ''; // Limpiar el input file
        return;

      }

      const reader = new FileReader();

      if (reader)

        reader.onload = function(e) {
          document.getElementById('modal-avatar-preview').src = e.target.result;
        };
      reader.readAsDataURL(file);
    }
  }
</script>