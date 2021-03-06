<?php
	session_start();
	$email = $_SESSION['user_email'];
	$type = $_SESSION['account_type'];

	if($type == "Student"){
		header("Location: studentview.php");
	}else if(!$type == "Instructor"){
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
    <title>Teacher View</title>

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
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Courses</a>
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
            <a class="nav-link active" aria-current="page" href="teacherview.php">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="teachersurveyview.php">
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
      
			<?php

			try {
				$config = parse_ini_file("databaseSettings.ini");
				$dbh = new PDO($config['dsn'], $config['username'], $config['password']);

				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				foreach ($dbh->query("SELECT id, name, credits, email 
										FROM Course NATURAL JOIN Teaches
										WHERE email='".$email."'") as $c){
											
					echo '<div class="table-responsive">';
					echo '<table style="width=100%" class="table table-striped table-sm">';
					  echo '<thead>';
						
						echo '<tr>';
						  echo '<th>Course ID</th>';
						  echo '<th>Name</th>';
						  echo '<th>Credits</th>';              
						echo '</tr>';
					  echo '</thead>';
					  
					  echo '<colgroup>';
					   echo '<col span="1" style="width: 33%;">';
					   echo '<col span="1" style="width: 33%;">';
					   echo '<col span="1" style="width: 34%;">';
					echo '</colgroup>';
					  
					  echo '<tbody>';
											
					echo "<THEAD>";
					echo "<TR>";
					echo "<TD>".$c[0]."</TD>";
					echo "<TD>".$c[1]."</TD>";
					echo "<TD>".$c[2]."</TD>";
					echo "</TR>";
					echo "</THEAD>";
					
					echo "<TR>";
					echo "<TD></TD>";
					echo "<TD><b>Student Emails</TD>";
					echo "<TD><b>Student Names</TD>";
					echo "</TR>";
					
					foreach ($dbh->query("select * from (Takes natural join Course) 
											join Student on Takes.email=Student.email
											where id='".$c[0]."'") as $s){
						echo "<TR>";
						echo "<TD></TD>";
						echo "<TD>".$s[5]."</TD>";
						echo "<TD>".$s[7]."</TD>";
						echo "</TR>";
					}
					
					echo '</tbody>';
				echo '</table>';
			  echo '</div>';
				}
				
			} catch (PDOException $e) {
			  print "Error!".$e->getMessage()."<br/>";
			  die();
			}

			?>
            
          
    </main>
  </div>
</div>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
  </body>
</html>

       