<?php
session_start();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
  // Checa sessão
  if (!empty($_SESSION['adm_ok'])) {
    http_response_code(200);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'ok';
  } else {
    http_response_code(401);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'no';
  }
  exit;
}

if ($method === 'POST') {
  $user = $_POST['user'] ?? '';
  $pass = $_POST['pass'] ?? '';

  // TODO: mover para config e usar hash (password_hash/password_verify)
  $VALID_USER = 'admin';
  $VALID_PASS = 'mudar123'; // TROQUE ESTA SENHA

  if ($user === $VALID_USER && $pass === $VALID_PASS) {
    $_SESSION['adm_ok'] = true;
    header('Location: admin.html');
    exit;
  }
  http_response_code(403);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'Credenciais inválidas';
  exit;
}

http_response_code(405);
echo 'Method not allowed';
