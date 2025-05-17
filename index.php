<?php
session_start();
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi App</title>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <script src="js/app.js" defer></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        header {
            padding: 15px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header div {
            font-size: 16px;
        }

        .logout-form button {
            padding: 6px 10px;
            font-size: 14px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        #content {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }

        .bottom-nav {
            display: flex;
            justify-content: space-around;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .bottom-nav button {
            background: none;
            border: none;
            font-size: 14px;
            padding: 10px;
            flex: 1;
            color: white;
            font-weight: bold;
            opacity: 0.7;
            transition: opacity 0.2s, transform 0.2s;
        }

        .bottom-nav button.active {
            opacity: 1;
            transform: scale(1.1);
        }

        .bottom-nav button:hover {
            opacity: 1;
        }

        /* Visual para la fila seleccionada */
        #countries-table tr.selected {
            background-color: #ffeaa7;
        }

        /* Margen para que el botón no quede oculto */
        #content {
            padding-bottom: 80px;
            /* espacio suficiente para el menú */
        }

        tr.selected {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }
    </style>
</head>

<body>
    <header>
        <div>usuario: <?= htmlspecialchars($user) ?></div>
        <?php if ($user === 'nahuel'): ?>
            <form method="post" action="kick.php" style="margin-left: 10px;">
                <select name="kick_user">
                    <option value="all">Expulsar a todos</option>
                    <option value="nahuel">Expulsar a nahuel</option>
                    <option value="lili">Expulsar a lili</option>
                    <option value="ml">Expulsar a ml</option>
                </select>
                <button type="submit">💣 Expulsar</button>
            </form>
        <?php endif; ?>

        <form method="post" action="logout.php" class="logout-form">
            <button type="submit">Cerrar sesión</button>
        </form>
    </header>

    <div id="content"></div>

    <nav class="bottom-nav">
        <button data-page="dream_top" class="active">🌙 Mi Top</button>
        <button data-page="real_top">🧠 Top Real</button>
        <button data-page="puntuar">⭐ Puntuar</button>
        <button data-page="puntuaciones">📊 Puntuaciones</button>

    </nav>
</body>

</html>