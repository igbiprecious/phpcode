<?php

if( isset($_POST['login']) ){

	$username = $_POST['username'];
	$password = $_POST['password'];


	function sanitizeString2($string){

		$string = stripslashes($string);
		$string = htmlspecialchars($string);
		$string = trim($string);
		return $string;
	}


	$db = new mysqli('localhost', 'root', '');

		if($db->connect_error){

		die("Could not connect to server").$db->connect_error();
		}

		$select = $db->select_db('dbfile');

		if ($select == 0){

		die("Could not select database from server").$db->connect_error();
		}



	function sanitizeSQL2($db, $string){

		$string = $db->real_escape_string($string);
		$string = sanitizeString2($string);
		return $string; 
	}

	$username = sanitizeSQL2($db, $username);
	$password = sanitizeSQL2($db, $password);

	
	$sql = "SELECT * FROM fortune WHERE username = '$username'";	
	$query = $db->query($sql);
	$count = mysqli_num_rows($query);
		if(!$count){
		
		echo "<script>alert(\"No records found\")</script>";
		exit();
		}else{
			while ($rows = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$hashpwd = $rows['password'];
		$dehash = password_verify($password, $hashpwd);
		
			}

		if($decrypt == 0){
		header("Location:join.php?error=wrongpasskey");
		exit();

		}else{

		$sql = "SELECT * FROM fortune WHERE username='$username' AND password='$hashpwd'";
		$result = $db->query($sql);
		if(!$retval = $result->fetch_assoc()){
			header("Location:join.php?error=wrongdetails");
			exit();			
		}else{
			header("Location:home.php");
			$db->close();
			exit();
		}
		}

	}

	
		
}



?>
