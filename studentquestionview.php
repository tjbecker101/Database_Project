<?php
session_start();
$email = $_SESSION['user_email'];
$c_id = $_SESSION['C_id'];
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
    <title>Survey Questions</title>

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
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Surveys</a>
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
            <a class="nav-link active" aria-current="page" href="studentsurveyview.php">
              <span data-feather="circle"></span>
              View Surveys
            </a>
          </li>
		  
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">


      <h2>Multiple Choice</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
			
            <tr>
              <th>Question</th>
              <th>Option 1</th>
			  <th>Option 2</th>
			  <th>Option 3</th>
			  <th>Option 4</th>
			  <th>Option 5</th>
			  <th></th>
            </tr>
          </thead>
          <tbody>
			<?php

			try {
				$config = parse_ini_file("databaseSettings.ini");
				$dbh = new PDO($config['dsn'], $config['username'], $config['password']);

				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$counter = 0;

				foreach ($dbh->query("select question, option_A, option_B, option_C, option_D, option_E, question_ID from Questions
					join Survey on survey_id=id where id = '$c_id' and option_A is not null;") as $q){
					echo "<form method='post'>";
						echo "<TR>";
						echo "<TD>".$q[0]."</TD>";
						
						echo "<TD>";
						echo "<input type = 'radio' value='A' name = 'rating_$counter' > $q[1]";
						echo "</TD>";
						
						echo "<TD>";
						echo "<input type = 'radio' value='B' name = 'rating_$counter' > $q[2]";
						echo "</TD>";
						
						echo "<TD>";
						echo "<input type = 'radio' value='C' name = 'rating_$counter' > $q[3]";
						echo "</TD>";
						
						echo "<TD>";
						echo "<input type = 'radio' value='D' name = 'rating_$counter' > $q[4]";
						echo "</TD>";
						
						echo "<TD>";
						echo "<input type = 'radio' value='E' name = 'rating_$counter' > $q[5]";
						echo "</TD>";
						echo "</TR>";
						
						echo "<input type='hidden' value='$q[6]' name='questionID_$counter'>";
						
						$counter++;
					}										
					
				
			} catch (PDOException $e) {
			  print "Error!".$e->getMessage()."<br/>";
			  die();
			}

	

			?>
            
          </tbody>
        </table>
      </div>
	  
	  <h2>Short Answer</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
			
            <tr>
              <th>Question</th>
              <th>Answer</th>
			  <th></th>
            </tr>
          </thead>
          <tbody>
			<?php

			try {
				$config = parse_ini_file("databaseSettings.ini");
				$dbh = new PDO($config['dsn'], $config['username'], $config['password']);

				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$counter2 = 0;
				
				foreach ($dbh->query("select question, question_ID from Questions
				join Survey on survey_id=id where id = '$c_id' and option_A is null;") as $q){
					echo "<TR>";
					echo "<TD>".$q[0]."</TD>";
					echo "<TD>";
					echo "<input type='text' name='rating2_$counter2' height='auto' maxlength='249'>";
					echo "</TD>";
					echo "</TR>";		
					echo "<input type='hidden' value='$q[1]' name='questionID2_$counter2'>";
					$counter2++;
				}
					echo '<TD> <input type="submit" name="submitAnswers" value="Submit"> </TD>';
					echo "</form>";
				
				
			
			} catch (PDOException $e) {
			  print "Error!".$e->getMessage()."<br/>";
			  die();
			}

	

			?>
            
          </tbody>
        </table>
		<?php
		if(isset($_POST['submitAnswers'])){
			try{
				$validAnswers = true;
				for($x = 0; $x < $counter; $x++){
					if(!isset($_POST["rating_$x"])){
						$validAnswers = false;
						break;
					}
				}
				if($validAnswers){
					for($x = 0; $x < $counter; $x++){
						$answer = $_POST["rating_$x"];
						$id = $_POST["questionID_$x"];
						$dbh->exec("INSERT INTO Answers VALUES('$email', '$id', '$answer')");
					}
					for($x = 0; $x < $counter2; $x++){
						$answer2 = $_POST["rating2_$x"];
						$id2 = $_POST["questionID2_$x"];
						$stmt = $dbh -> prepare("INSERT INTO Answers VALUES('$email', '$id2', :answer2)");
						$stmt -> bindParam(':answer2',$answer2);
						$stmt -> execute();
					}
					echo "Thanks for Completing the Survey!";
				}else{
					echo "Please Answer all Multiple Choice Questions";
				}
			}catch(PDOException $ex){
				echo "Already Have Taken This Survey!";
			}
		}
		?>
      </div>
	  
	  
	  
    </main>
  </div>
</div>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
  </body>
</html>

       

       