<?php
session_start();
if (empty($_SESSION['user'])) {
    http_response_code(403);
    exit;
}
$user = $_SESSION['user'];

$countries = [
    'Albania' => '🇦🇱',
    'Alemania' => '🇩🇪',
    'Armenia' => '🇦🇲',
    'Austria' => '🇦🇹',
    'Dinamarca' => '🇩🇰',
    'Estonia' => '🇪🇪',
    'España' => '🇪🇸',
    'Finlandia' => '🇫🇮',
    'Francia' => '🇫🇷',
    'Grecia' => '🇬🇷',
    'Islandia' => '🇮🇸',
    'Italia' => '🇮🇹',
    'Latvia' => '🇱🇻',
    'Lituania' => '🇱🇹',
    'Luxemburgo' => '🇱🇺',
    'Malta' => '🇲🇹',
    'Noruega' => '🇳🇴',
    'Países Bajos' => '🇳🇱',
    'Polonia' => '🇵🇱',
    'Portugal' => '🇵🇹',
    'San Marino' => '🇸🇲',
    'Suecia' => '🇸🇪',
    'Suiza' => '🇨🇭',
    'UK' => '🇬🇧',
    'Ucrania' => '🇺🇦'
];

$file = __DIR__ . "/orders/points_{$user}.json";
$points = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
arsort($points);
?>

<div>
    <h1>Puntuar Países</h1>
    <label for="country-select">Selecciona un país:</label>
    <select id="country-select" class="styled-select">
        <option value="">-- Elige un país --</option>
        <?php foreach ($countries as $name => $flag): ?>
            <option value="<?= htmlspecialchars($name) ?>"><?= $flag ?>     <?= htmlspecialchars($name) ?></option>
        <?php endforeach; ?>
    </select>



    <div id="rating-section" style="margin-top:20px; display:none;">
        <label for="rating-range">
            Puntuación:
            <span id="range-value" style="font-weight: bold; font-size: 20px; color: #4caf50;">50</span> / 100
        </label>
        <input type="range" id="rating-range" min="1" max="100" value="50" class="styled-range">
        <button id="save-rating">Guardar puntuación</button>
    </div>

    <h2>Ranking</h2>
    <table id="score-table"> <!-- Estilo igual que antes -->
        <thead>
            <tr>
                <th>Pos</th>
                <th>País</th>
                <th>Puntos</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($points as $country => $score):
                $color = '#4caf50';
                if ($score <= 33)
                    $color = '#e74c3c';
                elseif ($score <= 66)
                    $color = '#f39c12'; ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $countries[$country] ?>     <?= htmlspecialchars($country) ?></td>
                    <td style="color:<?= $color ?>; font-weight:bold;"><?= $score ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal para empate -->
<div id="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
  background:rgba(0,0,0,0.7); z-index:999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:20px; border-radius:10px; color:black; max-width:90%;">
        <p id="modal-text"></p>
        <button id="modal-up">⬆️ Subir ( +1 )</button>
        <button id="modal-down">⬇️ Bajar ( -1 )</button>
    </div>
</div>

<style>
    table#score-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(4px);
        border-radius: 12px;
        overflow: hidden;
        color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    #score-table thead {
        background-color: rgba(255, 255, 255, 0.15);
    }

    #score-table th,
    #score-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    #score-table tr:last-child td {
        border-bottom: none;
    }

    #score-table tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.05);
    }

    #score-table th {
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
    }

    #score-table td {
        font-size: 14px;
    }

    #score-table td:nth-child(2) {
        font-size: 20px;
    }

    /* Colorear puntuaciones altas, medias y bajas */
    #score-table td:last-child {
        font-weight: bold;
    }

    #score-table td:last-child:before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
        background-color: currentColor;
    }

    #score-table td:last-child {
        color: white;
    }

    #score-table tr td:last-child {
        color: #4caf50;
        /* verde por defecto */

        /* estilos condicionales simples si se desea JS más adelante */
    }

    input[type="range"].styled-range {
        -webkit-appearance: none;
        width: 100%;
        height: 8px;
        border-radius: 5px;
        background: linear-gradient(to right, #4caf50 0%, #4caf50 16%, #ddd 16%, #ddd 100%);
        outline: none;
        transition: background 0.3s;
        margin-bottom: 20px;
    }

    input[type="range"].styled-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        height: 20px;
        width: 20px;
        background: white;
        border: 2px solid #4caf50;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s, border 0.3s;
        margin-top: -6px;
    }

    input[type="range"].styled-range::-moz-range-thumb {
        height: 20px;
        width: 20px;
        background: white;
        border: 2px solid #4caf50;
        border-radius: 50%;
        cursor: pointer;
    }

    .styled-select {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-size: 16px;
        backdrop-filter: blur(4px);
        margin-top: 8px;
        margin-bottom: 20px;
        outline: none;
        appearance: none;
        cursor: pointer;
    }

    .styled-select option {
        background-color: #222;
        color: white;
    }
</style>