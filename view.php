<?php
// EDIT EXISTING 
// you coming with get request and get auto_id 
	require_once "pdo.php";
	require_once "util.php";
	session_start(); //start the session


	//if the user requested cancel go back to index.php
	if( isset($_POST['cancel']) ) {
	  // Redirect the browser to index.php
	  header('Location: index.php');
	  return;
	}
	
	//STEP 1 
	//checking if row exists
	$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['profile_id'] ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for profile_id';
		header('Location: index.php');
		return;
	}

	//request data from Position table by profile_id
	$stmtPosition = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
	$stmtPosition->execute(array(":xyz" => $_GET['profile_id'] ));
	
    //view 
	//STEP 2
	//checking the user input
	$fn = htmlentities($row['first_name']);
	$ln = htmlentities($row['last_name']);
	$ema = htmlentities($row['email']);
	$he = htmlentities($row['headline']);
	$su = htmlentities($row['summary']);
	$profile_id = $row['profile_id'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Iuliia Zemliana - Profiles and Positions Database CRUD</title>
		<?php require_once "head.php"; ?>
	</head>

	<body>
		<div class="container">
			<h1>Profile information</h1>
			<?php flashMessages(); ?>
			<form method="post">
				<p>First Name: <?php echo($fn); ?> </p>
				<p>Last Name: <?php echo($ln); ?> </p>
				<p>Email: <?php echo($ema); ?> </p>
				<p>Headline: <?php echo($he); ?> </p>
				<p>Summary: <?php echo(htmlentities($profile['headline'])); ?> </p>
				<p>Position: </p>
				<ul>
					<?php
						while ( $rowPosition = $stmtPosition->fetch(PDO::FETCH_ASSOC) ) {
						    echo('<li>'.htmlentities($rowPosition['year'].': '.$rowPosition['description']).'</li>');
						}
					?>
				</ul>
				<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
				<p>
					<a href="index.php">Done</a>
				</p>
			</form>
		</div>
	</body>
</html>

