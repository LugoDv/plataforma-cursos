<div class="bg-base-200  border-black shadow-neo p-6 rounded-none h-full">
  <div class="flex items-center gap-2 mb-6">
    <h2 class="text-xl font-display uppercase italic">Estudiantes</h2>
    <span class="bg-black text-white px-2 py-0.5 text-[10px] font-black"><?= $totalStudents ?></span>
  </div>

  <div class="overflow-x-auto">
    <table class="table table-xs w-full border-separate border-spacing-y-2 p-0">
      <tbody class="space-y-2">
        <?php if (empty($students)): ?>
          <tr>
            <td class="text-center py-4 font-bold opacity-50 italic">Sin alumnos inscritos</td>
          </tr>
        <?php else: ?>
          <?php foreach ($students as $student): ?>
            <tr class="bg-base-100 border-2 border-black shadow-neo-sm hover:shadow-neo transition-all rounded-none ">
              <td class="p-2 border-y-2 border-l-2 border-black ">
                <div class="flex items-center gap-3">
                  <div class="avatar placeholder">
                    <div class="bg-neutral text-neutral-content w-8 h-8 border-2 border-black shadow-neo-sm rounded-none">
                      <span class="text-xs font-black"><?= strtoupper(substr($student['username'], 0, 1)) ?></span>
                    </div>
                  </div>
                  <div>
                    <div class="font-black text-[11px] uppercase truncate w-24 leading-none"><?= $student['username'] ?></div>
                    <div class="text-[9px] opacity-60 font-bold"><?= $student['enrolled_at'] ?></div>
                  </div>
                </div>
              </td>
              <td class="p-2 border-y-2 border-r-2 border-black text-right">
                <button class="btn btn-square btn-xs border-2 border-black rounded-none hover:bg-error shadow-neo"
                  onclick=" ">
                  <i data-lucide="trash-2" class="size-3"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>