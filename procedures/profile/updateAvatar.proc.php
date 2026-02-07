<?php
require_once __DIR__ . '/../../includes/utils.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connectDB.php';
require_once __DIR__ . '/../../includes/dao/UserDao.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_SESSION['user']) && isset($_FILES['avatar'])) {
    $userDao = new UserDao($db);
    $userEmail = $_SESSION['user']['email'];
    $userId = $_SESSION['user']['id'];

    $avatarFile = $_FILES['avatar'];

    // Obtener desde qué layout viene el usuario
    $source = isset($_POST['source']) ? $_POST['source'] : 'dashboard';

    $redirectPage = ($source === 'public') ? 'pages/homeProfile.php' : 'pages/dashboardProfile.php';

    if ($userDao->crateFileAvatar($userId, $avatarFile)) {
      $updatedUser = $userDao->getUserByEmail($userEmail);
      $_SESSION['user']['avatar'] = $updatedUser['profile_picture'];
      $_SESSION['alert_message'] = [
        "type" => "success",
        "message" => "Avatar actualizado."
      ];

      // echo "actulizado";
      // die();

      redirect($redirectPage);
    } else {
      $_SESSION['alert_message'] = [
        "type" => "error",
        "message" => "Error al actualizar el avatar. Intenta de nuevo."
      ];
      redirect($redirectPage);
    }
  } else {
    $_SESSION['alert_message'] = [
      "type" => "error",
      "message" => "No se pudo procesar la solicitud."
    ];
    redirect('index.php');
  }
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Solicitud inválida.'
  ];
  redirect('index.php');
}
