<?php
session_start();
require 'new-connection.php';

$_SESSION['customer_id'] = 1;

$query = "SELECT * FROM customers";

$customer = fetch_record($query);


?>