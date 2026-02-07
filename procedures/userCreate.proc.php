<?php
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connectDB.php';
require_once __DIR__ . '/../includes/dao/UserDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


  $userDao = new UserDao($db);


  $username = cleanInput($_POST['username']) ?? "";
  $email = cleanInput($_POST['email']) ?? "";
  $password = cleanInput($_POST['password']) ?? "";
  $role = cleanInput($_POST['role']) ?? "";
  $avatar = $_FILES['avatar'] ?? null;

  if ($userDao->isExistsUser($email)) {
    $_SESSION['alert_message'] = [
      'type' => 'error',
      'message' => 'El correo electrónico ya está registrado.'
    ];
    redirect('pages/users.php');
  }

  $newUser = [
    'username' => $username,
    'email' => $email,
    'password' => md5($password),
    'role' => $role
  ];

  if ($userDao->createUser($newUser, $avatar)) {
    $_SESSION['alert_message'] = [
      'type' => 'success',
      'message' => 'Usuario creado correctamente.'
    ];
  } else {
    $_SESSION['alert_message'] = [
      'type' => 'error',
      'message' => 'Error al crear el usuario. Inténtalo de nuevo.'
    ];
  }
  redirect('pages/users.php');
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Solicitud inválida.'
  ];
  redirect('index.php');
}
