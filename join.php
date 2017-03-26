<?php
date_default_timezone_set('Africa/Lagos');
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Sign up</title>
<link rel="stylesheet" type="text/css" href="join.css">


</head>
<body>

<?php 


if(isset($_POST['submit'])){

	$surname = $_POST['surname'];
	$lastname = $_POST['lastname'];	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$repassword = $_POST['repassword'];
	$email = $_POST['email'];
	$country = $_POST['country'];
	$dob = $_POST['year']."-".$_POST['month']."-".$_POST['day'];
	$sex = $_POST['sex'];
	$status = $_POST['status'];
	$biography = $_POST['biography'];
	$date = $_POST['date'];

			if($repassword != $password){

			header("Location:join.php?error=mismatch");
			exit();
			}

		
			$pattern = "/^(\w+)(\@)(\w+)(\.)(\w{3})$/i";
			$validate = preg_match($pattern, $email);
			if(!$validate){

				header("Location:join.php?error=invalid");
				exit();
			}



	function sanitizeString($string){

		$string = stripslashes($string);
		$string = htmlspecialchars($string);
		$string = trim($string);
		$string = strtolower($string);
		$string = ucwords($string);
		return $string;
	}

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


	function sanitizeSQL($db, $string){

		$string = $db->real_escape_string($string);
		$string = sanitizeString($string);
		return $string; 
	}

	function sanitizeSQL2($db, $string){

		$string = $db->real_escape_string($string);
		$string = sanitizeString2($string);
		return $string; 
	}

			$surname = sanitizeSQL($db, $surname);
			$lastname = sanitizeSQL($db, $lastname);
			$username = sanitizeSQL2($db, $username);
			$password = sanitizeSQL2($db, $password);
			$repassword = sanitizeSQL2($db, $repassword);
			$email = sanitizeSQL2($db, $email);
			$country = sanitizeSQL($db, $country);
			$dob = sanitizeSQL2($db, $dob);
			$sex = sanitizeSQL($db, $sex);
			$status = sanitizeSQL($db, $status);
			$biography = sanitizeSQL($db, $biography);


			


		$sql = "SELECT * FROM fortune WHERE username = '$username'";
		$query = $db->query($sql);

			if(mysqli_num_rows($query) > 0){

				header("Location:join.php?error=usernametaken");
				exit();
			}

		
		$sql = "SELECT * FROM fortune WHERE email = '$email'";
		$query = $db->query($sql);

			if(mysqli_num_rows($query) > 0){

				header("Location:join.php?error=emailtaken");
				exit();
			}else{

				
				$token = password_hash($password, PASSWORD_DEFAULT);

				$sql = "INSERT INTO fortune (surname, lastname, username, password, email, country, dob, sex, status, bio, date) VALUES ('$surname', '$lastname', '$username', '$token', '$email', '$country', '$dob', '$sex', '$status', '$biography', '$date')";

				$db->query($sql);


				$db->close();

				header("Location:home.php");
				exit();

				

			}
			







}else{

?>

<div class="login">
<form action='logining.php' method='POST' >

		

	Username:<input type='text' name='username' placeholder='Username' required='required'>
	Password:<input type='password' name='password' placeholder='*******' required='required'>
	<input class="submitlog" type='submit' name='login' value='Log in'>
	<br>
	<?php 

		 $url= "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		 if ( strpos($url, 'error=failed') ){

		 	echo "<i><font color='red'><center>Wrong Username and/or  Password combination...</center> </font></i>";
		 }
		
		 ?>

</form>
</div>






<br><br>

<form class='signup' action='join.php' method='POST' >
<table class='stable' border='0' cellpadding='2px' cellspacing='2px' >
	<?php echo "<input type='hidden' name='date'  value='".date('Y-m-d H:i:s')."' />"; ?>
	<tr><td >Surname:</td><td><input type='text' name='surname' placeholder='Surname' required='required'></td></tr>
	<tr><td>Lastname:</td><td><input type='text' name='lastname' placeholder='Lastname' required='required'></td></tr>
	<tr><td>Username:</td><td><input type='text' name='username' placeholder='Username' required='required'></td></tr>
	<?php 
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if (strpos($url, 'error=usernametaken') > 0 ){

			echo "<tr><td colspan='2'><i><font color='red'>Username already exist...</font></i></td></tr>";
		}
	?>
	<tr><td>Password:</td><td><input type='password' name='password' placeholder='********' required='required' /></td></tr>
	<tr><td><i>Re-type Password:</i></td><td><input type='password' name='repassword' placeholder='********' required='required' /></td></tr>
	<?php 
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		if (strpos($url, 'error=mismatch') > 0 ){

			echo "<tr><td colspan='2'><i><font color='red'>Password do not match</font></i></td></tr>";
		}
	?>
	<tr><td>E-mail:</td><td><input type='text' name='email' placeholder='abc@example.com' required='required'></td></tr>
	<?php 
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		if (strpos($url, 'error=invalid') > 0 ){

			echo "<tr><td colspan='2'><i><font color='red'>Invalid E-mail format provided...</font></i></td></tr>";
		}

		if (strpos($url, 'error=emailtaken') > 0 ){

			echo "<tr><td colspan='2'><i><font color='red'>E-mail has already been used...</font></i></td></tr>";
		}
	?>
	<tr><td>Country:</td><td><input type='text' name='country' placeholder='Country'></td></tr>

	<tr><td>Date of Birth:</td><td><select name='day'>
		<?php for ($i=1; $i <= 31 ; $i++) { 
			echo "<option value='$i'>$i<br></option>";

		} ?>
	</select> -
	<select name='month'>
		<?php for ($i=1; $i <= 12 ; $i++) { 
			echo "<option value='$i'>$i<br></option>";
			
		} ?>
	</select> -
	<select name='year'>
		<?php for ($i=1930; $i <= 2017 ; $i++) { 
			echo "<option value='$i'>$i<br></option>";
			
		} ?>
	</select>
		

	</td></tr>
	
	<tr><td>Sex:</td><td><select name='sex'>
		<option value='male'>Male</option>
		<option value='female'>Female</option>
	</select></td></tr>
	<tr><td>Status:</td><td><select name='status'>
		<option value='single'>Single</option>
		<option value='married'>Married</option>
		<option value='divorce'>Divorce</option>
	</select></td></tr>
	<tr><td>Bio:</td><td><textarea name="biography" placeholder="Enter Description here..."></textarea></td></tr>
	<tr><td><input class="submitsign" type='submit' name='submit' value='Sign Up'></td></tr>

	
	



</table>
</form>


<div class="content">
<h1>Fortune Keeper Mutual Community</h1>
<p>
Welcome to Fortune Keeper. A community that is made only for serious minded people.
<br>
It's a platform where you provide help and get 200% of your donation in 48hrs.<br> This is tested and trusted.


<h3> What are you waiting for?</h3>
<p>Hurry now and register with us, a platform that will change your life for good in short time.
</p>

<br>



</p>

</div>

<p class="footer"><marquee>
	THE POWER TO MAKE WEALTH IS IN YOUR HANDS ONLY IF YOU CAN GRAB THE OPPORTUNITY...
</marquee></p>


<?php

}

?>


</body>
</html>

