<?php
session_start();

// Usuarios permitidos
$allowed = ['nahuel','lili','ml'];
$input = trim($_POST['user'] ?? '');

// Cargar lista de activos
$file = __DIR__ . '/active_users.json';
$active = json_decode(file_get_contents($file), true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!in_array($input, $allowed)) {
        $error = 'Usuario no válido';
    } elseif (in_array($input, $active)) {
        $error = 'Ese usuario ya está conectado';
    } elseif (count($active) >= 3) {
        $error = 'El servidor está lleno';
    } else {
        $active[] = $input;
        file_put_contents($file, json_encode($active));
        $_SESSION['user'] = $input;
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.1);
      padding: 30px 20px;
      border-radius: 12px;
      text-align: center;
      width: 90%;
      max-width: 300px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      backdrop-filter: blur(8px);
    }

    h1 {
      margin-bottom: 20px;
    }

    input {
      padding: 10px;
      border: none;
      border-radius: 6px;
      width: 90%;
      margin-bottom: 15px;
      font-size: 16px;
      font-weight: bold;
    }

    button {
      padding: 10px;
      border: none;
      border-radius: 6px;
      width: 90%;
      font-size: 16px;
      background: #fff;
      color: #2575fc;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #f0f0f0;
    }

    .error {
      background: #ff4d4d;
      padding: 8px;
      border-radius: 6px;
      margin-bottom: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h1>A EuroVisionar</h1>
    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <input name="user" placeholder="Tu nombre..." required>
      <button type="submit">Entrar</button>
    </form>
  </div>
</body>
</html>
