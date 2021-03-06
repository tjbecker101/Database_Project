<?php
session_start();
$email = $_SESSION['user_email'];
$type = $_SESSION['account_type'];

if($type == "Instructor"){
	header("Location: teacherview.php");
}else if(!$type == "Student"){
	header("Location: landingpage.php");
}

try {
	$config = parse_ini_file("databaseSettings.ini");
	$dbh = new PDO($config['dsn'], $config['username'], $config['password']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	foreach ($dbh->query("SELECT password FROM Student where email = '$email'") as $row){
		if($row[0] == sha1("dummyPassword")){
			header("Location: changepassword.php");
		}
	}
				
} catch (PDOException $e) {
  print "Error!".$e->getMessage()."<br/>";
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
    <title>Student View</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/jumbotron/">

    

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

    
  </head>
  <body>
    
<main>
  <div class="container py-4">
  <header class="p-3 bg-dark text-white">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        <li><a href="#" class="nav-link px-2 text-white">Home</a></li>
      </ul>

      <a href="logout.php" class="text-end">
        <button type="button" class="btn btn-outline-light me-2">Logout</button>
      </a>
	  
    </div>
  </div>
</header>

    <div class="p-5 mb-4 bg-light rounded-3">
      <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Register for Courses</h1>
        <p class="col-md-8 fs-4">Register for the courses you wish to take in the upcoming semester</p>
        <button class="btn btn-primary btn-lg" onclick="location.href = 'registerview.php';" type="button">Register</button>
      </div>
    </div>

    <div class="row align-items-md-stretch">
      <div class="col-md-6">
        <div class="h-100 p-5 text-white bg-dark rounded-3">
          <h2>View Surveys</h2>
          <button class="btn btn-outline-light" onclick="location.href = 'studentsurveyview.php';" type="button">View</button>
        </div>
      </div>
      <div class="col-md-6">
        <div class="h-100 p-5 bg-light border rounded-3">
          <h2>Change Password</h2>
          <button class="btn btn-outline-secondary" onclick="location.href = 'changepassword.php';" type="button">Change Password</button>
        </div>
      </div>
    </div>

    <footer class="pt-3 mt-4 text-muted border-top">
      &copy; Tyler & Eric
    </footer>
  </div>
</main>


    
  </body>
</html>
