<?php
require_once '../includes/config.php';
require_once '../includes/connectDB.php';
$page_title = 'Mis cursos';
require_once '../includes/dashboard_header.php';
include_once '../includes/dao/CourseDao.php';
include_once '../includes/dao/LessonDao.php';
include_once '../includes/dao/EnrollmentDao.php';

$courseDao = new CourseDao($db);
$courses = $courseDao->getCoursesByTeacher($_SESSION['user']['id']);

$lessonDao = new LessonDao($db);
$enrollmentDao = new EnrollmentDao($db);
?>

<div class="p-6 space-y-8">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <h2 class="text-3xl font-display uppercase italic tracking-tighter">Mis Cursos</h2>
      <p class="font-sans text-xs font-bold opacity-50 uppercase tracking-widest mt-1">Panel de Gestión Educativa</p>

    </div>
    <div class="breadcrumbs hidden sm:block font-sans text-xs uppercase font-bold opacity-60">
      <ul>
        <li><a href="<?= BASE_URL ?>pages/dashboard.php">Lugo Tech</a></li>
        <li>Mis cursos</li>
      </ul>
    </div>


  </div>
  <div class="flex justify-end">

    <button onclick="add_course_modal.showModal()"
      class="btn btn-primary rounded-none border-3 border-black shadow-neo font-display italic uppercase transition-all hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none">
      <i data-lucide="plus-circle" class="size-5 stroke-[2.5]"></i>
      <span class="hidden sm:inline">Nuevo Curso</span>
    </button>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php foreach ($courses as $course): ?>
      <div class="bg-base-100 border-3 border-black shadow-neo-lg rounded-none flex flex-col group transition-all">

        <figure class="relative h-48 border-b-3 border-black overflow-hidden bg-base-300">
          <img src="<?= BASE_URL . "assets/upload/thumbnails/" . $course['thumbnail'] ?>"
            alt="<?= $course['title'] ?>"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />

          <div class="absolute top-3 left-3 px-3 py-1 border-2 border-black font-black text-black/70 text-[10px] uppercase shadow-neo-sm <?= $course['status'] === 'published' ? 'bg-success' : 'bg-warning' ?>">
            <?= $course['status'] ?>
          </div>
        </figure>

        <div class="p-6 flex flex-col flex-1">
          <div class="flex justify-between items-center mb-3">
            <span class="px-2 py-0.5 border-2 border-black text-[9px] font-black uppercase bg-base-200">
              <?= $course['category'] ?>
            </span>
            <div class="flex items-center gap-1 opacity-70">
              <i data-lucide="users" class="size-3 stroke-3"></i>
              <span class="text-[10px] font-black uppercase tracking-tighter"><?= $enrollmentDao->countStudentsByCourse($course['id'] . " ")  ?> Estudiantes</span>
            </div>
          </div>

          <h3 class="font-display italic text-xl uppercase leading-none mb-6 line-clamp-2">
            <?= $course['title'] ?>
          </h3>

          <div class="mt-auto flex gap-2">
            <a href="teacherCourse.php?course_id=<?= $course['id'] ?>"
              class="btn btn-secondary flex-1 rounded-none border-2 border-black shadow-neo-sm font-display italic uppercase text-xs">
              Gestionar
            </a>
            <div class="dropdown dropdown-end">
              <div tabindex="0" role="button" class="btn btn-square border-2 border-black rounded-none bg-base-100 shadow-neo-sm">
                <i data-lucide="settings-2" class="size-5"></i>
              </div>
              <ul tabindex="0" class="dropdown-content z-99 menu p-0 shadow-neo border-2 border-black bg-base-100 rounded-none w-40 mt-2">
                <li>
                  <button onclick="updateCourse('<?= $course['id'] ?>','<?= $course['teacher_id'] ?>','<?= htmlspecialchars($course['title'], ENT_QUOTES) ?>','<?= htmlspecialchars($course['description'], ENT_QUOTES) ?>','<?= $course['category'] ?>','<?= $course['status'] ?>','<?= $course['thumbnail'] ?>')" class="rounded-none font-bold uppercase text-[10px] p-3 hover:bg-primary transition-colors">
                    <i data-lucide="edit" class="size-4"></i> Editar Info
                  </button>
                </li>
                <li>
                  <button onclick="confirmDeleteCourse('<?= $course['id'] ?>', '<?= htmlspecialchars($course['title'], ENT_QUOTES) ?>')" class="rounded-none font-bold uppercase text-[10px] p-3 hover:bg-error text-error hover:text-black transition-colors border-t-2 border-black">
                    <i data-lucide="trash-2" class="size-4"></i> Eliminar
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
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

<!-- crear curso modal -->
<dialog id="add_course_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box bg-base-100 p-0 max-w-2xl max-h-[90vh] flex flex-col rounded-none border-3 border-black shadow-neo-lg">

    <div class="flex items-center gap-3 p-6 pb-6 border-b-2 border-black bg-base-200 shrink-0">
      <div class="bg-primary border-2 border-black p-2 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
        <i data-lucide="book-plus" class="text-black size-5 stroke-[2.5]"></i>
      </div>
      <h3 class="font-display italic text-2xl uppercase tracking-tighter">Nuevo Curso</h3>
    </div>

    <form action="<?= BASE_URL ?>procedures/courseCreate.proc.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-4 overflow-y-auto">

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



      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Categoría</span>
        </label>
        <select name="category" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
          <option value="backend">Backend</option>
          <option value="frontend">Frontend</option>
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
            <p class="text-error text-[10px] font-bold mt-1 hidden" id="thumbnail-error-new"> El archivo no debe superar 2 MB</p>
          </div>
        </div>
      </div>

      <div class="modal-action mt-6 gap-3">
        <button type="button" class="btn btn-ghost rounded-none border-2 border-black font-black uppercase text-[11px] px-6" onclick="add_course_modal.close()">
          Cancelar
        </button>
        <button type="submit" class="btn btn-primary rounded-none border-3 border-black font-black uppercase text-[11px] px-10 shadow-neo-sm hover:translate-x-px hover:translate-y-px hover:shadow-none transition-all">
          Guardar Curso
        </button>
      </div>

    </form>
  </div>
  <form method="dialog" class="modal-backdrop bg-black/70"><button>close</button></form>
</dialog>

<!-- editar curso modal -->
<?php require_once __DIR__ . '/../includes/editCourseModal.php'; ?>





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

  // function updateCourse(id, teacherId, title, description, category, status, thumbnail) {
  //   const modal = document.getElementById('edit_course_modal');
  //   const titleInput = document.getElementById('edit_title_input');
  //   const descriptionInput = document.getElementById('edit_description_input');
  //   const teacherSelect = document.getElementById('edit_teacher_input');
  //   const categorySelect = document.getElementById('edit_category_input');
  //   const courseIdInput = document.getElementById('course_edit_input');
  //   const previewImage = document.getElementById("preview-thumbnail");

  //   if (titleInput && descriptionInput && teacherSelect && categorySelect && courseIdInput && modal && previewImage) {
  //     titleInput.value = title;
  //     descriptionInput.value = description;
  //     teacherSelect.value = teacherId;
  //     categorySelect.value = category;
  //     courseIdInput.value = id;
  //     previewImage.src = "<?= BASE_URL ?>assets/upload/thumbnails/" + thumbnail;

  //     modal.showModal();
  //   }
  // }

  // function previewNewThumbnail(event) {
  //   const file = event.target.files[0];
  //   const maxSize = 2 * 1024 * 1024; // 2 MB en bytes
  //   const inputId = event.target.id;
  //   const previewImg = inputId === 'thumbnail-input-new' ?
  //     document.getElementById('preview-thumbnail-new') :
  //     document.getElementById('preview-thumbnail');
  //   const errorLabel = inputId === 'thumbnail-input-new' ?
  //     document.getElementById('thumbnail-error-new') :
  //     document.getElementById('thumbnail-error-edit');

  //   if (file) {

  //     if (file.size > maxSize) {

  //       errorLabel.classList.remove('hidden');
  //       event.target.value = ''; // Limpiar el input file
  //       // Restaurar placeholder si es el modal de nuevo curso
  //       if (inputId === 'thumbnail-input-new') {
  //         previewImg.src = '<?= BASE_URL ?>assets/images/placeholder_img.jpg';
  //       } else {
  //         // Para el modal de edición, mantener la imagen actual del curso
  //         const currentThumbnail = document.getElementById('course_edit_input').value;
  //         previewImg.src = '<?= BASE_URL ?>assets/upload/thumbnails/' + currentThumbnail;
  //       }

  //       return;
  //     }

  //     const reader = new FileReader();

  //     if (reader) {
  //       reader.onload = function(e) {
  //         previewImg.src = e.target.result;
  //       };
  //       reader.readAsDataURL(file);
  //     }

  //     // Ocultar error si estaba visible
  //     errorLabel.classList.add('hidden');
  //   }
  // }
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
  // function resetEditCourseModal() {
  //   const modal = document.getElementById('edit_course_modal');
  //   const form = modal.querySelector('form');
  //   const errorLabel = document.getElementById('thumbnail-error-edit');
  //   const thumbnailInput = document.getElementById('thumbnail-input');
  //   const previewImg = document.getElementById('preview-thumbnail');

  //   form.reset();
  //   thumbnailInput.value = '';
  //   previewImg.src = '';
  //   errorLabel.classList.add('hidden');
  // }

  // Event listeners para detectar cuando se cierran los modales
  document.addEventListener('DOMContentLoaded', function() {
    const addCourseModal = document.getElementById('add_course_modal');
    // const editCourseModal = document.getElementById('edit_course_modal');

    addCourseModal.addEventListener('close', resetNewCourseModal);
    // editCourseModal.addEventListener('close', resetEditCourseModal);
  });
</script>

<?php require_once '../includes/dashboard_footer.php'; ?>