<?php
session_start();
require 'vendor/autoload.php';
require('new-connection.php');

function get_seats(){
	$names = array("name" => 'John', "age" => 16);
	echo json_encode($names);
}

//add customer to the database
$customerQuery = "INSERT INTO customers (first, last, email) VALUES ('{$_POST['first']}', '{$_POST['last']}', '{$_POST['email']}')";

$customer_id = run_mysql_query($customerQuery);

//double check to see if showdate and seat are not in database
for($i = 0; $i < sizeof($_POST['cart']); $i++){
	$ticketQuery="INSERT INTO transactions (purchaseDate, showDate, seatNumber, Customer_id) VALUES ('{$_POST['purchaseDate']}','{$_POST['showDate']}',{$_POST['cart'][$i]}, {$customer_id})";
	run_mysql_query($ticketQuery);
}

//add "ticket" to the database
// $customerQuery = "SELECT customers.email FROM customers WHERE customers.email = '{$_POST['email']}'";

// $credentials = fetch_record($customerQuery);



// if( !isset($credentials) ){
// 	$customerQuery = "INSERT INTO customers (first, last, email) VALUES ('{$_POST['first']}', '{$_POST['last']}', '{$_POST['email']}')";
// 	$customer_id = run_mysql_query($customerQuery);
// } else {
	
// 	$customer_id = $credentials;

// }


?>

