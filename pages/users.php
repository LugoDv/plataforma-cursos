<?php
require_once '../includes/config.php';
require_once '../includes/connectDB.php';
require_once '../includes/utils.php';
$page_title = 'Usuarios';
include_once '../includes/dao/UserDao.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  redirect('index.php');
}
require_once '../includes/dashboard_header.php';
$userDao = new UserDao($db);
$users = $userDao->getAllUsers() ?? [];

$rolStyles = [
  'admin'   => ['badge' => 'badge  badge-primary badge-sm', 'icon' => 'shield-check', 'label' => 'Admin'],
  'teacher' => ['badge' => 'badge  badge-secondary badge-sm', 'icon' => 'graduation-cap', 'label' => 'Profesor'],
  'student' => ['badge' => 'badge  badge-accent badge-sm', 'icon' => 'book', 'label' => 'Estudiante']
];

?>

<div class="card w-full bg-base-300 rounded-none">
  <div class="card-body rounded-none">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-3xl text-base-content font-display uppercase italic">Usuarios</h2>
      <div class="breadcrumbs hidden sm:block font-sans text-xs uppercase font-bold opacity-60">
        <ul>
          <li><a href="<?= BASE_URL ?>pages/dashboard.php">Lugo Tech</a></li>
          <li>Gestión</li>
        </ul>
      </div>
    </div>

    <div class="mt-4">
      <div class="card bg-base-100 border-3 border-black shadow-neo rounded-none">
        <div class="card-body p-6 shadow-none">

          <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex gap-2 items-center flex-1">
              <label class="input input-bordered border-2 rounded-none border-black flex items-center gap-2 w-full max-w-xs shadow-neo-sm">
                <i data-lucide="search" class="size-4 opacity-70"></i>
                <input type="search" class="grow font-sans rounded-none" placeholder="Buscar usuario..." />
              </label>
            </div>

            <div class="flex gap-2 items-center">
              <button onclick="add_user_modal.showModal()" class="btn btn-primary border-3 border-black shadow-neo-sm rounded-none font-display italic">
                <i data-lucide="plus" class="size-4"></i>
                <span class="hidden sm:inline">Nuevo Usuario</span>
              </button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="table table-sm border-separate border-spacing-0 w-full border-2 border-black">
              <thead class="bg-base-200 font-display italic uppercase ">
                <tr>
                  <th class="border-b-2 border-r-2 border-black">ID</th>
                  <th class="border-b-2 border-r-2 border-black">Nombre</th>
                  <th class="border-b-2 border-r-2 border-black text-center">Avatar</th>
                  <th class="border-b-2 border-r-2 border-black">Email</th>
                  <th class="border-b-2 border-r-2 border-black">Rol</th>
                  <th class="border-b-2 border-black text-center">Acciones</th>
                </tr>
              </thead>
              <tbody class="font-sans">
                <?php foreach ($users as $user):
                  $style = $rolStyles[$user['role']] ?? $rolStyles['default'];
                ?>
                  <tr class="hover:bg-base-200 transition-colors">
                    <td class="font-bold border-r-2 border-b-2 border-black"><?= $user['id'] ?></td>
                    <td class="border-r-2 border-b-2 border-black"><?= $user['username'] ?></td>
                    <td class="border-r-2 border-b-2 border-black text-center">
                      <div class="avatar flex justify-center">
                        <div class="w-10 h-10 rounded-full border-2 border-black overflow-hidden shadow-neo-sm">
                          <img src="<?= BASE_URL . "assets/upload/avatars/" . $user['profile_picture'] ?>" alt="Avatar" />
                        </div>
                      </div>
                    </td>
                    <td class="border-r-2 border-b-2 border-black"><?= $user['email'] ?></td>
                    <td class="border-r-2 border-b-2 border-black">
                      <div class="<?= $style['badge'] ?> border-2 border-black gap-1 px-3 py-1 shadow-neo-sm">
                        <i data-lucide="<?= $style['icon'] ?>" class="size-3 stroke-3"></i>
                        <span class="text-[10px] uppercase font-black italic tracking-tighter">
                          <?= $style['label'] ?>
                        </span>
                      </div>
                    </td>
                    <td class="border-b-2 border-black">
                      <?php if ($user['role'] !== 'admin'): ?>
                        <div class="flex justify-center gap-2">
                          <button onclick="updateUser('<?= $user['id'] ?>','<?= $user['username'] ?>','<?= $user['email'] ?>','<?= $user['role'] ?>','<?= $user['profile_picture'] ?>')" class="btn btn-square btn-xs border-2 border-black shadow-neo-sm hover:bg-info rounded-none" title="Editar">
                            <i data-lucide="pencil" class="size-3"></i>
                          </button>
                          <button class="btn btn-square btn-xs border-2 border-black shadow-neo-sm btn-error rounded-none" onclick="confirmDeleteUser('<?= $user['id'] ?>', '<?= $user['email'] ?>')" title="Borrar">
                            <i data-lucide="trash-2" class="size-3"></i>
                          </button>
                        </div>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="flex items-center justify-between mt-6 bg-base-200 p-3 border-2 border-black shadow-neo-sm">
            <span class="text-xs font-bold uppercase opacity-70 italic">Página 1 de 10</span>
            <div class="join border-2 border-black">
              <button class="join-item btn btn-xs border-r-2 border-black">«</button>
              <button class="join-item btn btn-xs bg-primary text-white font-bold italic border-r-2 border-black">1</button>
              <button class="join-item btn btn-xs">»</button>
            </div>
          </div>

          <!-- modal eliminar usuario -->
          <dialog id="user_delete_modal" class="modal">
            <div class="modal-box p-0 rounded-none border-3 border-black shadow-neo-lg bg-base-100 max-w-sm overflow-hidden">

              <div class="bg-error p-4 border-b-2 border-black flex items-center justify-between">
                <div class="flex items-center gap-2 text-black">
                  <i data-lucide="alert-triangle" class="size-5 stroke-3"></i>
                  <h3 class="font-display italic uppercase text-lg tracking-tighter">Confirmar Borrado</h3>
                </div>
                <form method="dialog">
                  <button class="btn btn-sm btn-ghost btn-square border-none hover:bg-black/10">
                    <i data-lucide="x" class="size-4 text-black"></i>
                  </button>
                </form>
              </div>

              <div class="p-6">
                <p class="font-sans text-base-content text-sm mb-2 uppercase font-black opacity-60 tracking-widest">Atención</p>
                <p class="font-sans text-lg">
                  ¿Estás seguro que deseas borrar a <br>
                  <span id="delete_user_name" class="font-display italic text-error underline decoration-black decoration-2"></span>?
                </p>

                <p class="text-[10px] mt-6 uppercase font-black opacity-40 leading-tight">
                  Esta acción es irreversible y eliminará permanentemente los datos y la foto de perfil del servidor.
                </p>

                <div class="modal-action mt-8 flex gap-3">
                  <form method="dialog" class="flex-1">
                    <button class="btn btn-ghost w-full rounded-none border-2 border-black font-black uppercase text-xs hover:bg-base-300">
                      No, cancelar
                    </button>
                  </form>

                  <form method="post" action="<?= BASE_URL ?>procedures/userDelete.proc.php" class="flex-1">
                    <input type="hidden" name="user_id" id="delete_user_id_input">
                    <button type="submit" class="btn btn-error w-full rounded-none border-2 border-black shadow-neo-sm font-black uppercase text-xs hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all">
                      Si, eliminar
                    </button>
                  </form>
                </div>
              </div>
            </div>

            <form method="dialog" class="modal-backdrop bg-black/70 backdrop-blur-none">
              <button>close</button>
            </form>
          </dialog>

        </div>
      </div>
    </div>
  </div>
</div>


<!-- crear usuario modal -->
<dialog id="add_user_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box bg-base-100 p-0 max-w-md max-h-[90vh] flex flex-col rounded-none border-3 border-black shadow-neo-lg">

    <div class="flex items-center gap-3 p-6 pb-6 border-b-2 border-black bg-base-200 shrink-0">
      <div class="bg-primary border-2 border-black p-2 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
        <i data-lucide="user-plus" class="text-black size-5 stroke-[2.5]"></i>
      </div>
      <h3 class="font-display italic text-2xl uppercase tracking-tighter">Registrar Usuario</h3>
    </div>

    <form action="<?= BASE_URL ?>procedures/userCreate.proc.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-5 overflow-y-auto">

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Username</span>
        </label>
        <label class="input validator input-bordered rounded-none border-2 border-black bg-base-100 w-full">
          <input type="text" name="username" placeholder="Ej: lugo_dev" required minlength="3" />
        </label>
        <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 3 caracteres</div>
      </div>

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Correo Electrónico</span>
        </label>
        <label class="input validator input-bordered rounded-none border-2 border-black bg-base-100 w-full">
          <i data-lucide="mail" class="size-4 opacity-50"></i>
          <input type="email" name="email" placeholder="mail@site.com" required />
        </label>
        <div class="validator-hint font-bold text-[10px] uppercase mt-1">Ingrese un email válido</div>
      </div>

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Contraseña</span>
        </label>
        <label class="input validator input-bordered rounded-none border-2 border-black bg-base-100 w-full">
          <i data-lucide="key" class="size-4 opacity-50"></i>
          <input type="password" name="password" required minlength="8" placeholder="••••••••" />
        </label>
        <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 8 caracteres</div>
      </div>

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Rol de Sistema</span>
        </label>
        <select name="role" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
          <option value="student">Estudiante</option>
          <option value="teacher">Profesor</option>
        </select>
      </div>

      <div class="w-full">
        <div class="flex items-center gap-5 p-4 bg-base-200 border-2 border-black shadow-neo-sm rounded-none">
          <div class="avatar">
            <div class="w-20 h-20 rounded-none border-2 border-black bg-base-300">
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

      <div class="modal-action mt-6 gap-3">
        <button type="button" class="btn btn-ghost rounded-none border-2 border-black font-black uppercase text-[11px] px-6" onclick="add_user_modal.close()">
          Cancelar
        </button>
        <button type="submit" class="btn btn-primary rounded-none border-3 border-black font-black uppercase text-[11px] px-10 shadow-neo-sm hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-none transition-all">
          Guardar Usuario
        </button>
      </div>

    </form>
  </div>
  <form method="dialog" class="modal-backdrop bg-black/70"><button>close</button></form>
</dialog>
<!-- editar usuario -->
<dialog id="edit_user_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box bg-base-100 p-0 max-w-md max-h-[90vh] flex flex-col rounded-none border-3 border-black shadow-neo-lg">

    <div class="flex items-center gap-3 p-6 pb-6 border-b-2 border-black bg-base-200 shrink-0">
      <div class="bg-primary border-2 border-black p-2 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
        <i data-lucide="pencil" class="text-black size-5 stroke-[2.5]"></i>
      </div>
      <h3 class="font-display italic text-2xl uppercase tracking-tighter">Editar Usuario</h3>
    </div>

    <form action="<?= BASE_URL ?>procedures/userUpdate.proc.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 overflow-y-auto">
      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Username</span>
        </label>
        <label class="input validator input-bordered rounded-none border-2 border-black bg-base-100 w-full">
          <input type="text" name="username" placeholder="Ej: lugo_dev" required minlength="3" />
        </label>
        <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 3 caracteres</div>
      </div>

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Correo Electrónico</span>
        </label>
        <label class="input validator input-bordered rounded-none border-2 border-black bg-base-100 w-full">
          <i data-lucide="mail" class="size-4 opacity-50"></i>
          <input type="email" placeholder="mail@site.com" name="email" id="edit_email_input" required />
        </label>
        <div class="validator-hint font-bold text-[10px] uppercase mt-1">Ingrese un email válido</div>
      </div>

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Rol Administrativo</span>
        </label>
        <select name="role" id="edit_role_input" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
          <option value="student">Estudiante</option>
          <option value="teacher">Profesor</option>
        </select>
      </div>

      <div class="w-full">
        <div class="flex items-center gap-5 p-4 bg-base-200 border-2 border-black shadow-neo-sm rounded-none mb-2">
          <div class="avatar">
            <div class="w-20 h-20 rounded-none border-2 border-black bg-base-300 shadow-neo-sm overflow-hidden">
              <img src="" id="preview-img" class="object-cover w-full h-full" alt="Foto actual" />
            </div>
          </div>

          <div class="flex-1">
            <h4 class="text-[11px] font-black uppercase tracking-widest mb-1">Imagen de Perfil</h4>
            <p class="text-[9px] leading-tight opacity-70 mb-3">Sube un nuevo archivo para actualizar la foto.</p>
            <input type="file" name="avatar" id="avatar-input"
              class="file-input file-input-bordered rounded-none border-2 border-black file-input-xs w-full bg-base-100"
              onchange="previewNewAvatar(event)" />
            <p class="text-error text-[10px] font-bold mt-1 hidden" id="avatar-error-edit">El archivo no debe superar 2 MB</p>
          </div>
        </div>
        <label class="label text-[10px] font-bold opacity-60 uppercase">Máx: 2MB | JPG, PNG, GIF</label>
      </div>

      <input type="hidden" name="user_id" id="user_edit_input" />
      <input type="hidden" name="userAvatarPath" id="user_edit_avatar" />

      <div class="modal-action mt-6 gap-3">
        <button type="button" class="btn btn-ghost rounded-none border-2 border-black font-black uppercase text-[11px] px-6" onclick="edit_user_modal.close()">
          Cancelar
        </button>
        <button type="submit" class="btn btn-primary rounded-none border-3 border-black font-black uppercase text-[11px] px-10 shadow-neo-sm hover:translate-x-px hover:translate-y-px hover:shadow-none transition-all">
          Actualizar
        </button>
      </div>

    </form>
  </div>

  <form method="dialog" class="modal-backdrop bg-black/70 backdrop-blur-none">
    <button>close</button>
  </form>
</dialog>

<script>
  function confirmDeleteUser(id, name) {
    const nameSpan = document.getElementById('delete_user_name');
    const idInput = document.getElementById('delete_user_id_input');
    const modal = document.getElementById('user_delete_modal');

    if (nameSpan && modal) {
      nameSpan.textContent = name;
      idInput.value = id;

      // 3. Abrimos el modal de DaisyUI
      modal.showModal();
    }
  }

  function updateUser(id, username, email, role, avatar) {
    const modal = document.getElementById('edit_user_modal');
    const usernameInput = modal.querySelector('input[name="username"]');
    const emailInput = modal.querySelector('input[name="email"]');
    const roleSelect = modal.querySelector('select[name="role"]');
    const userIdInput = document.getElementById('user_edit_input');
    const userAvatarInput = document.getElementById('user_edit_avatar');
    const previewImage = document.getElementById("preview-img");

    if (emailInput && roleSelect && userIdInput && modal && userAvatarInput && previewImage && usernameInput) {
      usernameInput.value = username;
      emailInput.value = email;
      roleSelect.value = role;
      userIdInput.value = id;
      userAvatarInput.value = avatar;
      previewImage.src = "<?= BASE_URL ?>" + "assets/upload/avatars/" + avatar;

      // 3. Abrimos el modal de DaisyUI
      modal.showModal();
    }
  }

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

  // Función para limpiar el modal de nuevo usuario
  function resetNewUserModal() {
    const modal = document.getElementById('add_user_modal');
    const form = modal.querySelector('form');
    const previewImg = document.getElementById('preview-img-new');
    const errorLabel = document.getElementById('avatar-error-new');

    form.reset();

    previewImg.src = '<?= BASE_URL ?>assets/images/placeholder_img.jpg';

    errorLabel.classList.add('hidden');
  }

  // Función para limpiar el modal de editar usuario
  function resetEditUserModal() {
    const modal = document.getElementById('edit_user_modal');
    const form = modal.querySelector('form');
    const errorLabel = document.getElementById('avatar-error-edit');
    const avatarInput = document.getElementById('avatar-input');

    form.reset();

    avatarInput.value = '';

    errorLabel.classList.add('hidden');
  }

  // Event listeners para detectar cuando se cierran los modales
  document.addEventListener('DOMContentLoaded', function() {
    const addUserModal = document.getElementById('add_user_modal');
    const editUserModal = document.getElementById('edit_user_modal');

    addUserModal.addEventListener('close', resetNewUserModal);

    editUserModal.addEventListener('close', resetEditUserModal);
  });
</script>

<?php require_once '../includes/dashboard_footer.php'; ?>