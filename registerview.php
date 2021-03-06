<?php
session_start();
$email = $_SESSION['user_email'];
$type = $_SESSION['account_type'];

if($type == "Instructor"){
	header("Location: teacherview.php");
}else if(!$type == "Student"){
	header("Location: landingpage.php");
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
    <title>Register</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">

    

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
    <link href="dashboard.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Register</a>
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
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="studentview.php">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="studentsurveyview.php">
              <span data-feather="circle"></span>
              View Surveys
            </a>
          </li>
		  <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="changepassword.php">
              <span data-feather="key"></span>
              Change Password
            </a>
          </li>
          
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

      <h2>Courses</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
			
            <tr>
              <th>Course ID</th>
              <th>Name</th>
              <th>Credits</th>              
            </tr>
          </thead>
          </thead>
          <tbody>
			<?php

			try {
				$config = parse_ini_file("databaseSettings.ini");
				$dbh = new PDO($config['dsn'], $config['username'], $config['password']);

				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				foreach ($dbh->query("SELECT id, name, credits FROM Course") as $row){
					echo "<TR>";
					echo '<form method="post" action="registerview.php">';
					echo "<TD>".$row[0]."</TD>";
					echo "<TD>".$row[1]."</TD>";
					echo "<TD>".$row[2]."</TD>";
					echo "<input type='hidden' name='ID' value='$row[0]'>";
					echo '<TD> <input type="submit" name="view" value="Register"> </TD>';
					echo '</form>';
					echo "</TR>";
				}
				
			} catch (PDOException $e) {
			  print "Error!".$e->getMessage()."<br/>";
			  die();
			}
			
			if(isset($_POST['view'])){
				try{
					$id = $_POST['ID'];
					$dbh->exec("INSERT INTO Takes VALUES('$email', '$id')");
					echo "You have been Registered!";
				}catch(PDOException $ex){
					echo "Already Regitered for this Class";
				}
			}

			?>
            
          </tbody>
        </table>
      </div>

    </main>
  </div>
</div>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
  </body>
</html>

       