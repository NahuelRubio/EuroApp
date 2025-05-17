<?php
session_start();
if (empty($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit;
}

$user = $_SESSION['user'];

// Leer cuerpo JSON
$input = json_decode(file_get_contents('php://input'), true);

$section = $input['section'] ?? '';
$order = $input['order'] ?? [];

if (!in_array($section, ['dream','real']) || !is_array($order)) {
    http_response_code(400);
    echo json_encode(['status'=>'error','msg'=>'Datos inválidos']);
    exit;
}

$file = __DIR__ . "/orders/{$section}_{$user}.json";
file_put_contents($file, json_encode($order));

echo json_encode(['status'=>'ok']);
exit;
