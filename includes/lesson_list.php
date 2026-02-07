<div class="bg-base-200 border-3 border-black shadow-neo p-6 rounded-none h-full">
  <h2 class="text-2xl font-display uppercase italic tracking-tight mb-6">Contenido del curso</h2>

  <div class="space-y-3">
    <?php if (empty($lessons)): ?>
      <div class="border-2 border-dashed border-black/20 p-10 text-center font-bold opacity-40 uppercase text-xs">
        Este curso aún no tiene lecciones disponibles.
      </div>
    <?php else: ?>
      <?php foreach ($lessons as $lesson): ?>
        <div class="collapse collapse-arrow bg-base-100 border-2 border-black shadow-neo-sm hover:shadow-neo transition-all rounded-none">
          <input type="checkbox" class="peer" />

          <div class="collapse-title p-0 min-h-0 flex items-center">
            <div class="bg-black text-white w-12 h-14 flex items-center justify-center font-display text-xl shrink-0">
              <?= $lesson['order_index'] ?>
            </div>

            <div class="flex-1 px-4 py-2">
              <h4 class="font-black uppercase text-xs"><?= $lesson['title'] ?></h4>
              <div class="flex gap-2 mt-1">
                <?php if ($lesson['video_url']): ?>
                  <i data-lucide="play-circle" class="size-3 text-primary stroke-3"></i>
                <?php endif; ?>
                <?php if ($lesson['content']): ?>
                  <i data-lucide="file-text" class="size-3 text-secondary stroke-3"></i>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="collapse-content bg-base-200/50 border-t-2 border-black">
            <div class="pt-4 space-y-3">
              <?php if ($lesson['video_url']): ?>
                <div class="flex items-start gap-2">
                  <i data-lucide="play-circle" class="size-4 text-primary stroke-3 mt-0.5 shrink-0"></i>
                  <div>
                    <p class="font-black uppercase text-[10px] text-primary mb-1">Video de la lección</p>
                    <div class="text-xs font-bold opacity-60">Disponible para estudiantes inscritos</div>
                  </div>
                </div>
              <?php endif; ?>

              <?php if ($lesson['content']): ?>
                <div class="flex items-start gap-2">
                  <i data-lucide="file-text" class="size-4 text-secondary stroke-3 mt-0.5 shrink-0"></i>
                  <div>
                    <p class="font-black uppercase text-[10px] text-secondary mb-1">Descripción</p>
                    <p class="text-sm font-normal leading-relaxed"><?= htmlspecialchars($lesson['content']) ?></p>
                  </div>
                </div>
              <?php endif; ?>

              <?php if (!$lesson['video_url'] && !$lesson['content']): ?>
                <p class="text-xs font-bold opacity-50 italic">Vista previa disponible próximamente.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>