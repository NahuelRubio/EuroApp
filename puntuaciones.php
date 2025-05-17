<?php
session_start();
if (empty($_SESSION['user'])) { http_response_code(403); exit; }

$users = ['nahuel', 'lili', 'ml'];

$countries = [
  'Albania' => '🇦🇱','Alemania'=>'🇩🇪','Armenia'=>'🇦🇲','Austria'=>'🇦🇹','Dinamarca'=>'🇩🇰',
  'Estonia'=>'🇪🇪','España'=>'🇪🇸','Finlandia'=>'🇫🇮','Francia'=>'🇫🇷','Grecia'=>'🇬🇷',
  'Islandia'=>'🇮🇸','Italia'=>'🇮🇹','Latvia'=>'🇱🇻','Lituania'=>'🇱🇹','Luxemburgo'=>'🇱🇺',
  'Malta'=>'🇲🇹','Noruega'=>'🇳🇴','Países Bajos'=>'🇳🇱','Polonia'=>'🇵🇱','Portugal'=>'🇵🇹',
  'San Marino'=>'🇸🇲','Suecia'=>'🇸🇪','Suiza'=>'🇨🇭','UK'=>'🇬🇧','Ucrania'=>'🇺🇦'
];

// Cargar resultados reales si existen
$finalResults = [];
$finalFile = __DIR__ . '/final_results.json';
if (file_exists($finalFile)) {
    $data = json_decode(file_get_contents($finalFile), true);
    if (is_array($data)) {
        foreach ($data as $entry) {
            $finalResults[$entry['pos']] = $entry;
        }
    }
}
?>

<?php foreach ($users as $user): ?>
  <?php
    $file = __DIR__ . "/orders/points_{$user}.json";
    if (!file_exists($file)) continue;

    $data = json_decode(file_get_contents($file), true);
    if (!is_array($data) || count($data) === 0) continue;

    arsort($data);
    $top5 = array_slice($data, 0, 5, true);
    if (count($top5) === 0) continue;
  ?>

  <h2 style="margin-top: 40px;"><?= ucfirst($user) ?></h2>
  <table class="styled-table">
    <thead>
      <tr>
        <th>Pos</th>
        <th>País</th>
        <th>Pos Real</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; foreach ($top5 as $country => $score): ?>
        <tr>
          <td><?= $i ?></td>
          <td><?= $countries[$country] ?? '🏳️' ?> <?= htmlspecialchars($country) ?></td>
          <td>
            <?php if (isset($finalResults[$i])): ?>
              <?= $finalResults[$i]['flag'] ?> <?= htmlspecialchars($finalResults[$i]['country']) ?>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
        </tr>
      <?php $i++; endforeach; ?>
    </tbody>
  </table>
<?php endforeach; ?>

<style>
.styled-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(4px);
  border-radius: 12px;
  overflow: hidden;
  color: white;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.styled-table th, .styled-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}

.styled-table thead {
  background-color: rgba(255, 255, 255, 0.15);
  font-weight: bold;
  text-transform: uppercase;
  font-size: 14px;
}

.styled-table td:nth-child(2) {
  font-size: 18px;
}
</style>
