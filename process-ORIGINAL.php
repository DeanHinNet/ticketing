<?php
session_start();
require('new-connection.php');

if(isset($_POST['action']) && $_POST['action'] == 'login')
{
	$query="SELECT users.email, users.password FROM users WHERE users.email = '{$_POST['email']}'";
	$credentials=fetch_record($query);
	if(isset($credentials))
	{
		if($credentials['password']==$_POST['password'])
		{
			echo "success";
		}
		else
		{
			$_SESSION['error']['login']= "Password is not correct";
		}
	}
	else
	{
		$_SESSION['error']['login'] = "Login with those credentials do not exist.";
	}
}

if(isset($_POST['action']) && $_POST['action'] == 'register')
{
	//check if username exists
	$query="SELECT users.username FROM users WHERE users.username = '{$_POST['username']}'";
	$credentials = fetch_record($query);

	if(!isset($credentials))
	{
		$query = "INSERT INTO users (username, password) VALUES ('{$_POST['username']}', '{$_POST['password']}')";
		run_mysql_query($query);
		$_SESSION['success_message'] = "Congrats! You are now registered!";
	}
	else
	{
		$_SESSION['error']['register'] = "user already exists! Please login";
		header('location: door.php');
	}

}

?>
