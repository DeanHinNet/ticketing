<?php

session_start();

$_SESSION['user_id'] = 1;

$db = new PDO('mysql:host=localhost;dbname=site', 'homestead', 'secret');

$user = $db->prepare("

			SELECT * FROM users
			WHERE id = :user_id

			");
$user->execute(['user_id' => $_SESSION['user_id']]);

$user = $user->fetchObject();
?>