<?php
require_once '../includes/config.php';
require_once '../includes/header.php';
require_once '../includes/connectDB.php';
require_once '../includes/dao/EnrollmentDao.php';
$enrollmentDao = new EnrollmentDao($db);
$countCourses = $enrollmentDao->coutCursesByStudent($_SESSION['user']['id']);

// Indicar que viene del layout público
$profile_source = 'public';


?>



<main class="max-w-7xl mx-auto px-6 lg:px-20 py-16 lg:py-8">

  <?php require_once '../includes/profile.php'; ?>
</main>

<?php require_once '../includes/footer.php'; ?>