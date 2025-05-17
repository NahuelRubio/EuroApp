<?php
session_start();
$user = $_SESSION['user'] ?? null;

// Eliminar usuario de la lista activa
if ($user) {
    $file = __DIR__ . '/active_users.json';
    $active = json_decode(file_get_contents($file), true) ?: [];
    $active = array_filter($active, fn($u) => $u !== $user);
    file_put_contents($file, json_encode(array_values($active)));
}

// Destruir la sesión
session_destroy();

// Redirigir al login
header('Location: login.php');
exit;
