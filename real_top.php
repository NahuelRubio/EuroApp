<?php
session_start();
if (empty($_SESSION['user'])) {
    http_response_code(403);
    exit;
}
$user = $_SESSION['user'];

// Lista de países y emojis de bandera
$countries = [
    'Noruega'=>'🇳🇴','Luxemburgo'=>'🇱🇺','Estonia'=>'🇪🇪','Lituania'=>'🇱🇹',
    'España'=>'🇪🇸','Ucrania'=>'🇺🇦','UK'=>'🇬🇧','Austria'=>'🇦🇹',
    'Islandia'=>'🇮🇸','Latvia'=>'🇱🇻','Paises Bajos'=>'🇳🇱','Finlandia'=>'🇫🇮',
    'Italia'=>'🇮🇹','Polonia'=>'🇵🇱','Alemania'=>'🇩🇪','Grecia'=>'🇬🇷',
    'Armenia'=>'🇦🇲','Suiza'=>'🇨🇭','Malta'=>'🇲🇹','Portugal'=>'🇵🇹',
    'Dinamarca'=>'🇩🇰','Suecia'=>'🇸🇪','Francia'=>'🇫🇷','San Marino'=>'🇸🇲',
    'Albania'=>'🇦🇱'
];

// Ruta de archivo de orden guardado
$file = __DIR__ . "/orders/" . (strpos(__FILE__, 'dream_') !== false ? "dream_{$user}.json" : "real_{$user}.json");
$order = [];
if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
    if (is_array($data)) {
        $order = $data;
    }
}
if (empty($order)) {
    $order = array_keys($countries);
}

?>
<div>
<h1><?= strpos(__FILE__, 'dream_') !== false ? 'Mi top ideal' : 'Top realista' ?></h1>
  <table id="countries-table">
    <thead>
      <tr><th>Pos</th><th>País</th></tr>
    </thead>
    <tbody>
      <?php foreach ($order as $i => $country): ?>
      <tr draggable="true" data-country="<?= htmlspecialchars($country) ?>">
        <td><?= $i + 1 ?></td>
        <td><?= $countries[$country] ." ".htmlspecialchars($country) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<style>
  table#countries-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(4px);
    border-radius: 12px;
    overflow: hidden;
    color: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
  }

  #countries-table thead {
    background-color: rgba(255, 255, 255, 0.15);
  }

  #countries-table th, #countries-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
  }

  #countries-table tr:last-child td {
    border-bottom: none;
  }

  #countries-table tr:nth-child(even) {
    background-color: rgba(255, 255, 255, 0.05);
  }

  #countries-table th {
    font-weight: bold;
    text-transform: uppercase;
    font-size: 14px;
  }

  #countries-table td {
    font-size: 14px;
  }

  #countries-table td:nth-child(2) {
    font-size: 20px;
  }

  tr.selected {
    background-color: rgba(255, 255, 255, 0.2) !important;
  }
  #countries-table tr {
  cursor: grab;
  user-select: none;
}

</style>
