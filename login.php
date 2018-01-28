<?php
require_once "pdo.php";
require_once "util.php";


session_start();
unset($_SESSION['name']); //to log user out
unset($_SESSION['user_id']); //to log user out

//when button 'cancel' is clicked
if ( isset($_POST['cancel'] ) ) {
  // Redirect the browser to index.php
  header("Location: index.php");
  return;
}

//our password
$salt = 'XyZzy12*_';

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
  //if input is empty send message thatemail and password must be included
  if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
      $_SESSION['error'] = "User name and password are required";
      header("Location: login.php");
      return; 
   } 
  //if user typed something, validate it
  else {
    //using test_input function to prepare the data
    $email = test_input($_POST["email"]);
    //validating for email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = "Email must have an at-sign (@)";
      header("Location: login.php");
      return; 
    }

    //getting the password and user name from the database
    $check = hash('md5', $salt.$_POST['pass']);
    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row !== false) {
      $_SESSION['name'] = $row['name'];
      $_SESSION['user_id'] = $row['user_id'];
      header("Location: index.php");
      return;
    }else {
      $_SESSION['error'] = "Incorrect password";
      header("Location: login.php");
      return;
    }
  }
}

//check the input data
function test_input($data) {
  //The trim() function removes whitespace and other predefined characters from both sides of a string.
  $data = trim($data);
  //The stripslashes() function removes backslashes
  $data = stripslashes($data);
  //The htmlspecialchars() function converts some predefined characters to HTML entities.
  $data = htmlspecialchars($data);
  return $data;
}
?>

<!-- //Finished silently handling any incoming POST data
//Now it is time to produce output for this page  -->
<!DOCTYPE html>
<html>
  <head>
    <title>Iuliia Zemliana - Profiles and Positions Database CRUD</title>
    <?php require_once "head.php"; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  </head>
  <body>
    <div class="container">
      <h1>Please Log In</h1>
      <?php
      //printing the flash message
      flashMessages();
      ?>
      <form method="post">
        <p>Email: <input type="text" name="email" id="email"></p>
        <p>Password: <input type="text" name="pass" id="pass"></p>
        <input type="submit" onclick="return doValidate();" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
      </form>
      <p>
      For a password, view source and find a password
      in the HTML comments.
      <!-- Hint: 
      The account is umsi@umich.edu
      The password is the three character name of the 
      programming language used in this class (all lower case) 
      followed by 123. -->
      </p>
      <script>
      function doValidate() {
          console.log('Validating...');
          try {
              addr = document.getElementById('email').value;
              pw = document.getElementById('pass').value;
              console.log("Validating addr="+addr+" pw="+pw);
              if (addr == null || addr == "" || pw == null || pw == "") {
                  alert("Both fields must be filled out");
                  return false;
              }
              if ( addr.indexOf('@') == -1 ) {
                  alert("Invalid email address");
                  return false;
              }
              return true;
          } catch(e) {
              return false;
          }
          return false;
      }
      </script>
    </div>
  </body>
</html>


