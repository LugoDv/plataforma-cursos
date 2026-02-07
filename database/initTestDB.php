<?php
require_once __DIR__ . '/../includes/connectDB.php';



$db->exec('PRAGMA foreign_keys = ON;');

$db->exec("DROP TABLE IF EXISTS enrollments;");
$db->exec("DROP TABLE IF EXISTS lessons;");
$db->exec("DROP TABLE IF EXISTS courses;");
$db->exec("DROP TABLE IF EXISTS users;");

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
  echo "✅ Estructura de tablas creada.<br>";

  // 2. Inserción de Usuarios (Admin, Teachers, Students)
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Admin', 'admin@lugotech.com', '" . md5('admin') . "', 'admin')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Javier', 'profe.javier@lugotech.com', '" . md5('12345') . "', 'teacher')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Elena', 'profe.elena@lugotech.com', '" . md5('12345') . "', 'teacher')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Marcos', 'alumno.marcos@gmail.com', '" . md5('12345') . "', 'student')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Ana', 'alumno.ana@gmail.com', '" . md5('12345') . "', 'student')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Carlos', 'carlos@gmail.com', '" . md5('12345') . "', 'student')");
  $db->exec("INSERT INTO users (username, email, password, role) VALUES ('Sofia', 'sofia@gmail.com', '" . md5('12345') . "', 'student')");

  echo "✅ Usuarios de prueba insertados.<br>";

  $sql_populate_courses = "
    INSERT INTO courses (teacher_id, title, description, thumbnail, category, status) VALUES 
    (2, 'Master en PHP Moderno', 'Domina PHP 8.x, POO avanzada y seguridad en aplicaciones backend.', 'php.jpg', 'backend', 'published'),
    (2, 'React Interface Design', 'Creación de componentes reutilizables y gestión de estado con estética Neo-Brutalista.', 'react.jpg', 'frontend', 'published'),
    (2, 'Arquitectura de APIs con SQLite', 'Diseño de sistemas ligeros, potentes y portables para microservicios.', 'sqlite.jpg', 'backend', 'pending'),
    (2, 'Neo-Brutalismo UI Kit', 'Domina la tendencia visual de bordes gruesos y sombras sólidas con Tailwind 4.', 'ui.jpg', 'frontend', 'published'),
    (2, 'Node.js de Cero a Pro', 'Construcción de servidores escalables utilizando Express y arquitectura limpia.', 'node.jpg', 'backend', 'published'),
    (2, 'CSS Avanzado y Animaciones', 'Layouts complejos con Grid, Flexbox y animaciones de alto rendimiento.', 'css.jpg', 'frontend', 'pending'),
    (3, 'Tailwind CSS 4 Masterclass', 'Diseño ultra rápido con la nueva versión del framework más popular.', 'tailwind.jpg', 'frontend', 'published'),
    (3, 'Docker para Desarrolladores', 'Contenedores desde cero para desplegar tus apps PHP y Node.', 'docker.jpg', 'backend', 'pending'),
    (2, 'Animaciones con Framer Motion', 'Crea interfaces que cobren vida con React y animaciones fluidas.', 'framer.jpg', 'frontend', 'published');
    ";
  $db->exec($sql_populate_courses);
  echo "✅ Cursos insertados correctamente.<br>";

  $sql_populate_lessons = "
    INSERT INTO lessons (course_id, title, content, video_url, order_index) VALUES 
    -- PHP Moderno
    (1, 'Introducción a PHP 8.x', 'Bienvenido al curso. Aquí veremos los fundamentos.', 'https://www.youtube.com/watch?v=lLsyzBggW_o&list=PLH_tVOsiVGzmnl7ImSmhIw5qb9Sy5KJRE', 1),
    (1, 'Programación Orientada a Objetos', 'Clases, interfaces y polimorfismo en PHP.', 'https://www.youtube.com/watch?v=YqSB8WSlb2o&list=PLH_tVOsiVGzmnl7ImSmhIw5qb9Sy5KJRE&index=2', 2),
    (1, 'Manejo de Base de Datos', 'Conexión segura con PDO y SQLite.', 'https://www.youtube.com/watch?v=BhOTNewtPcE&list=PLH_tVOsiVGzmnl7ImSmhIw5qb9Sy5KJRE&index=3', 3),
    
    -- React Design
    (2, 'Primeros pasos con Vite', 'Instalación y estructura del proyecto.', 'https://www.youtube.com/watch?v=Un0qRgXNS9E&list=PLPl81lqbj-4J2xx_YAb97dpCG1rxl2wv-', 1),
    (2, 'Componentes y Props', 'Comunicación entre elementos de UI.', 'https://www.youtube.com/watch?v=ABSGBn1-mNM&list=PLPl81lqbj-4J2xx_YAb97dpCG1rxl2wv-&index=2', 2),
    

    -- Curso 3: APIs
    (3, 'Endpoints RESTful', 'Diseño de rutas limpias.', 'https://www.youtube.com/watch?v=u2Ms34GE14U', 1),
    (3, 'CRUD con SQLite', 'Persistencia de datos.', 'https://www.youtube.com/watch?v=GeAvqKqClsw', 2),

    -- Curso 5: Tailwind 4
    (7, 'Instalación de Tailwind 4', 'Novedades de la versión.', 'https://www.youtube.com/watch?v=W0Si4Dz13dw&list=PLg9145ptuAijcFlJGKxgGIzH915uBzXrv', 1),
    (7, 'Configuración del motor JIT', 'Optimización máxima.', 'https://www.youtube.com/watch?v=nDC-94fVAW8&list=PLg9145ptuAijcFlJGKxgGIzH915uBzXrv&index=2', 2),  
    
    -- Curso 6: Docker
    (8, 'Hola Mundo con Docker', 'Primer contenedor.', 'https://www.youtube.com/watch?v=CV_Uf3Dq-EU', 1),
    (8, 'Docker Compose', 'Orquestación simple.', 'https://www.youtube.com/watch?v=DM65_JyGxCo', 2),

    -- Curso 5: Node.js
    (5, 'Introducción a Node.js', '¿Qué es Node y para qué sirve?', 'https://www.youtube.com/watch?v=LAUi8pPlcUM&list=PLC3y8-rFHvwh8shCMHFA5kWxD9PaPwxaY', 1),
    (5, 'Express.js Básico', 'Creando tu primer servidor.', 'https://www.youtube.com/watch?v=HXpPKhWOkAs&list=PLC3y8-rFHvwh8shCMHFA5kWxD9PaPwxaY&index=2', 2),

    -- Curso 9: Animaciones Framer
    (9, 'Framer Motion para Principiantes', 'Animaciones básicas en React.', 'https://www.youtube.com/watch?v=2V1WK-3HQNk', 1),
    (9, 'Animaciones Avanzadas', 'Gestos y animaciones complejas.', 'https://www.youtube.com/watch?v=znbCa4Rr054', 2),
    
    -- Curso 6: CSS Avanzado
    (6, 'CSS Grid Avanzado', 'Diseños complejos con CSS Grid.', 'https://www.youtube.com/watch?v=0xMQfnTU6oo&list=PL4cUxeGkcC9gQeDH6xYhmO-db2mhoTSrT', 1),
    (6, 'Animaciones con Keyframes', 'Dale vida a tus interfaces.', 'https://www.youtube.com/watch?v=HkPp3Zs8VjA&list=PL4cUxeGkcC9gQeDH6xYhmO-db2mhoTSrT&index=2', 2),
    
    -- Curso 4: Neo-Brutalismo UI
    (4, 'Filosofía del Diseño Brutalista', 'Por qué los bordes gruesos están de moda.', 'https://www.youtube.com/watch?v=BNEtT_76CxY', 1),
    (4, 'Implementación con Tailwind', 'Creando clases personalizadas para sombras.', 'https://www.youtube.com/watch?v=QVvyiSkGcFM', 2);
    ";
  $db->exec($sql_populate_lessons);
  echo "✅ Lecciones reales insertadas.<br>";

  $sql_enroll_students = "
    INSERT INTO enrollments (user_id, course_id) VALUES
    (4, 1), (5, 1), (6, 1), (7, 1), -- 4 Alumnos en PHP
    (4, 2), (5, 2), (6, 2),         -- 3 Alumnos en React
    (4, 4);                         -- 1 Alumno en UI Kit
    ";
  $db->exec($sql_enroll_students);
  echo "✅ Alumnos inscritos en los cursos.<br>";

  echo "<br>🚀 <strong>PROYECTO INICIALIZADO CON ÉXITO</strong>. Ya puedes loguearte con <strong>profe.javier@lugotech.com</strong> (pass: 12345).";
} else {
  echo "❌ Error al crear las tablas.";
}

$db->close();
