# Lugo Tech - Plataforma de Cursos Online

Plataforma web de cursos online con sistema de roles (Admin, Profesor, Estudiante) que permite la gestión completa de cursos, lecciones y usuarios. Desarrollada con PHP vanilla y una estética Neo-Brutalista moderna.

## Tecnologías Utilizadas

| Tecnología       | Versión | Descripción                         |
| ---------------- | ------- | ----------------------------------- |
| **PHP**          | 8.x     | Backend y lógica del servidor       |
| **SQLite**       | 3       | Base de datos ligera y portable     |
| **Tailwind CSS** | 4.1     | Framework CSS utility-first         |
| **DaisyUI**      | 5.5     | Plugin de componentes para Tailwind |

### Librerías Frontend

- **Lucide Icons** - Iconografía SVG moderna
- **Archivo Black** - Fuente display para titulares
- **Plus Jakarta Sans** - Fuente principal para texto

## Características Principales

### Sistema de Roles

| Rol         | Permisos                                         |
| ----------- | ------------------------------------------------ |
| **Admin**   | Gestión completa de usuarios y cursos            |
| **Teacher** | Crear y gestionar sus propios cursos y lecciones |
| **Student** | Inscribirse a cursos y acceder al contenido      |

### Funcionalidades

- Autenticación de usuarios (login/registro)
- Dashboard personalizado según rol
- CRUD completo de cursos (crear, editar, eliminar)
- Gestión de lecciones por curso
- Sistema de inscripciones a cursos
- Categorización de cursos (Frontend/Backend)
- Estados de publicación (Pendiente/Publicado)
- Perfil de usuario con avatar personalizable
- Diseño responsive con tema claro/oscuro

## Quick Start

### Requisitos Previos

- [XAMPP](https://www.apachefriends.org/) instalado (o MAMP/LAMP)
- PHP 8.x habilitado
- Navegador web moderno

### Instalación

1. **Clonar el repositorio** en la carpeta `htdocs` de XAMPP:

```bash
cd C:\xampp\htdocs   # Windows
# o
cd /opt/lampp/htdocs # Linux

git clone https://github.com/LugoDv/plataforma-cursos
```

2. **Iniciar XAMPP** y asegurarse de que Apache esté corriendo.

3. **Inicializar la base de datos** accediendo a:

```
http://localhost/plataforma-cursos/database/initDB.php
```

4. **Acceder a la plataforma**:

```
http://localhost/plataforma-cursos/
```

### Usuarios de Prueba

| Rol        | Email                     | Contraseña |
| ---------- | ------------------------- | ---------- |
| Admin      | admin@lugotech.com        | admin      |
| Profesor   | profe.javier@lugotech.com | 12345      |
| Profesor   | profe.elena@lugotech.com  | 12345      |
| Estudiante | alumno.marcos@gmail.com   | 12345      |
| Estudiante | alumno.ana@gmail.com      | 12345      |

## Estructura del Proyecto

```
plataforma-cursos/
├── assets/
│   ├── css/          # Estilos (Tailwind + DaisyUI)
│   ├── img/          # Imágenes estáticas
│   ├── js/           # Scripts JavaScript
│   └── upload/       # Archivos subidos (avatars, thumbnails)
├── database/
│   ├── academy.db    # Base de datos SQLite
│   └── initDB.php    # Script de inicialización
├── includes/
│   ├── config.php    # Configuración global
│   ├── connectDB.php # Conexión a la BD
│   ├── dao/          # Data Access Objects
│   │   ├── CourseDao.php
│   │   ├── EnrollmentDao.php
│   │   ├── LessonDao.php
│   │   └── UserDao.php
│   └── *.php         # Componentes reutilizables
├── pages/            # Vistas principales
├── procedures/       # Controladores de acciones
└── index.php         # Página de inicio
```

## Desarrollo

Para compilar los estilos de Tailwind en modo desarrollo:

```bash
npm install
npm run dev
```

Esto iniciará el watcher de Tailwind que recompilará automáticamente los cambios en `assets/css/output.css`.

## Licencia

ISC
