/**
 * Inicializa el sistema de temas
 * Carga el tema guardado y gestiona el cambio
 */
(function () {
  const savedTheme = localStorage.getItem("theme") || "nord";
  document.documentElement.setAttribute("data-theme", savedTheme);

  document.addEventListener("DOMContentLoaded", function () {
    const themeController = document.querySelector(".theme-controller");
    if (themeController) {
      themeController.checked = savedTheme === "night";

      themeController.addEventListener("change", (e) => {
        const activeTheme = e.target.checked ? "night" : "nord";
        document.documentElement.setAttribute("data-theme", activeTheme);
        localStorage.setItem("theme", activeTheme);
      });
    }
  });
})();
