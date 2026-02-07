<?php
require_once '../includes/config.php';
require_once '../includes/connectDB.php';
require_once '../includes/utils.php';
$page_title = 'Cursos';
include_once '../includes/dao/CourseDao.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  redirect('index.php');
}
require_once '../includes/dashboard_header.php';

$courseDao = new CourseDao($db);
$courses = $courseDao->getAllCourses() ?? [];
$teachers = $courseDao->getAllTeachers() ?? [];

$categoryStyles = [
  'backend'  => ['badge' => 'badge badge-primary badge-sm', 'icon' => 'server', 'label' => 'Backend'],
  'frontend' => ['badge' => 'badge badge-secondary badge-sm', 'icon' => 'layout', 'label' => 'Frontend']
];

$statusStyles = [
  'pending'   => ['badge' => 'badge badge-warning badge-sm', 'icon' => 'clock', 'label' => 'Pendiente'],
  'published' => ['badge' => 'badge badge-success badge-sm', 'icon' => 'check-circle', 'label' => 'Publicado']
];

?>

<div class="card w-full bg-base-300 rounded-none">
  <div class="card-body rounded-none">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-3xl text-base-content font-display uppercase italic">Cursos</h2>
      <div class="breadcrumbs hidden sm:block font-sans text-xs uppercase font-bold opacity-60">
        <ul>
          <li><a href="<?= BASE_URL ?>pages/dashboard.php">Lugo Tech</a></li>
          <li>Gestión de Cursos</li>
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
                <input type="search" class="grow font-sans rounded-none" placeholder="Buscar curso..." />
              </label>
            </div>

            <div class="flex gap-2 items-center">
              <button onclick="add_course_modal.showModal()" class="btn btn-primary border-3 border-black shadow-neo-sm rounded-none font-display italic">
                <i data-lucide="plus" class="size-4"></i>
                <span class="hidden sm:inline">Nuevo Curso</span>
              </button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="table table-sm border-separate border-spacing-0 w-full border-2 border-black">
              <thead class="bg-base-200 font-display italic uppercase">
                <tr>
                  <th class="border-b-2 border-r-2 border-black">ID</th>
                  <th class="border-b-2 border-r-2 border-black">Título</th>
                  <th class="border-b-2 border-r-2 border-black text-center">Miniatura</th>
                  <th class="border-b-2 border-r-2 border-black">Profesor</th>
                  <th class="border-b-2 border-r-2 border-black">Categoría</th>
                  <th class="border-b-2 border-r-2 border-black">Estado</th>
                  <th class="border-b-2 border-black text-center">Acciones</th>
                </tr>
              </thead>
              <tbody class="font-sans">
                <?php foreach ($courses as $course):
                  $catStyle = $categoryStyles[$course['category']] ?? $categoryStyles['backend'];
                  $statusStyle = $statusStyles[$course['status']] ?? $statusStyles['pending'];
                ?>
                  <tr class="hover:bg-base-200 transition-colors">
                    <td class="font-bold border-r-2 border-b-2 border-black"><?= $course['id'] ?></td>
                    <td class="border-r-2 border-b-2 border-black"><?= $course['title'] ?></td>
                    <td class="border-r-2 border-b-2 border-black text-center">
                      <div class="avatar flex justify-center">
                        <div class="w-16 h-10 rounded-none border-2 border-black overflow-hidden shadow-neo-sm">
                          <img src="<?= BASE_URL . "assets/upload/thumbnails/" . $course['thumbnail'] ?>" alt="Thumbnail" class="object-cover" />
                        </div>
                      </div>
                    </td>
                    <td class="border-r-2 border-b-2 border-black"><?= $course['teacher_name'] ?? 'N/A' ?></td>
                    <td class="border-r-2 border-b-2 border-black">
                      <div class="<?= $catStyle['badge'] ?> border-2 border-black gap-1 px-3 py-1 shadow-neo-sm">
                        <i data-lucide="<?= $catStyle['icon'] ?>" class="size-3 stroke-3"></i>
                        <span class="text-[10px] uppercase font-black italic tracking-tighter">
                          <?= $catStyle['label'] ?>
                        </span>
                      </div>
                    </td>
                    <td class="border-r-2 border-b-2 border-black">
                      <div class="<?= $statusStyle['badge'] ?> border-2 border-black gap-1 px-3 py-1 shadow-neo-sm">
                        <i data-lucide="<?= $statusStyle['icon'] ?>" class="size-3 stroke-3"></i>
                        <span class="text-[10px] uppercase font-black italic tracking-tighter">
                          <?= $statusStyle['label'] ?>
                        </span>
                      </div>
                    </td>
                    <td class="border-b-2 border-black">
                      <div class="flex justify-center gap-2">
                        <button onclick="updateCourse('<?= $course['id'] ?>','<?= $course['teacher_id'] ?>','<?= htmlspecialchars($course['title'], ENT_QUOTES) ?>','<?= htmlspecialchars($course['description'], ENT_QUOTES) ?>','<?= $course['category'] ?>','<?= $course['status'] ?>','<?= $course['thumbnail'] ?>')" class="btn btn-square btn-xs border-2 border-black shadow-neo-sm hover:bg-info rounded-none" title="Editar">
                          <i data-lucide="pencil" class="size-3"></i>
                        </button>
                        <button class="btn btn-square btn-xs border-2 border-black shadow-neo-sm btn-error rounded-none" onclick="confirmDeleteCourse('<?= $course['id'] ?>', '<?= htmlspecialchars($course['title'], ENT_QUOTES) ?>')" title="Borrar">
                          <i data-lucide="trash-2" class="size-3"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="flex items-center justify-between mt-6 bg-base-200 p-3 border-2 border-black shadow-neo-sm">
            <span class="text-xs font-bold uppercase opacity-70 italic">Total: <?= count($courses) ?> cursos</span>
            <div class="join border-2 border-black">
              <button class="join-item btn btn-xs border-r-2 border-black">«</button>
              <button class="join-item btn btn-xs bg-primary text-white font-bold italic border-r-2 border-black">1</button>
              <button class="join-item btn btn-xs">»</button>
            </div>
          </div>

          <!-- modal eliminar curso -->
          <dialog id="course_delete_modal" class="modal">
            <div class="modal-box p-0 rounded-none border-3 border-black shadow-neo-lg bg-base-100 max-w-sm overflow-hidden">

              <div class="bg-error p-4 border-b-2 border-black flex items-center justify-between">
                <div class="flex items-center gap-2 text-black">
                  <i data-lucide="alert-triangle" class="size-5 stroke-3"></i>
                  <h3 class="font-display italic uppercase text-lg tracking-tighter">Confirmar Borrado</h3>
                </div>
                <form method="dialog">
                  <button class="btn btn-sm btn-ghost btn-circle text-black hover:bg-black/10">
                    <i data-lucide="x" class="size-5"></i>
                  </button>
                </form>
              </div>

              <div class="p-6 space-y-4">
                <p class="text-sm font-sans">
                  ¿Estás seguro de que deseas eliminar el curso <strong id="delete_course_name"></strong>? Esta acción no se puede deshacer.
                </p>

                <div class="bg-base-200 p-3 border-2 border-black rounded-none">
                  <div class="flex items-start gap-2 text-xs">
                    <i data-lucide="info" class="size-4 mt-0.5 opacity-70"></i>
                    <p class="opacity-70 font-bold uppercase">Se eliminarán todas las lecciones asociadas</p>
                  </div>
                </div>
              </div>

              <form action="<?= BASE_URL ?>procedures/courseDelete.proc.php" method="POST" class="p-6 pt-0">
                <input type="hidden" name="course_id" id="delete_course_id_input" />
                <div class="flex gap-3 justify-end">
                  <button type="button" onclick="course_delete_modal.close()" class="btn btn-ghost rounded-none border-2 border-black font-black uppercase text-[11px]">
                    Cancelar
                  </button>
                  <button type="submit" class="btn btn-error rounded-none border-3 border-black font-black uppercase text-[11px] shadow-neo-sm">
                    <i data-lucide="trash-2" class="size-4"></i>
                    Eliminar
                  </button>
                </div>
              </form>

            </div>
            <form method="dialog" class="modal-backdrop bg-black/70"><button>close</button></form>
          </dialog>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- crear curso modal -->
<dialog id="add_course_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box bg-base-100 p-0 max-w-2xl max-h-[90vh] flex flex-col rounded-none border-3 border-black shadow-neo-lg">

    <div class="flex items-center gap-3 p-6 pb-6 border-b-2 border-black bg-base-200 shrink-0">
      <div class="bg-primary border-2 border-black p-2 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
        <i data-lucide="book-plus" class="text-black size-5 stroke-[2.5]"></i>
      </div>
      <h3 class="font-display italic text-2xl uppercase tracking-tighter">Crear Nuevo Curso</h3>
    </div>

    <form action="<?= BASE_URL ?>procedures/courseCreate.proc.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-5 overflow-y-auto">

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Título del Curso</span>
        </label>
        <label class="input validator input-bordered rounded-none border-2 border-black bg-base-100 w-full">
          <input type="text" name="title" placeholder="Ej: Desarrollo Web con PHP" required minlength="3" />
        </label>
        <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 3 caracteres</div>
      </div>

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Descripción</span>
        </label>
        <textarea name="description" class="textarea textarea-bordered rounded-none border-2 border-black bg-base-100 w-full" placeholder="Descripción del curso..." rows="3" required minlength="10"></textarea>
        <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 10 caracteres</div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-control w-full">
          <label class="label mb-0">
            <span class="label-text font-black uppercase text-[11px] tracking-wider">Profesor</span>
          </label>
          <select name="teacher_id" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
            <option value="" disabled selected>Selecciona un profesor</option>
            <?php foreach ($teachers as $teacher): ?>
              <option value="<?= $teacher['id'] ?>"><?= $teacher['username'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-control w-full">
          <label class="label mb-0">
            <span class="label-text font-black uppercase text-[11px] tracking-wider">Categoría</span>
          </label>
          <select name="category" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
            <option value="backend">Backend</option>
            <option value="frontend">Frontend</option>
          </select>
        </div>
      </div>

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Estado</span>
        </label>
        <select name="status" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
          <option value="pending">Pendiente</option>
          <option value="published">Publicado</option>
        </select>
      </div>

      <div class="w-full">
        <div class="flex items-center gap-5 p-4 bg-base-200 border-2 border-black shadow-neo-sm rounded-none">
          <div class="avatar">
            <div class="w-24 h-16 rounded-none border-2 border-black bg-base-300">
              <img src="<?= BASE_URL ?>assets/images/placeholder_img.jpg" id="preview-thumbnail-new" class="object-cover" alt="Preview" />
            </div>
          </div>
          <div class="flex-1">
            <h4 class="text-[11px] font-black uppercase tracking-widest mb-1">Miniatura del Curso</h4>
            <input type="file" name="thumbnail" id="thumbnail-input-new" class="file-input file-input-bordered rounded-none border-2 border-black file-input-xs w-full bg-base-100" accept="image/*" onchange="previewNewThumbnail(event)" />
            <p class="text-error text-[10px] font-bold mt-1 hidden" id="thumbnail-error-new">⚠ El archivo no debe superar 2 MB</p>
          </div>
        </div>
      </div>

      <div class="modal-action mt-6 gap-3">
        <button type="button" class="btn btn-ghost rounded-none border-2 border-black font-black uppercase text-[11px] px-6" onclick="add_course_modal.close()">
          Cancelar
        </button>
        <button type="submit" class="btn btn-primary rounded-none border-3 border-black font-black uppercase text-[11px] px-10 shadow-neo-sm hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-none transition-all">
          Guardar Curso
        </button>
      </div>

    </form>
  </div>
  <form method="dialog" class="modal-backdrop bg-black/70"><button>close</button></form>
</dialog>

<!-- editar curso modal -->
<dialog id="edit_course_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box bg-base-100 p-0 max-w-2xl max-h-[90vh] flex flex-col rounded-none border-3 border-black shadow-neo-lg">

    <div class="flex items-center gap-3 p-6 pb-6 border-b-2 border-black bg-base-200 shrink-0">
      <div class="bg-primary border-2 border-black p-2 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
        <i data-lucide="pencil" class="text-black size-5 stroke-[2.5]"></i>
      </div>
      <h3 class="font-display italic text-2xl uppercase tracking-tighter">Editar Curso</h3>
    </div>

    <form action="<?= BASE_URL ?>procedures/courseUpdate.proc.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 overflow-y-auto">

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Título del Curso</span>
        </label>
        <label class="input validator input-bordered rounded-none border-2 border-black bg-base-100 w-full">
          <input type="text" name="title" id="edit_title_input" placeholder="Título del curso" required minlength="3" />
        </label>
        <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 3 caracteres</div>
      </div>

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Descripción</span>
        </label>
        <textarea name="description" id="edit_description_input" class="textarea textarea-bordered rounded-none border-2 border-black bg-base-100 w-full" placeholder="Descripción del curso..." rows="3" required minlength="10"></textarea>
        <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 10 caracteres</div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-control w-full">
          <label class="label mb-0">
            <span class="label-text font-black uppercase text-[11px] tracking-wider">Profesor</span>
          </label>
          <select name="teacher_id" id="edit_teacher_input" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
            <?php foreach ($teachers as $teacher): ?>
              <option value="<?= $teacher['id'] ?>"><?= $teacher['username'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-control w-full">
          <label class="label mb-0">
            <span class="label-text font-black uppercase text-[11px] tracking-wider">Categoría</span>
          </label>
          <select name="category" id="edit_category_input" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
            <option value="backend">Backend</option>
            <option value="frontend">Frontend</option>
          </select>
        </div>
      </div>

      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Estado</span>
        </label>
        <select name="status" id="edit_status_input" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
          <option value="pending">Pendiente</option>
          <option value="published">Publicado</option>
        </select>
      </div>

      <div class="w-full">
        <div class="flex items-center gap-5 p-4 bg-base-200 border-2 border-black shadow-neo-sm rounded-none mb-2">
          <div class="avatar">
            <div class="w-24 h-16 rounded-none border-2 border-black bg-base-300 shadow-neo-sm overflow-hidden">
              <img src="" id="preview-thumbnail" class="object-cover w-full h-full" alt="Miniatura actual" />
            </div>
          </div>

          <div class="flex-1">
            <h4 class="text-[11px] font-black uppercase tracking-widest mb-1">Miniatura del Curso</h4>
            <p class="text-[9px] leading-tight opacity-70 mb-3">Sube un nuevo archivo para actualizar la miniatura.</p>
            <input type="file" name="thumbnail" id="thumbnail-input"
              class="file-input file-input-bordered rounded-none border-2 border-black file-input-xs w-full bg-base-100"
              onchange="previewNewThumbnail(event)" />
            <p class="text-error text-[10px] font-bold mt-1 hidden" id="thumbnail-error-edit">⚠ El archivo no debe superar 2 MB</p>
          </div>
        </div>
        <label class="label text-[10px] font-bold opacity-60 uppercase">Máx: 2MB | JPG, PNG, GIF</label>
      </div>

      <input type="hidden" name="course_id" id="course_edit_input" />

      <div class="modal-action mt-6 gap-3">
        <button type="button" class="btn btn-ghost rounded-none border-2 border-black font-black uppercase text-[11px] px-6" onclick="edit_course_modal.close()">
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
  function confirmDeleteCourse(id, title) {
    const titleSpan = document.getElementById('delete_course_name');
    const idInput = document.getElementById('delete_course_id_input');
    const modal = document.getElementById('course_delete_modal');

    if (titleSpan && modal) {
      titleSpan.textContent = title;
      idInput.value = id;
      modal.showModal();
    }
  }

  function updateCourse(id, teacherId, title, description, category, status, thumbnail) {
    const modal = document.getElementById('edit_course_modal');
    const titleInput = document.getElementById('edit_title_input');
    const descriptionInput = document.getElementById('edit_description_input');
    const teacherSelect = document.getElementById('edit_teacher_input');
    const categorySelect = document.getElementById('edit_category_input');
    const statusSelect = document.getElementById('edit_status_input');
    const courseIdInput = document.getElementById('course_edit_input');
    const previewImage = document.getElementById("preview-thumbnail");

    if (titleInput && descriptionInput && teacherSelect && categorySelect && statusSelect && courseIdInput && modal && previewImage) {
      titleInput.value = title;
      descriptionInput.value = description;
      teacherSelect.value = teacherId;
      categorySelect.value = category;
      statusSelect.value = status;
      courseIdInput.value = id;
      previewImage.src = "<?= BASE_URL ?>assets/upload/thumbnails/" + thumbnail;

      modal.showModal();
    }
  }

  function previewNewThumbnail(event) {
    const file = event.target.files[0];
    const maxSize = 2 * 1024 * 1024; // 2 MB en bytes
    const inputId = event.target.id;
    const previewImg = inputId === 'thumbnail-input-new' ?
      document.getElementById('preview-thumbnail-new') :
      document.getElementById('preview-thumbnail');
    const errorLabel = inputId === 'thumbnail-input-new' ?
      document.getElementById('thumbnail-error-new') :
      document.getElementById('thumbnail-error-edit');

    if (file) {

      if (file.size > maxSize) {

        errorLabel.classList.remove('hidden');
        event.target.value = ''; // Limpiar el input file
        // Restaurar placeholder si es el modal de nuevo curso
        if (inputId === 'thumbnail-input-new') {
          previewImg.src = '<?= BASE_URL ?>assets/images/placeholder_img.jpg';
        } else {
          // Para el modal de edición, mantener la imagen actual del curso
          const currentThumbnail = document.getElementById('course_edit_input').value;
          previewImg.src = '<?= BASE_URL ?>assets/upload/thumbnails/' + currentThumbnail;
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

  // Función para limpiar el modal de nuevo curso
  function resetNewCourseModal() {
    const modal = document.getElementById('add_course_modal');
    const form = modal.querySelector('form');
    const previewImg = document.getElementById('preview-thumbnail-new');
    const errorLabel = document.getElementById('thumbnail-error-new');

    form.reset();
    previewImg.src = '<?= BASE_URL ?>assets/images/placeholder_img.jpg';
    errorLabel.classList.add('hidden');
  }

  // Función para limpiar el modal de editar curso
  function resetEditCourseModal() {
    const modal = document.getElementById('edit_course_modal');
    const form = modal.querySelector('form');
    const errorLabel = document.getElementById('thumbnail-error-edit');
    const thumbnailInput = document.getElementById('thumbnail-input');
    const previewImg = document.getElementById('preview-thumbnail');

    form.reset();
    thumbnailInput.value = '';
    previewImg.src = '';
    errorLabel.classList.add('hidden');
  }

  // Event listeners para detectar cuando se cierran los modales
  document.addEventListener('DOMContentLoaded', function() {
    const addCourseModal = document.getElementById('add_course_modal');
    const editCourseModal = document.getElementById('edit_course_modal');

    addCourseModal.addEventListener('close', resetNewCourseModal);
    editCourseModal.addEventListener('close', resetEditCourseModal);
  });
</script>

<?php require_once '../includes/dashboard_footer.php'; ?>