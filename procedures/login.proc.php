<?php
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connectDB.php';
require_once __DIR__ . '/../includes/dao/UserDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = cleanInput($_POST['email']) ?? "";
  $password = cleanInput($_POST['password']) ?? "";

  $userDao = new UserDao($db);
  $user = $userDao->getUserByEmail($email);

  if ($user) {
    if (md5($password) === $user['password']) {
      $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'role' => $user['role'],
        'avatar' => $user['profile_picture']
      ];

      $_SESSION['alert_message'] = [
        "type" => "success",
        "message" => "¡Bienvenido, " . $user['email'] . "!"
      ];

      switch ($user['role']) {
        case 'admin':
          redirect("pages/dashboard.php");
          break;
        case 'teacher':
          redirect("index.php");
          break;

        case 'student':
          redirect("index.php");
          break;

        default:
          redirect("index.php");
          break;
      }
    } else {
      $_SESSION['alert_message'] = [
        "type" => "error",
        "message" => "Contraseña incorrecta. Inténtalo de nuevo."
      ];
      redirect("pages/login.php");
    }
  } else {
    $_SESSION['alert_message'] = [
      "type" => "error",
      "message" => "El usuario no existe. Verifica tus credenciales."
    ];
    redirect("pages/login.php");
  }
}
