<?php
function redirect($page)
{
  header("Location: " . BASE_URL . $page);
  exit();
}
function cleanInput($data)
{
  return htmlspecialchars(strip_tags(trim($data)));
}


// Función para extraer el ID de video de YouTube
function getYouTubeEmbedUrl($url)
{
  if (empty($url)) return null;

  // Diferentes patrones de URLs de YouTube
  $patterns = [
    '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
    '/youtu\.be\/([a-zA-Z0-9_-]+)/',
    '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/'
  ];

  foreach ($patterns as $pattern) {
    if (preg_match($pattern, $url, $matches)) {
      return 'https://www.youtube.com/embed/' . $matches[1];
    }
  }

  return $url;
}
