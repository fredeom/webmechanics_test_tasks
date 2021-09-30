<?php
  $pdo = require("connect.php");

  $teams = [];
  $teamOptions = '';

  if (isset($_POST['id'])) {
    $team_id = $_POST['id'];
  } else {
    $team_id = -1;
  }

  foreach ($pdo->query("SELECT * FROM teams;") as $row) {
    $teams[$row['id']] = $row['name'];
    $teamOptions .= '<option value=' . $row['id'] . ($team_id > 0 && $team_id === $row['id'] ? " selected" : "") . '>' . $row['name'] . '</option>';
  }

  $table = [];
  $sql = "SELECT * FROM scores WHERE team1_id=? OR team2_id=?";
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$team_id, $team_id]);
    while($row = $stmt->fetch()) {
      $id1 = $row['team1_id'];
      $id2 = $row['team2_id'];
      $s1 = $row['score1'];
      $s2 = $row['score2'];
      if ($id2 === $team_id) {
        $tmp = $s1;
        $s1 = $s2;
        $s2 = $tmp;
        $tmp = $id1;
        $id1 = $id2;
        $id2 = $tmp;
      }
      $opName = $teams[$id2];
      if (!isset($table[$opName])) {
        $table[$opName] = [0, 0];
      }
      $table[$opName] = [$table[$opName][0] + $s1, $table[$opName][1] + $s2];
    }
    $ourgoals = 0;
    $theirgoals = 0;
    foreach ($table as $k => $v) {
      $ourgoals += $v[0];
      $theirgoals += $v[1];
    }
  } catch (\Exception $e) {
    print_r($e);
    die;
  }
?>
<form method="post">
  <select name="id">
    <option disabled selected value> -- Выберите команду -- </option>
    <?= $teamOptions ?>
  </select>
</form>
<table border=1>
  <thead><tr><th>Противник</th><th>Голов противнику</th><th>Голов от противника</th></tr></thead>
  <tbody>
    <?php foreach ($table as $k => $v): ?>
      <tr>
        <td><?= $k ?></td><td><?= $v[0] ?></td><td><?= $v[1] ?></td>
      </tr>
    <?php endforeach; ?>
    <tr><th>Итого:</th><td><?= $ourgoals ?></td><td><?= $theirgoals ?></td></tr>
  </tbody>
</table>

<script>
  const select = document.querySelector('select');
  select.addEventListener('change', () => {
    document.forms[0].submit();
  })
</script>