<?php
session_start();
require('new-connection.php');

$takenQuery = "SELECT seatNumber FROM transactions WHERE showDate = '{$_POST['date']}'";

$unavailable = fetch_all($takenQuery);

echo json_encode($unavailable);

?>

