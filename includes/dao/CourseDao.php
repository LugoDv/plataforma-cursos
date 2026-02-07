<?php
class CourseDao
{
  private $db;
  private const RUTA_BASE = 'assets/upload/thumbnails/';

  public function __construct($databaseConnection)
  {
    $this->db = $databaseConnection;
  }

  public function getCoursesByTeacher($teacherId)
  {
    $stmt = $this->db->prepare('SELECT * FROM courses WHERE teacher_id = :teacher_id ORDER BY id DESC');
    $stmt->bindValue(':teacher_id', $teacherId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $courses = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $courses[] = $row;
    }
    return $courses;
  }

  public function getCoursesByCategory($category)
  {
    $stmt = $this->db->prepare("SELECT courses.*, users.username as teacher_name FROM courses LEFT JOIN users ON courses.teacher_id = users.id WHERE courses.category  = :category AND courses.status = 'published' ORDER BY courses.id DESC");
    $stmt->bindValue(':category', $category, SQLITE3_TEXT);
    $result = $stmt->execute();
    $courses = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $courses[] = $row;
    }
    return $courses;
  }

  public function getAllCoursesByStatus($status = 'published')
  {
    $stmt = $this->db->prepare("SELECT courses.*, users.username as teacher_name FROM courses LEFT JOIN users ON courses.teacher_id = users.id WHERE courses.status  = :status ORDER BY courses.id DESC");
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $result = $stmt->execute();
    $courses = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $courses[] = $row;
    }
    return $courses;
  }

  public function getAllCourses()
  {
    $result = $this->db->query('SELECT courses.*, users.username as teacher_name FROM courses LEFT JOIN users ON courses.teacher_id = users.id ORDER BY courses.id DESC');
    $courses = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $courses[] = $row;
    }
    return $courses;
  }

  public function getCourseById($id)
  {
    $stmt = $this->db->prepare('SELECT courses.*, users.username as teacher_name FROM courses LEFT JOIN users ON courses.teacher_id = users.id WHERE courses.id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC);
  }

  public function getAllTeachers()
  {
    $stmt = $this->db->prepare("SELECT id, username, email FROM users WHERE role = 'teacher' ORDER BY username");
    $result = $stmt->execute();
    $teachers = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $teachers[] = $row;
    }
    return $teachers;
  }

  public function createCourse($course, $file)
  {
    $stmt = $this->db->prepare('INSERT INTO courses (teacher_id, title, description, thumbnail, category, status) VALUES (:teacher_id, :title, :description, :thumbnail, :category, :status)');

    $stmt->bindValue(':teacher_id', $course['teacher_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':title', $course['title'], SQLITE3_TEXT);
    $stmt->bindValue(':description', $course['description'], SQLITE3_TEXT);
    $stmt->bindValue(':thumbnail', 'default_course.jpg', SQLITE3_TEXT);
    $stmt->bindValue(':category', $course['category'], SQLITE3_TEXT);
    $stmt->bindValue(':status', $course['status'], SQLITE3_TEXT);

    $isInsert = $stmt->execute();

    if ($isInsert) {
      $lastId = $this->db->lastInsertRowID();
      if (isset($file['size']) && $file['size'] > 0) {
        $this->createFileThumbnail($lastId, $file);
      }
      return true;
    }

    return false;
  }

  public function updateCourse($course, $file, $teacherId = null)
  {

    // Verificar si el usuario tiene permiso para actualizar el curso
    if ($teacherId !== null) {
      $stmt = $this->db->prepare('SELECT teacher_id FROM courses WHERE id = :id');
      $stmt->bindValue(':id', $course['id'], SQLITE3_INTEGER);
      $result = $stmt->execute();
      $existing = $result->fetchArray(SQLITE3_ASSOC);

      if (!$existing || $existing['teacher_id'] != $teacherId) {
        return false;
      }
    }

    // Si el usuario subió algo nuevo, procesamos el cambio de archivo
    if ($file && isset($file['size']) && $file['size'] > 0) {
      $this->createFileThumbnail($course['id'], $file);
    }

    $stmt = $this->db->prepare('UPDATE courses SET teacher_id = :teacher_id, title = :title, description = :description, category = :category, status = :status WHERE id = :id');
    $stmt->bindValue(':id', $course['id'], SQLITE3_INTEGER);
    $stmt->bindValue(':teacher_id', $course['teacher_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':title', $course['title'], SQLITE3_TEXT);
    $stmt->bindValue(':description', $course['description'], SQLITE3_TEXT);
    $stmt->bindValue(':category', $course['category'], SQLITE3_TEXT);
    $stmt->bindValue(':status', $course['status'], SQLITE3_TEXT);
    return $stmt->execute();
  }

  public function deleteCourse($courseId, $teacherId = null)
  {
    // Verificar si el usuario tiene permiso para eliminar el curso
    if ($teacherId !== null) {
      $stmt = $this->db->prepare('SELECT teacher_id FROM courses WHERE id = :id');
      $stmt->bindValue(':id', $courseId, SQLITE3_INTEGER);
      $result = $stmt->execute();
      $existing = $result->fetchArray(SQLITE3_ASSOC);

      if (!$existing || $existing['teacher_id'] != $teacherId) {
        return false;
      }
    }

    // Obtener la ruta de la miniatura antes de eliminar el curso

    $stmt = $this->db->prepare('SELECT thumbnail FROM courses WHERE id = :id');
    $stmt->bindValue(':id', $courseId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $course = $result->fetchArray(SQLITE3_ASSOC);

    if ($course) {
      $imagePath = $course['thumbnail'];
      $this->deleteFileThumbnail($imagePath);

      $delStmt = $this->db->prepare('DELETE FROM courses WHERE id = :id');
      $delStmt->bindValue(':id', $courseId, SQLITE3_INTEGER);
      return $delStmt->execute();
    }

    return false;
  }

  public function createFileThumbnail($courseId, $file)
  {
    $rootDir = dirname(__DIR__, 2);
    $targetDir = $rootDir . '/' . self::RUTA_BASE;
    if (!is_dir($targetDir)) {
      mkdir($targetDir, 0755, true);
    }

    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'thumbnail_course_' . $courseId . '.' . $fileExtension;
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
      $stmt = $this->db->prepare('UPDATE courses SET thumbnail = :thumbnail WHERE id = :id');
      $stmt->bindValue(':thumbnail', $fileName, SQLITE3_TEXT);
      $stmt->bindValue(':id', $courseId, SQLITE3_INTEGER);
      $stmt->execute();
      return true;
    } else {
      return false;
    }
  }

  public function deleteFileThumbnail($imagePath)
  {
    if ($imagePath && $imagePath !== 'default_course.jpg') {
      $rootDir = dirname(__DIR__, 2);
      $fullPath = $rootDir . '/' . self::RUTA_BASE . $imagePath;

      if (file_exists($fullPath)) {
        unlink($fullPath);
        return true;
      }
    }
    return false;
  }

  // Métodos para estadísticas
  public function countAllCourses()
  {
    $result = $this->db->query('SELECT COUNT(*) as total FROM courses');
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row ? $row['total'] : 0;
  }

  public function countCoursesByStatus($status)
  {
    $stmt = $this->db->prepare('SELECT COUNT(*) as total FROM courses WHERE status = :status');
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row ? $row['total'] : 0;
  }

  public function countCoursesByTeacher($teacherId)
  {
    $stmt = $this->db->prepare('SELECT COUNT(*) as total FROM courses WHERE teacher_id = :teacher_id');
    $stmt->bindValue(':teacher_id', $teacherId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row ? $row['total'] : 0;
  }
}
