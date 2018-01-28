<?php
	require_once "pdo.php";
	//including utilities
	require_once "util.php";
	session_start();



//Retrieve the profiles from the database
$stmt = $pdo->query('SELECT * FROM Profile');
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

?> 
<!DOCTYPE html>
<html>
	<head>
		<title>Iuliia Zemliana - Profiles and Positions Database CRUD</title>
		<?php require_once "head.php"; ?>
	</head>

	<body>
		<div class="container">
		<h1>Iuliia Zemliana's Resume Registry</h1>
			<?php
			flashMessages();

			//check if we are logged in!
			if( ! isset($_SESSION["user_id"]) ) { ?>
			<p><a href="login.php">Please log in</a></p>
			<?php 
				//if not logged in, still show the data in the table but without 'edit' and 'delete'
				$nRows = $pdo->query('select count(*) from Profile')->fetchColumn(); 
				if ($nRows == 0) {
					echo "<p>No rows found</p>";
				} else {
					//sneaking in some code, but its not a good idea 
					//displaying the table data
					echo('<table border="1">'."\n");
					echo "<tr><th>Name</th>";
					echo "<th>Headline</th></tr>";
					//getting data from database
					$stmt = $pdo->query("SELECT first_name, last_name, headline, profile_id FROM Profile");
					while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
					    echo "<tr><td>";
						echo( '<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a>');
						echo "</td><td>";
						echo(htmlentities($row['headline']));
						echo "</td></tr>\n";
					}
				}
			} else { ?>
			<?php
				// get data from DB
				// if there is at least one row
				// display data
				// else show message

				$nRows = $pdo->query('select count(*) from Profile')->fetchColumn(); 
				if ($nRows == 0) {
					echo "<p>No rows found</p>";
				} else {
					//sneaking in some code, but its not a good idea 
					//displaying the table data
					echo('<table border="1">'."\n");
					echo "<tr><th>Name</th>";
					echo "<th>Headline</th>";
					echo "<th>Action</th></tr>";
					//getting data from database
					$stmt = $pdo->query("SELECT first_name, last_name, headline, profile_id FROM Profile");
					while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
					    echo "<tr><td>";
						echo( '<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a>');
						echo "</td><td>";
						echo(htmlentities($row['headline']));
						echo "</td><td>";
						//last column - action column
						//anchor text, we passing whichever column we are working with
						//get parameter we get from url
						echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
						echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a> ');
						echo("\n</form>\n");
						echo "</td></tr>\n";
					}
				}
			?>
			</table>
			<p style="margin-top:1em"><a href="add.php">Add New Entry</a></p>
			<p><a href="logout.php">Logout</a></p>
			<?php } ?>
		</div>
	</body>
</html>

