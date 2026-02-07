<?php
class UserDao
{
  private $db;
  private const RUTA_BASE = 'assets/upload/avatars/';

  public function __construct($databaseConnection)
  {
    $this->db = $databaseConnection;
  }

  public function getUserByEmail($email)
  {
    $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC);
  }

  public function isExistsUser($email)
  {
    $stmt = $this->db->prepare('SELECT COUNT(*) as count FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row['count'] > 0;
  }


  public function getAllUsers()
  {
    $result = $this->db->query('SELECT * FROM users ORDER BY id ');
    $users = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $users[] = $row;
    }
    return $users;
  }

  public function createUser($user, $file)
  {
    $stmt = $this->db->prepare('INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)');

    $stmt->bindValue(':username', $user['username'], SQLITE3_TEXT);
    $stmt->bindValue(':email', $user['email'], SQLITE3_TEXT);

    $stmt->bindValue(':password', $user['password'], SQLITE3_TEXT);

    $stmt->bindValue(':role', $user['role'], SQLITE3_TEXT);

    $isInsert = $stmt->execute();

    if ($isInsert) {
      $lastId = $this->db->lastInsertRowID();
      if (isset($file['size']) && $file['size'] > 0) {
        $this->crateFileAvatar($lastId, $file);
      }
      return true;
    }

    return false;
  }

  public function updateUser($user, $file)
  {

    // Si el usuario subió algo nuevo, procesamos el cambio de archivo
    if ($file && isset($file['size']) && $file['size'] > 0) {
      $this->crateFileAvatar($user['id'], $file);
    }

    $stmt = $this->db->prepare('UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id');
    $stmt->bindValue(':id', $user['id'], SQLITE3_INTEGER);
    $stmt->bindValue(':username', $user['username'], SQLITE3_TEXT);
    $stmt->bindValue(':email', $user['email'], SQLITE3_TEXT);
    $stmt->bindValue(':role', $user['role'], SQLITE3_TEXT);
    return $stmt->execute();
  }


  public function deleteUser($userId)
  {
    $stmt = $this->db->prepare('SELECT profile_picture FROM users WHERE id = :id');
    $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
      $imagePath = $user['profile_picture'];
      $this->deleteFileAvatar($imagePath);


      $delStmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
      $delStmt->bindValue(':id', $userId, SQLITE3_INTEGER);
      return $delStmt->execute();
    }

    return false;
  }

  public function crateFileAvatar($userId, $file)
  {
    $rootDir = dirname(__DIR__, 2);
    $targetDir = $rootDir . '/' . self::RUTA_BASE;
    if (!is_dir($targetDir)) {
      mkdir($targetDir, 0755, true);
    }

    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'avatar_user_' . $userId . '.' . $fileExtension;
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
      $stmt = $this->db->prepare('UPDATE users SET profile_picture = :avatar WHERE id = :id');
      $stmt->bindValue(':avatar', $fileName, SQLITE3_TEXT);
      $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
      $stmt->execute();
      return true;
    } else {
      return false;
    }
  }

  public function deleteFileAvatar($imagePath)
  {
    if ($imagePath && $imagePath !== 'default_avatar.jpg') {
      $rootDir = dirname(__DIR__, 2);
      $fullPath = $rootDir . '/' . self::RUTA_BASE . $imagePath;

      if (file_exists($fullPath)) {
        unlink($fullPath); // Borra el archivo del servidor
        return true;
      }
    }
    return false;
  }

  // Métodos para estadísticas
  public function countUsersByRole($role)
  {
    $stmt = $this->db->prepare('SELECT COUNT(*) as total FROM users WHERE role = :role');
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row ? $row['total'] : 0;
  }

  public function countAllUsers()
  {
    $result = $this->db->query('SELECT COUNT(*) as total FROM users');
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row ? $row['total'] : 0;
  }
}
