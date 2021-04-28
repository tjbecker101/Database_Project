<?php
session_start();
$email = $_SESSION['user_email'];

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.82.0">
    <title>Dashboard Template · Bootstrap v5.0</title>

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
<div class="container py-4">
<header class="p-3 bg-dark text-white">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        <li><a href="studentview.php" class="nav-link px-2 text-white">Home</a></li>
      </ul>

      <a href="landingpage.php" class="text-end">
        <button type="button" class="btn btn-outline-light me-2">Logout</button>
      </a>
	  
    </div>
  </div>
</header>
<div class="container-fluid">
  <div class="row">

 


      <h2>Section title</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
			
            <tr>
              <th>Course ID</th>
              <th>Name</th>
              <th>Credits</th>
              <th></th>
              
            </tr>
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
	    </div>

    </main>
  </div>
</div>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
  </body>
</html>

       