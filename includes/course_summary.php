<div class="bg-base-100 border-3 border-black shadow-neo-lg p-6 flex flex-col md:flex-row justify-between items-center gap-6 rounded-none">
  <div class="flex items-center gap-6">
    <div class="hidden md:block w-24 h-24 border-2 border-black shadow-neo-sm overflow-hidden bg-base-300">
      <img src="<?= BASE_URL . 'assets/upload/thumbnails/' . $course['thumbnail'] ?>"
        class="w-full h-full object-cover" alt="Course thumb">
    </div>
    <div>
      <div class="flex items-center gap-3 mb-1">
        <span class="bg-secondary px-2 py-0.5 border-2 border-black text-[10px] font-black uppercase shadow-neo-sm">
          <?= $course['category'] ?>
        </span>
        <span class="text-[10px] font-black opacity-50 uppercase tracking-widest">ID: #<?= $course['id'] ?></span>
      </div>
      <h1 class="text-3xl md:text-4xl font-display uppercase italic tracking-tighter leading-none">
        <?= $course['title'] ?>
      </h1>
      <p class="text-sm font-medium mt-2 max-w-xl opacity-80"><?= $course['description'] ?></p>
    </div>
  </div>

  <div class="flex gap-3 w-full md:w-auto">
    <button onclick="updateCourse(<?= htmlspecialchars($course['id']) ?>, <?= htmlspecialchars($course['teacher_id']) ?>, '<?= htmlspecialchars(addslashes($course['title'])) ?>', '<?= htmlspecialchars(addslashes($course['description'])) ?>', '<?= htmlspecialchars($course['category']) ?>', '<?= htmlspecialchars($course['status']) ?>', '<?= htmlspecialchars($course['thumbnail']) ?>')"
      class="btn btn-primary flex-1 md:flex-none border-2 border-black shadow-neo-sm rounded-none font-display italic uppercase">
      <i data-lucide="settings-2" class="size-4"></i> Editar Info
    </button>
  </div>


</div>

<!-- editar curso modal -->
<?php require_once __DIR__ . '/editCourseModal.php'; ?>