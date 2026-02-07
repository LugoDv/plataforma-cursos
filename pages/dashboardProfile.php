<?php
require_once '../includes/config.php';
require_once '../includes/dashboard_header.php';
require_once '../includes/connectDB.php';
require_once '../includes/dao/EnrollmentDao.php';
$enrollmentDao = new EnrollmentDao($db);
$countCourses = $enrollmentDao->coutCursesByStudent($_SESSION['user']['id']);

// Indicar que viene del dashboard
$profile_source = 'dashboard';

require_once '../includes/profile.php';
require_once '../includes/dashboard_footer.php';
