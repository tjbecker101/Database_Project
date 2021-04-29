<?php
	session_start();
	$email = $_SESSION['user_email'];
	$c_id = $_SESSION['course_id'];
	
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
            <a class="nav-link active" aria-current="page" href="teachersurveyview.php">
              <span data-feather="circle"></span>
              Back To Surveys
            </a>
          </li>
		  
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">


      <h2>Questions</h2>
      
			<?php

			try {
				$config = parse_ini_file("databaseSettings.ini");
				$dbh = new PDO($config['dsn'], $config['username'], $config['password']);

				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				foreach ($dbh->query("select question, question_ID, option_A, option_B, option_C, option_D, option_E
										from Questions 
										where survey_ID='".$c_id."'") as $q){
					
					echo '<div class="table-responsive">';
						echo '<table style="width=100%" class="table table-striped table-sm">';
						
							echo '<colgroup>';
							   echo '<col span="1" style="width: 70%;">';
							   echo '<col span="1" style="width: 15%;">';
							   echo '<col span="1" style="width: 15%;">';
							echo '</colgroup>';
						
							echo '<tbody>';
							
							//question
							echo "<THEAD>";
							echo "<TR>";
							echo "<TD><b>".$q[0]."</TD>";
							echo "<TD></TD>";
							echo "<TD></TD>";
							echo "</TR>";
							echo "</THEAD>";
							
							//response rate
							foreach ($dbh->query("select count(email)
													from (Questions natural join Answers) 
													where survey_ID='".$c_id."' and question_ID='".$q[1]."'") as $responses){}
							foreach ($dbh->query("select count(email)
													from Takes 
													where ID='".$c_id."'") as $totStudent){}
							$resRate=($responses[0]/$totStudent[0])*100;
									
							echo "<TR>";
							echo "<TD>Response Rate: $responses[0]/$totStudent[0] ($resRate%)</TD>";
							echo "<TD></TD>";
							echo "<TD></TD>";
							echo "</TR>";
							
							if ($q[2]!=null){
							
								//multiple choice header 
								echo "<TR>";
								echo "<TD><b>Response Option</TD>";
								echo "<TD><b>Frequency</TD>";
								echo "<TD><b>Percent</TD>";
								echo "</TR>";
								
								//count up multiple choice responses
								foreach ($dbh->query("select count(email)
													from Questions natural join Answers 
													where survey_ID='".$c_id."' 
													and question_ID='".$q[1]."' 
													and submissions='A'") as $resA){}
								foreach ($dbh->query("select count(email)
													from Questions natural join Answers 
													where survey_ID='".$c_id."' 
													and question_ID='".$q[1]."' 
													and submissions='B'") as $resB){}
								foreach ($dbh->query("select count(email)
													from Questions natural join Answers 
													where survey_ID='".$c_id."' 
													and question_ID='".$q[1]."' 
													and submissions='C'") as $resC){}
								foreach ($dbh->query("select count(email)
													from Questions natural join Answers 
													where survey_ID='".$c_id."' 
													and question_ID='".$q[1]."' 
													and submissions='D'") as $resD){}
								foreach ($dbh->query("select count(email)
													from Questions natural join Answers 
													where survey_ID='".$c_id."' 
													and question_ID='".$q[1]."' 
													and submissions='E'") as $resE){}
								$ratioA=($resA[0]/$responses[0])*100;
								$ratioB=($resB[0]/$responses[0])*100;
								$ratioC=($resC[0]/$responses[0])*100;
								$ratioD=($resD[0]/$responses[0])*100;
								$ratioE=($resE[0]/$responses[0])*100;

								//show multiple choice responses
								echo "<TR>";
								echo "<TD>".$q[2]."</TD>";
								echo "<TD>".$resA[0]."</TD>";
								echo "<TD>".$ratioA."%</TD>";
								echo "</TR>";
								echo "<TR>";
								echo "<TD>".$q[3]."</TD>";
								echo "<TD>".$resB[0]."</TD>";
								echo "<TD>".$ratioB."%</TD>";
								echo "</TR>";
								echo "<TR>";
								echo "<TD>".$q[4]."</TD>";
								echo "<TD>".$resC[0]."</TD>";
								echo "<TD>".$ratioC."%</TD>";
								echo "</TR>";
								echo "<TR>";
								echo "<TD>".$q[5]."</TD>";
								echo "<TD>".$resD[0]."</TD>";
								echo "<TD>".$ratioD."%</TD>";
								echo "</TR>";
								echo "<TR>";
								echo "<TD>".$q[6]."</TD>";
								echo "<TD>".$resE[0]."</TD>";
								echo "<TD>".$ratioE."%</TD>";
								echo "</TR>";
							
							} else {
								
								foreach ($dbh->query("select submissions
													from Questions natural join Answers 
													where survey_ID='".$c_id."' 
													and question_ID='".$q[1]."'") as $shrtAns){
									echo "<TR>";
									echo "<TD>".$shrtAns[0]."</TD>";
									echo "<TD></TD>";
									echo "<TD></TD>";
									echo "</TR>";
								}
								
								
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

       

       