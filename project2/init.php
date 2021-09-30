<?php

$pdo = require("connect.php");

try {
  $pdo->beginTransaction();
  if ($pdo->exec("CREATE TABLE teams(
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(40)
  );") === 0) {
    $sql = "INSERT INTO `teams` (name) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['Spartak']);
    $stmt->execute(['Zenit']);
    $stmt->execute(['Ural']);
    $stmt->execute(['Torpedo']);
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS scores(
      id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
      team1_id INT NOT NULL,
      team2_id INT NOT NULL,
      score1 INT NOT NULL,
      score2 INT NOT NULL
    );");

    $pdo->commit();
  } else {
    $pdo->rollBack();
    print_r("Таблицы уже созданы");
    return;
  }
} catch (\Exception $e) {
  $pdo->rollBack();
  print_r($e);
}