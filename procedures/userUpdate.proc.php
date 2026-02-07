<?php
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connectDB.php';
require_once __DIR__ . '/../includes/dao/UserDao.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Solicitud inválida.'
  ];
  redirect('index.php');
}

$userDao = new UserDao($db);

$id = cleanInput($_POST['user_id']) ?? "";
$username = cleanInput($_POST['username']) ?? "";
$email = cleanInput($_POST['email']) ?? "";
$rol = cleanInput($_POST['role']) ?? "";
$avatarPath = cleanInput($_POST['userAvatarPath']) ?? "";
$avatar = $_FILES['avatar'] ?? null;

// Validar si el email ya existe para OTRO usuario 
if ($userDao->isExistsUser($email)) {
  $existingUser = $userDao->getUserByEmail($email);
  if ($existingUser && $existingUser['id'] != $id) {
    $_SESSION['alert_message'] = [
      'type' => 'error',
      'message' => 'El correo electrónico no está disponible.'
    ];
    redirect('pages/users.php');
  }
}

$newUser = [
  'id' => $id,
  'username' => $username,
  'email' => $email,
  'role' => $rol,
  'profile_picture' => $avatarPath
];

if ($userDao->updateUser($newUser, $avatar)) {
  $_SESSION['alert_message'] = [
    'type' => 'success',
    'message' => 'Usuario actualizado.'
  ];
} else {
  $_SESSION['alert_message'] = [
    'type' => 'error',
    'message' => 'Error al actualizar el usuario. Inténtalo de nuevo.'
  ];
}
redirect('pages/users.php');
