<?php
try{
	$config = parse_ini_file("databaseSettings.ini");
	$dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	session_start();
	$valid = true;
	if(isset($_POST["emailInput"])){		
		foreach($dbh->query("SELECT email, password FROM Student") as $row){
			if($row[0] == $_POST['emailInput']){
				if($row[1] == sha1($_POST['passwordInput'])){
					$_SESSION['loggedin'] = true;
					$_SESSION['user_email'] = $_POST["emailInput"];
					$_SESSION['account_type'] = "Student";
					$valid = true;
					header("Location: studentview.php");
					exit();
					echo "works";
				}else{
					$valid = false;
				}
			}else{
				$valid = false;
			}
		}
		foreach($dbh->query("SELECT email, password FROM Instructor") as $row){
			if($row[0] == $_POST['emailInput']){
				if($row[1] == sha1($_POST['passwordInput'])){
					$_SESSION['loggedin'] = true;
					$_SESSION['user_email'] = $_POST["emailInput"];
					$_SESSION['account_type'] = "Instructor";
					$valid = TRUE;
					header("Location: teacherview.php");
					exit();
				}else{
					$valid = false;
				}
			}else{
				$valid = false;
			}
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
    <title>Sign in</title>

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
  <form method="post" action="landingpage.php">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="form-floating">
      <input type="email" class="form-control" name = "emailInput" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" name = "passwordInput" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>
	
	<?php $_SESSION["loggedin"] = false;
	if(!$valid){ ?>
	<div id="incorrect_info">
		<p>Incorrect Info</p>
	</div>
	<?php } ?>

    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-muted">&copy; Tyler and Eric</p>
  </form>
</main>


    
  </body>
</html>

                            