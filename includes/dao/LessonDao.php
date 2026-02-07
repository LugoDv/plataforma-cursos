<?php
class LessonDao
{
  private $db;

  public function __construct($databaseConnection)
  {
    $this->db = $databaseConnection;
  }

  public function countLessonsByCourse($courseId)
  {
    $stmt = $this->db->prepare('SELECT COUNT(*) as total FROM lessons WHERE course_id = :course_id');
    $stmt->bindValue(':course_id', $courseId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    return $row ? $row['total'] : 0;
  }

  public function getLessonsByCourse($courseId)
  {
    $stmt = $this->db->prepare('SELECT * FROM lessons WHERE course_id = :course_id ORDER BY order_index ASC');
    $stmt->bindValue(':course_id', $courseId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $lessons = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $lessons[] = $row;
    }
    return $lessons;
  }

  public function createLesson($courseId, $title, $videoUrl, $orderIndex, $content)
  {
    $stmt = $this->db->prepare('INSERT INTO lessons (course_id, title, video_url, order_index, content) VALUES (:course_id, :title, :video_url, :order_index, :content)');
    $stmt->bindValue(':course_id', $courseId, SQLITE3_INTEGER);
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);
    $stmt->bindValue(':video_url', $videoUrl, SQLITE3_TEXT);
    $stmt->bindValue(':order_index', $orderIndex, SQLITE3_INTEGER);
    $stmt->bindValue(':content', $content, SQLITE3_TEXT);
    return $stmt->execute();
  }

  public function updateLesson($lessonId, $title, $videoUrl, $orderIndex, $content)
  {
    $stmt = $this->db->prepare('UPDATE lessons SET title = :title, video_url = :video_url, order_index = :order_index, content = :content WHERE id = :lesson_id');
    $stmt->bindValue(':lesson_id', $lessonId, SQLITE3_INTEGER);
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);
    $stmt->bindValue(':video_url', $videoUrl, SQLITE3_TEXT);
    $stmt->bindValue(':order_index', $orderIndex, SQLITE3_INTEGER);
    $stmt->bindValue(':content', $content, SQLITE3_TEXT);
    return $stmt->execute();
  }

  public function deleteLesson($lessonId)
  {
    $stmt = $this->db->prepare('DELETE FROM lessons WHERE id = :lesson_id');
    $stmt->bindValue(':lesson_id', $lessonId, SQLITE3_INTEGER);
    return $stmt->execute();
  }
}
