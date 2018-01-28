<?php 
	//showing flash messages
	function flashMessages() {
		if ( isset($_SESSION['error']) ) {
			echo('<p style="color:red">'.htmlentities( $_SESSION["error"] )."</p>\n");
			unset($_SESSION["error"]);
		}
		if ( isset($_SESSION['success']) ) {
			echo('<p style="color:green">'.htmlentities( $_SESSION["success"] )."</p>\n");
			unset($_SESSION["success"]);
		}
	}

	//validating the profile
	function validateProfile() {
		if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
      		return "All fields are required";
   		} 

   		if( strpos($_POST['email'], '@') === false ) {
   			return "Email address must contain @";
   		}
   		return true;
	}

	//Look through the POST data and return true or error msg
	function validatePos() {
		for ($i=1; $i <= 9; $i++) { 
			if(! isset($_POST['year'.$i]) ) continue;
			if(! isset($_POST['desc'.$i]) ) continue;
			$year = $_POST['year'.$i];
			$desc = $_POST['desc'.$i];
			if ( strlen($year) == 0 || strlen($desc) ==  0) {
				return "All fields are required";
			}

			if(! is_numeric($year) ) {
				return "Position year must be numeric";
			}
		}
		return true;
	}

	/* NOTE: What fetchAll() does ...
	$profiles = array();
	while( $row = $stmt = $stmt->fetch(PDO::FETCH_ASSOC) ) {
		$profiles[] = $row;
	} 
	*/
	function loadPos($pdo, $profile_id) {
		$stmt = $pdo->prepare('SELECT * FROM Position 
			WHERE profile_id = :prof ORDER BY rank');
		$stmt->execute(array( ':prof' => $profile_id));
		$positions = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$positions[] = $row;
		} 
		return $positions;
	}