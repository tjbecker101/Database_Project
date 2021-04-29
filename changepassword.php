<?php
try{
	$config = parse_ini_file("databaseSettings.ini");
	$dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	session_start();
	$email = $_SESSION['user_email'];
	$type = $_SESSION['account_type'];
	$default = false;
	if($type == "Student"){
		foreach ($dbh->query("SELECT password FROM Student where email = '$email'") as $row){
			if($row[0] == sha1("dummyPassword")){
				$default = true;
			}
		}
	}else if($type == "Instructor"){
		foreach ($dbh->query("SELECT password FROM Instructor where email = '$email'") as $row){
			if($row[0] == sha1("dummyPassword")){
				$default = true;
			}
		}
	}else{
		header("Location: landingpage.php");
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
    <link href="changepass.css" rel="stylesheet">
  </head>
  
  <body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Change Password</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="logout.php">Logout</a>
    </li>
  </ul>
</header>


<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <?php
		  if($type == "Instructor"){
			echo "<li class='nav-item'>";
            echo "<a class='nav-link active' aria-current='page' href='teacherview.php'>";
			echo "<span data-feather='home'></span>";
			echo "Dashboard";
            echo "</a>";
			echo "</li>";
          
			echo "<li class='nav-item'>";
            echo "<a class='nav-link active' aria-current='page' href='teachercourseview.php'>";
            echo "<span data-feather='circle'></span>";
            echo "View Courses";
            echo "</a>";
			echo "</li>";
			
			echo "<li class='nav-item'>";
            echo "<a class='nav-link active' aria-current='page' href='teachersurveyview.php'>";
            echo "<span data-feather='circle'></span>";
            echo "View Surveys";
            echo "</a>";
			echo "</li>";
			
		  }else{
			  
			echo "<li class='nav-item'>";
            echo "<a class='nav-link active' aria-current='page' href='studentview.php'>";
			echo "<span data-feather='home'></span>";
			echo "Dashboard";
            echo "</a>";
			echo "</li>";
          
			echo "<li class='nav-item'>";
            echo "<a class='nav-link active' aria-current='page' href='studentsurveyview.php'>";
            echo "<span data-feather='circle'></span>";
            echo "View Surveys";
            echo "</a>";
			echo "</li>";
			
			echo "<li class='nav-item'>";
            echo "<a class='nav-link active' aria-current='page' href='registerview.php'>";
            echo "<span data-feather='circle'></span>";
            echo "Register";
            echo "</a>";
			echo "</li>"; 
		  }
		  ?>
        </ul>
      </div>
    </nav>


<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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
					if($type == "Instructor"){
						$stmt = $dbh -> prepare("UPDATE Instructor SET password = sha1(:pass) WHERE email = :email");
						$stmt -> bindParam(':pass',$pass1);
						$stmt -> bindParam(':email',$email);
						$stmt -> execute();
					}else{
						$stmt = $dbh -> prepare("UPDATE Student SET password = sha1(:pass) WHERE email = :email");
						$stmt -> bindParam(':pass',$pass1);
						$stmt -> bindParam(':email',$email);
						$stmt -> execute();
					}
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


          <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>

  </body>
</html>

                            