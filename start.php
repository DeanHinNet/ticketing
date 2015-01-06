<?php
session_start();
require('new-connection.php');


//get list of all the dates and seats that are not available (or currently taken)
//showDate, seatNumber

$query = "SELECT showDate, seatNumber FROM transactions";

$unavailable = fetch_all($query);

var_dump($unavailable);
?>