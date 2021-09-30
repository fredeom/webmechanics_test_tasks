<?php

$user = "admin";
$pass = "admin";

return new PDO('mysql:host=localhost;dbname=webmechanics;charset=utf8', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
