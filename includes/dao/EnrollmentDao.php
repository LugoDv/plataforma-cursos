<?php
class EnrollmentDao
{
  private $dbConnection;

  public function __construct($dbConnection)
  {
    $this->dbConnection = $dbConnection;
  }


  public function coutCursesByStudent($studentId)
  {
    $stmt = $this->dbConnection->prepare('SELECT COUNT(*) as total FROM enrollments WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $studentId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    return $row ? $row['total'] : 0;
  }

  public function getCoursesByStudent($studentId)
  {
    $stmt = $this->dbConnection->prepare("SELECT c.* FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE e.user_id = :user_id ORDER BY c.title");
    $stmt->bindValue(':user_id', $studentId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $courses = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $courses[] = $row;
    }
    return $courses;
  }

  public function isSubscribed($studentId, $courseId)
  {
    $stmt = $this->dbConnection->prepare('SELECT COUNT(*) as count FROM enrollments WHERE user_id = :userId AND course_id = :courseId');
    $stmt->bindValue('userId', $studentId, SQLITE3_INTEGER);
    $stmt->bindValue('courseId', $courseId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    return $row && $row['count'] > 0;
  }

  public function subscribeStudent($studentId, $courseId)
  {
    $stmt = $this->dbConnection->prepare('INSERT INTO enrollments (user_id,course_id) VALUES (:userId, :courseId)');
    $stmt->bindValue('userId', $studentId, SQLITE3_INTEGER);
    $stmt->bindValue('courseId', $courseId, SQLITE3_INTEGER);

    return $stmt->execute();
  }

  public function getStudentsByCourse($courseId)
  {
    $stmt = $this->dbConnection->prepare("SELECT u.id, u.username, u.email, e.enrolled_at FROM enrollments e JOIN users u ON e.user_id = u.id WHERE e.course_id = :course_id ORDER BY u.username");
    $stmt->bindValue(':course_id', $courseId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $students = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $students[] = $row;
    }
    return $students;
  }

  public function countStudentsByCourse($courseId)
  {
    $stmt = $this->dbConnection->prepare('SELECT COUNT(*) as total FROM enrollments WHERE course_id = :course_id');
    $stmt->bindValue(':course_id', $courseId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    return $row ? $row['total'] : 0;
  }

  // Métodos para estadísticas
  public function countTotalEnrollments()
  {
    $result = $this->dbConnection->query('SELECT COUNT(*) as total FROM enrollments');
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row ? $row['total'] : 0;
  }

  public function countStudentsByTeacher($teacherId)
  {
    $stmt = $this->dbConnection->prepare('SELECT COUNT(DISTINCT e.user_id) as total FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE c.teacher_id = :teacher_id');
    $stmt->bindValue(':teacher_id', $teacherId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row ? $row['total'] : 0;
  }
}
