<?php
	// DELETE AN ENTRY -->
	// model code -->
	require_once "pdo.php"; //ask for pdo.php
	session_start(); //start the session

	//if the user is not logged in redirect back to index.pnp
	//with an error
	if ( ! isset($_SESSION['user_id']) ) {
    	die('Not logged in');
    	return;
	}


	//STEP 4
	if( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
		$sql = "DELETE FROM Profile WHERE profile_id = :zip";  //stuck the data in the placeholder
		$stmt = $pdo->prepare($sql); //prepare the statement
		$stmt->execute(array(':zip' => $_POST['profile_id']));
		$_SESSION['success'] = 'Record deleted';
		header( 'Location: index.php' ); //redirect
		return;
	}

	//STEP 1
	//Make sure that user_id is present 
	if ( ! isset($_GET['profile_id']) ) {
		$_SESSION['error'] = "Missing profile_id";
		header('Location: index.php');
		return;
	}

	//STEP 2
	//checking if the value that we got from Get request is correct
	$stmt = $pdo->prepare("SELECT first_name, last_name, profile_id FROM Profile WHERE profile_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['profile_id'] ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for profile_id';
		header('Location: index.php');
		return;
	}
?>

<!-- STEP 3 -->
<!-- view -->
<!DOCTYPE html>
<html>
	<head>
		<title>Iuliia Zemliana - Profiles and Positions Database CRUD</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	</head>

	<body>
		<div class="container">
			<h1>Deleting Profile</h1>
			<?php
				echo( "<span style='margin-top: 1em; display: inline-block;'>First name: " .htmlentities($row['first_name'])."</span><br>" );
				echo("<span style='margin-top: 0.5em; display: inline-block;'>Last name: ".htmlentities($row['last_name'])."</span>\n");
			?> </p>
			<!-- hidden variable here  -->
			<form method="post">
				<input type="hidden" name="profile_id" value="<?= $row ['profile_id'] ?>">
				<input type="submit" value="Delete" name="delete">
				<a href="index.php">Cancel</a>
			</form>
		</div>
	</body>
</html>