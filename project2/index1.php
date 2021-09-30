<?php
  $pdo = require("connect.php");
  if (isset($_POST) && isset($_POST["id"])) {
    $sql = "INSERT INTO scores (team1_id, team2_id, score1, score2) VALUES (?,?,?,?)";
    try {
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$_POST['id'][0], $_POST['id'][1], $_POST['score'][0], $_POST['score'][1]]);
      echo "<span style='color:red'>База данных обновлена!</span><br>";
      echo "<script>setTimeout(() => {window.location = window.location.href}, 3000);</script>";
      return;
    } catch (\Exception $e) {
      print_r($e);
    }
  }

  $teams = "<option disabled selected value> -- Выберите команду -- </option>";

  foreach ($pdo->query("SELECT * FROM teams;") as $row) {
    $teams .= "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
  }

?>
<form method="post">
  <select name="id[]">
    <?= $teams ?>
  </select> <br>
  <select name="id[]">
    <?= $teams ?>
  </select> <br>
  <label>Сколько голов забила первая команда: <input name="score[]" type="number" min=0 value="0"/></label> <br>
  <label>Сколько голов забила вторая команда: <input name="score[]" type="number" min=0 value="0"/></label> <br>
  <input type="submit"/>
</form>
<script>
  const selects = document.querySelectorAll("select");
  selects.forEach((select, index) => {
    ((index)=> {
      select.addEventListener('change', (e) => {
        const options2 = selects[1 - index].querySelectorAll('option');
        options2.forEach(option => {
          if (option.value == e.target.value) option.setAttribute("disabled", "disabled"); else option.removeAttribute("disabled");
        });
      });
    })(index);
  });
</script>