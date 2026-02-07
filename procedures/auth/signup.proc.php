<?php
require_once __DIR__ . '/../../includes/utils.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connectDB.php';
require_once __DIR__ . '/../../includes/dao/UserDao.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = cleanInput($_POST['name']) ?? "";
  $email = cleanInput($_POST['email']) ?? "";
  $password = cleanInput($_POST['password']) ?? "";
  $avatar = $_FILES['avatar'] ?? null;


  $userDao = new UserDao($db);
  $existingUser = $userDao->getUserByEmail($email);

  if ($existingUser) {
    $_SESSION['alert_message'] = [
      "type" => "error",
      "message" => "El correo electrónico ya está registrado. Por favor, utiliza otro."
    ];
    redirect("pages/signup.php");
  } else {

    $newUser = [
      'username' => $name,
      'email' => $email,
      'password' => md5($password),
      'role' => 'student',
      'avatar' => $avatar,

    ];
    $userDao->createUser($newUser, $avatar);
    $user = $userDao->getUserByEmail($email);

    $_SESSION['user'] = [
      'id' => $user['id'],
      'username' => $user['username'],
      'email' => $user['email'],
      'role' => $user['role'],
      'avatar' => $user['profile_picture']
    ];

    $_SESSION['alert_message'] = [
      "type" => "success",
      "message" => "¡Registro exitoso!."
    ];
    redirect("index.php");
  }
}
