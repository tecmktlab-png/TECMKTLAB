<?php
session_start();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function respond_text($code, $msg){
  http_response_code($code);
  header('Content-Type: text/plain; charset=utf-8');
  echo $msg;
  exit;
}

// Tratar pré-flight ou HEAD amigavelmente
if ($method === 'OPTIONS') {
  header('Allow: GET, POST, OPTIONS, HEAD');
  http_response_code(204);
  exit;
}
if ($method === 'HEAD') {
  header('Allow: GET, POST, OPTIONS, HEAD');
  http_response_code(200);
  exit;
}

if ($method === 'GET') {
  if (!empty($_SESSION['adm_ok'])) {
    respond_text(200, 'ok');
  } else {
    respond_text(401, 'no');
  }
}

if ($method === 'POST') {
  // IMPORTANTE: o <form> precisa ser method="post" action="login.php"
  $user = $_POST['user'] ?? '';
  $pass = $_POST['pass'] ?? '';

  // Troque estas credenciais depois
  $VALID_USER = 'admin';
  $VALID_PASS = 'mudar123';

  if ($user === $VALID_USER && $pass === $VALID_PASS) {
    $_SESSION['adm_ok'] = true;
    header('Location: admin.html');
    exit;
  }
  respond_text(403, 'Credenciais inválidas');
}

// Qualquer outra coisa: só responda GET padrão em vez de 405
respond_text(200, !empty($_SESSION['adm_ok']) ? 'ok' : 'no');
