<?php 
    include_once("res/db.php");
    include_once("res/functions.php");
    
    //process the form when the button is clicked
    if(isset($_POST['signupBtn'])){

        //store form errors in an array 
        $form_errors = array();

        //validate form here
        $required_fields = array('email', 'username', 'password');

        //call the function to check empty field and merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
        
        //fields that require checking for minimum length
        $fields_to_check_length = array('username' => 4, 'password' => 6);

        //call the functions to check minimum required length and merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

        //email validation / merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_email($_POST));

        //setting variables to store data from the form
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        //checking for duplicates
        if (checkDuplicateEntries("users", "email", $email, $db)){
            $result = displayMessage("Email is already taken, please try another one");
        }
        else if (checkDuplicateEntries("users", "username", $username, $db)){
            $result = displayMessage("Username is already taken,  please try another one");
        }
        //check if form error array is empty, if empty then insert data to db
        else if(empty($form_errors)){

            //hashing the password to secure it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try{
                //creating sql query statement to insert data into the db
                $sql = "INSERT INTO users (username, email, password, join_date)
                        VALUES (:username, :email, :password, now())";

                //creating prepare statement to project the db from sql injection and attacks
                $statement = $db->prepare($sql);

                //executing the prepared statement
                $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $hashed_password));

                //check if data was inserted 
                if($statement->rowCount() == 1) {
                    $result = displayMessage("Signup Successful", "pass"); 
                }
            }catch(PDOException $ex) {
                $result = displayMessage("Sorry an error occurred: " .$ex->getMessage());
            }
        }
        else {
            if(count($form_errors) == 1) {
                $result = displayMessage("There was an error in the form<br>");
            } else {
                $result = displayMessage("There were " .count($form_errors). " errors in the form <br>");
            }
        }

    }

?>
    <?php include_once("inc/header.php"); ?>
    <div class="container-fluid bg">
      <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12"></div>
        <div class="col-md-4 col-sm-4 col-xs-12">

          <!-- creating the login form -->

          <form class="form-container" method="POST" action="">
          <?php if(isset($result)) echo $result; ?>
          <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
            <h2 class="text-center">Register Form</h2> 
            
            <div class="form-group">
                <label for="email"><span class="required">*</span>Email</label>
                <input type="text" class="form-control" name="email">
            </div>

            <div class="form-group">
                <label for="username"><span class="required">*</span>Username</label>
                <input type="text" class="form-control" name="username">
            </div>

            <div class="form-group">
                <label for="password"><span class="required">*</span>Password</label>
                <input type="password" class="form-control" name="password">
            </div>

            <button type="submit" name="signupBtn" value="Signup" class="btn btn-success btn-block">Submit</button><br>

            <!-- redirect to index page -->
            <a href="index.php" class="btn btn-default" role="button" aria-pressed="true" style="color:white;">Back</a>
            
          </form>

        </div>
        <div class="col-md-4 col-sm-4 col-xs-12"></div>
      
      </div>
    </div>

          