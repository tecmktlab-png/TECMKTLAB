<?php
session_start();
if (empty($_SESSION['adm_ok'])) {
  http_response_code(401);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'unauthorized';
  exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || empty($data['id'])) {
  http_response_code(400);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'bad request';
  exit;
}

$path = __DIR__ . '/posts.json';
$posts = [];
if (file_exists($path)) {
  $json = file_get_contents($path);
  $posts = json_decode($json, true);
  if (!is_array($posts)) { $posts = []; }
}

// substitui se id já existir, senão adiciona
$found = false;
foreach ($posts as $k => $p) {
  if (!empty($p['id']) && $p['id'] === $data['id']) {
    $posts[$k] = $data; $found = true; break;
  }
}
if (!$found) $posts[] = $data;

// ordena por data desc (opcional)
usort($posts, function($a,$b){
  return strcmp($b['date'] ?? '', $a['date'] ?? '');
});

// salva
file_put_contents($path, json_encode($posts, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['ok'=>1, 'total'=>count($posts)]);
