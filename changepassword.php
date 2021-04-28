<?php
try{
	$config = parse_ini_file("databaseSettings.ini");
	$dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	session_start();
	$email = $_SESSION['user_email'];
	$default = false;
	foreach ($dbh->query("SELECT password FROM Student where email = '$email'") as $row){
		if($row[0] == sha1("dummyPassword")){
			$default = true;
		}
	}
	
	
}catch(PDOException $e){
	print "Error! " .$e -> getMessage()."<br/>";
	die();
}
	

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.82.0">
    <title>Landing Page</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">

    

    <!-- Bootstrap core CSS -->
<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    
<main class="form-signin">
  <form method="post" action="changepassword.php">
	<?php
		if($default){
			echo "<h1 class='h3 mb-3 fw-normal'>Please Set Initial Password</h1>";
			echo "<div class='form-floating'>";
			echo "<input type='password' class='form-control' name = 'passwordInput1' id='floatingPassword' placeholder='Password'>";
			echo "<label for='floatingPassword'>Password</label>";
			echo "</div>";
			echo "<div class='form-floating'>";
			echo "<input type='password' class='form-control' name = 'passwordInput2' id='floatingPassword' placeholder='Password'>";
			echo "<label for='floatingPassword'>Re-type Password</label>";
			echo "</div>";
		}else{
			echo "<h1 class='h3 mb-3 fw-normal'>Change Password</h1>";
			echo "<div class='form-floating'>";
			echo "<input type='password' class='form-control' name = 'passwordInput1' id='floatingPassword' placeholder='Password'>";
			echo "<label for='floatingPassword'>Password</label>";
			echo "</div>";
			echo "<div class='form-floating'>";
			echo "<input type='password' class='form-control' name = 'passwordInput2' id='floatingPassword' placeholder='Password'>";
			echo "<label for='floatingPassword'>Re-type Password</label>";
			echo "</div>";
		}
		
		if(isset($_POST['newPass'])){
			try{
				$pass1 = $_POST['passwordInput1'];
				$pass2 = $_POST['passwordInput2'];
				if($pass1 != $pass2){
					echo "Passwords do not Match";
				}else{
					$dbh->exec("UPDATE Student SET password = sha1('$pass1') WHERE email = '$email'");
					echo "Password Changed!";
				}
			}catch(PDOException $ex){
				echo "Error";
			}
		}
	
	?> 

    <button class="w-100 btn btn-lg btn-primary" name="newPass" type="submit">Sign in</button>
    
	
	<p class="mt-5 mb-3 text-muted">&copy; Tyler and Eric</p>
  </form>
</main>


    
  </body>
</html>

                            