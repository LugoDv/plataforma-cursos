<?php
require_once __DIR__ . '/../includes/connectDB.php';



$db->exec('PRAGMA foreign_keys = ON;');

$tables = "
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    profile_picture TEXT DEFAULT 'default_avatar.jpg',
    role TEXT NOT NULL CHECK (role IN ('admin', 'student', 'teacher'))
);

CREATE TABLE IF NOT EXISTS courses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    teacher_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    thumbnail TEXT NOT NULL DEFAULT 'default_course.jpg',
    category TEXT NOT NULL CHECK (category IN ('backend', 'frontend')),
    status TEXT NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'published')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS lessons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    course_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    content TEXT,
    video_url TEXT,
    order_index INTEGER NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS enrollments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    course_id INTEGER NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);
";

if ($db->exec($tables)) {
  echo "Tablas creadas correctamente.";

  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Admin', 'admin@lugotech.com', '" . md5('admin') . "', 'admin')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Javier', 'profe.javier@lugotech.com', '" . md5('12345') . "', 'teacher')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Elena', 'profe.elena@lugotech.com', '" . md5('12345') . "', 'teacher')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Marcos', 'alumno.marcos@gmail.com', '" . md5('12345') . "', 'student')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Ana', 'alumno.ana@gmail.com', '" . md5('12345') . "', 'student')");

  echo "Usuarios insertados correctamente.";
  $sql_populate_courses = "
INSERT INTO courses (teacher_id, title, description, thumbnail, category, status) VALUES 
(2, 'Master en PHP Moderno', 'Domina PHP 8.x, POO avanzada y seguridad en aplicaciones backend.', 'php.jpg', 'backend', 'published'),
(2, 'React Interface Design', 'Creación de componentes reutilizables y gestión de estado con la estética Neo-Brutalista.', 'react.jpg', 'frontend', 'published'),
(2, 'Arquitectura de APIs con SQLite', 'Diseño de sistemas ligeros, potentes y portables para microservicios.', 'sqlite.jpg', 'backend', 'pending'),
(2, 'Neo-Brutalismo UI Kit', 'Domina la tendencia visual de bordes gruesos y sombras sólidas con Tailwind 4.', 'ui.jpg', 'frontend', 'published'),
(2, 'Node.js de Cero a Pro', 'Construcción de servidores escalables utilizando Express y arquitectura limpia.', 'node.jpg', 'backend', 'published'),
(2, 'CSS Avanzado y Animaciones', 'Layouts complejos con Grid, Flexbox y animaciones de alto rendimiento.', 'css.jpg', 'frontend', 'pending');
";

  // Ejecución directa
  $db->exec($sql_populate_courses);
  echo "Cursos insertados correctamente.";
  $sql_populate_lessons = "
INSERT INTO lessons (course_id, title, content, video_url, order_index) VALUES 
-- Lecciones para el Curso 1 (PHP Moderno)
(1, 'Introducción a PHP 8.x', 'Contenido de bienvenida al curso.', 'https://youtube.com/link1', 1),
(1, 'Tipado Estricto y Union Types', 'Explicación detallada de tipos en PHP.', 'https://youtube.com/link2', 2),
(1, 'Programación Orientada a Objetos', 'Clases, interfaces y polimorfismo.', 'https://youtube.com/link3', 3),

-- Lecciones para el Curso 2 (React Design)
(2, 'Configurando el entorno con Vite', 'Guía paso a paso para iniciar.', 'https://youtube.com/link4', 1),
(2, 'Hooks Básicos: useState y useEffect', 'Manejo de estados y ciclos de vida.', 'https://youtube.com/link5', 2),
(2, 'Custom Hooks para Neo-Brutalismo', 'Creando lógica reutilizable.', NULL, 3),

-- Lecciones para el Curso 3 (APIs con SQLite)
(3, 'Configuración de la DB', 'Creando el archivo .db y tablas.', NULL, 1),
(3, 'Estructura de una API REST', 'Rutas, controladores y modelos.', 'https://youtube.com/link6', 2),
(3, 'Seguridad y Sanitización', 'Evitando inyección SQL en SQLite.', NULL, 3);
";

  $db->exec($sql_populate_lessons);
  echo "Lecciones insertadas correctamente.";

  $sql_enroll_students = "
INSERT INTO enrollments (user_id, course_id) VALUES
(4, 1),
(4, 2),
(5, 1),
(5, 2);
";

  $db->exec($sql_enroll_students);
  echo "Estudiantes inscritos correctamente.";

  $db->close();
} else {
  echo "Error al crear las tablas.";
}

$db->close();
