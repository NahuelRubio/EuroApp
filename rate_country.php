<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'msg' => 'No autorizado']);
    exit;
}

$user = $_SESSION['user'];
$input = json_decode(file_get_contents('php://input'), true);
$country = $input['country'] ?? '';
$score = (int)($input['score'] ?? 0);

if (!$country || $score < 1 || $score > 100) {
    echo json_encode(['status' => 'error', 'msg' => 'Datos inválidos']);
    exit;
}

$file = __DIR__ . "/orders/points_{$user}.json";
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$empateCon = null;
foreach ($data as $c => $s) {
    if ($c !== $country && $s === $score) {
        $empateCon = $c;
        break;
    }
}

if ($empateCon) {
    echo json_encode([
        'status' => 'empate',
        'conflict' => $empateCon
    ]);
    exit;
}

$data[$country] = $score;
file_put_contents($file, json_encode($data));
echo json_encode(['status' => 'ok']);
