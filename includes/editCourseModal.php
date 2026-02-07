<!-- editar curso modal -->
<dialog id="edit_course_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box bg-base-100 p-0 max-w-2xl max-h-[90vh] flex flex-col rounded-none border-3 border-black shadow-neo-lg">

    <div class="flex items-center gap-3 p-6 pb-6 border-b-2 border-black bg-base-200 shrink-0">
      <div class="bg-primary border-2 border-black p-2 shadow-neo-sm">
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



      <div class="form-control w-full">
        <label class="label mb-0">
          <span class="label-text font-black uppercase text-[11px] tracking-wider">Categoría</span>
        </label>
        <select name="category" id="edit_category_input" class="select select-bordered rounded-none border-2 border-black bg-base-100 w-full font-bold" required>
          <option value="backend">Backend</option>
          <option value="frontend">Frontend</option>
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

      <input type="hidden" name="teacher_id" id="edit_teacher_input">
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
  function updateCourse(id, teacherId, title, description, category, status, thumbnail) {
    const modal = document.getElementById('edit_course_modal');
    const titleInput = document.getElementById('edit_title_input');
    const descriptionInput = document.getElementById('edit_description_input');
    const teacherSelect = document.getElementById('edit_teacher_input');
    const categorySelect = document.getElementById('edit_category_input');
    const courseIdInput = document.getElementById('course_edit_input');
    const previewImage = document.getElementById("preview-thumbnail");

    if (titleInput && descriptionInput && teacherSelect && categorySelect && courseIdInput && modal && previewImage) {
      titleInput.value = title;
      descriptionInput.value = description;
      teacherSelect.value = teacherId;
      categorySelect.value = category;
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

  document.addEventListener('DOMContentLoaded', function() {
    const editCourseModal = document.getElementById('edit_course_modal');

    editCourseModal.addEventListener('close', resetEditCourseModal);
  });
</script>