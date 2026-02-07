<div class="bg-base-200 border-3 border-black shadow-neo p-6 rounded-none h-full">
  <div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-display uppercase italic tracking-tight">Lecciones</h2>
    <button onclick="add_lesson_modal.showModal()"
      class="btn btn-sm btn-accent border-2 border-black shadow-neo-sm rounded-none font-display italic">
      + Nuevo
    </button>
  </div>

  <div class="space-y-3">
    <?php if (empty($lessons)): ?>
      <div class="border-2 border-dashed border-black/20 p-10 text-center font-bold opacity-40 uppercase text-xs">
        No hay lecciones creadas todavía.
      </div>
    <?php else: ?>
      <?php foreach ($lessons as $lesson): ?>
        <div class="flex items-center bg-base-100 border-2 border-black shadow-neo-sm hover:shadow-neo transition-all rounded-none overflow-hidden group">
          <div class="bg-black text-white w-12 h-14 flex items-center justify-center font-display text-xl shrink-0">
            <?= $lesson['order_index'] ?>
          </div>

          <div class="flex-1 px-4 py-2">
            <h4 class="font-black uppercase text-xs truncate max-w-50 md:max-w-none"><?= $lesson['title'] ?></h4>
            <div class="flex gap-2 mt-1">
              <?php if ($lesson['video_url']): ?>
                <i data-lucide="play-circle" class="size-3 text-primary stroke-3"></i>
              <?php endif; ?>
              <?php if ($lesson['content']): ?>
                <i data-lucide="file-text" class="size-3 text-secondary stroke-3"></i>
              <?php endif; ?>
            </div>
          </div>

          <div class="flex gap-1 pr-3">
            <button class="btn btn-square btn-xs border-2 border-black rounded-none hover:bg-warning shadow-neo"
              onclick="updateLesson(
                <?= htmlspecialchars($lesson['id']) ?>,
                '<?= htmlspecialchars($lesson['title']) ?>',
                '<?= htmlspecialchars($lesson['video_url']) ?>',
                '<?= htmlspecialchars($lesson['order_index']) ?>',
                '<?= htmlspecialchars($lesson['content']) ?>'
              )">
              <i data-lucide="edit-3" class="size-3"></i>
            </button>
            <button class="btn btn-square btn-xs border-2 border-black rounded-none hover:bg-error shadow-neo"
              onclick="deleteLesson(<?= htmlspecialchars($lesson['id']) ?>, <?= htmlspecialchars($courseId) ?>); ">
              <i data-lucide="trash-2" class="size-3"></i>
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- crear lección modal-->
  <dialog id="add_lesson_modal" class="modal">
    <div class="modal-box rounded-none border-3 border-black shadow-neo-lg max-w-2xl p-0">
      <div class="flex items-center gap-3 p-6 pb-6 border-b-2 border-black bg-base-200 shrink-0">
        <div class="bg-secondary border-2 border-black p-2 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
          <i data-lucide="notebook-text" class="text-black size-5 stroke-[2.5]"></i>
        </div>
        <h3 class="font-display italic text-2xl uppercase tracking-tighter">Nueva lección</h3>
      </div>

      <form action="../procedures/lessons/create.proc.php" method="POST" class="p-6 space-y-4 bg-base-100">
        <input type="hidden" name="course_id" value="<?= $courseId ?>">



        <div class="form-control w-full">
          <label class="label mb-0">
            <span class="label-text font-black uppercase text-[11px] tracking-wider">Título de la lección</span>
          </label>
          <label class="input validator input-bordered rounded-none border-2 border-black bg-base-100 w-full">
            <input type="text" name="title" placeholder="Ej: Desarrollo Web con PHP" required minlength="3" />
          </label>
          <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 3 caracteres</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label"><span class="label-text font-black uppercase text-xs">URL del Video (Opcional)</span></label>
            <input name="video_url" type="url" placeholder="https://youtube.com/..." class="input input-bordered border-2 border-black rounded-none font-bold" />
          </div>
          <div class="form-control">
            <label class="label"><span class="label-text font-black uppercase text-xs">Orden (Index)</span></label>
            <input name="order_index" type="number" value="<?= count($lessons) + 1 ?>" class="input input-bordered border-2 border-black rounded-none font-bold " />
          </div>
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text font-black uppercase text-xs">Contenido / Texto</span></label>
          <textarea name="content" class="textarea textarea-bordered border-2 border-black rounded-none font-bold h-32 " placeholder="Escribe aquí la teoría de la lección..."></textarea>
        </div>

        <button type="submit" class="btn btn-secondary w-full border-2 border-black rounded-none shadow-neo font-display italic uppercase mt-4 hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all">
          Guardar Lección
        </button>
      </form>
    </div>
  </dialog>

  <!-- editar lección modal-->
  <dialog id="edit_lesson_modal" class="modal">
    <div class="modal-box rounded-none border-3 border-black shadow-neo-lg max-w-2xl p-0">
      <div class="flex items-center gap-3 p-6 pb-6 border-b-2 border-black bg-base-200 shrink-0">
        <div class="bg-secondary border-2 border-black p-2 shadow-neo-sm">
          <i data-lucide="edit-3" class="text-black size-5 stroke-[2.5]"></i>
        </div>
        <h3 class="font-display italic text-2xl uppercase tracking-tighter">Editar lección</h3>
      </div>

      <form action="../procedures/lessons/update.proc.php" method="POST" class="p-6 space-y-4 bg-base-100">
        <input type="hidden" name="lesson_id" id="edit_lesson_id">
        <input type="hidden" name="course_id" value="<?= $courseId ?>">

        <div class="form-control w-full">
          <label class="label mb-0">
            <span class="label-text font-black uppercase text-[11px] tracking-wider">Título de la lección</span>
          </label>
          <label class="input validator input-bordered rounded-none border-2 border-black bg-base-100 w-full">
            <input type="text" name="title" id="edit_title" placeholder="Ej: Desarrollo Web con PHP" required minlength="3" />
          </label>
          <div class="validator-hint font-bold text-[10px] uppercase mt-1">Mínimo 3 caracteres</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-control">
            <label class="label"><span class="label-text font-black uppercase text-xs">URL del Video (Opcional)</span></label>
            <input name="video_url" id="edit_video_url" type="url" placeholder="https://youtube.com/..." class="input input-bordered border-2 border-black rounded-none font-bold" />
          </div>
          <div class="form-control">
            <label class="label"><span class="label-text font-black uppercase text-xs">Orden (Index)</span></label>
            <input name="order_index" id="edit_order_index" type="number" class="input input-bordered border-2 border-black rounded-none font-bold " />
          </div>
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text font-black uppercase text-xs">Contenido / Texto</span></label>
          <textarea name="content" id="edit_content" class="textarea textarea-bordered border-2 border-black rounded-none font-bold h-32 " placeholder="Escribe aquí la teoría de la lección..."></textarea>
        </div>

        <div class="modal-action mt-6 gap-3">
          <button type="button" class="btn btn-ghost rounded-none border-2 border-black font-black uppercase text-[11px] px-6" onclick="edit_lesson_modal.close()">
            Cancelar
          </button>
          <button type="submit" class="btn btn-secondary rounded-none border-3 border-black font-black uppercase text-[11px] px-10 shadow-neo-sm hover:translate-x-px hover:translate-y-px hover:shadow-none transition-all">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </dialog>

  <!-- eliminar lección modal-->
  <dialog id="delete_lesson_modal" class="modal">
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
          ¿Estás seguro de que deseas eliminar esta lección ? Esta acción no se puede deshacer.
        </p>

      </div>



      <form action="../procedures/lessons/delete.proc.php" method="POST" class="p-6 pt-0">
        <input type="hidden" name="lesson_id" id="delete_lesson_id_input" />
        <input type="hidden" name="course_id" id="delete_course_id_input" />
        <div class="flex gap-3 justify-end">
          <button type="button" onclick="delete_lesson_modal.close()" class="btn btn-ghost rounded-none border-2 border-black font-black uppercase text-[11px]">
            Cancelar
          </button>
          <button type="submit" class="btn btn-error rounded-none border-3 border-black font-black uppercase text-[11px] shadow-neo-sm">
            <i data-lucide="trash-2" class="size-4"></i>
            Eliminar
          </button>
        </div>
      </form>

    </div>
  </dialog>

  <script>
    function deleteLesson(lessonId, courseId) {
      document.getElementById('delete_lesson_id_input').value = lessonId;
      document.getElementById('delete_course_id_input').value = courseId;
      delete_lesson_modal.showModal();
    }


    function updateLesson(id, title, videoUrl, orderIndex, content) {
      document.getElementById('edit_lesson_id').value = id;
      document.getElementById('edit_title').value = title;
      document.getElementById('edit_video_url').value = videoUrl;
      document.getElementById('edit_order_index').value = orderIndex;
      document.getElementById('edit_content').value = content;
      edit_lesson_modal.showModal();
    }

    function resetNewLessonForm() {
      modal = document.getElementById('add_lesson_modal');
      form = modal.querySelector('form');
      form.reset();

    }

    document.addEventListener('DOMContentLoaded', function() {
      const addLessonModal = document.getElementById('add_lesson_modal');
      addLessonModal.addEventListener('close', resetNewLessonForm);
    });
  </script>