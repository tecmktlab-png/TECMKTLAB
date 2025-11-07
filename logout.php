<?php
session_start();
$_SESSION = [];
session_destroy();

header('Cache-Control: no-store');
header('Location: admin.html', true, 302);
exit; // <- importante para não “vazar” nada e garantir o redirect
