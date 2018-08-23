<?php 
    //add db connection
    include_once("res/db.php");
    include_once("res/functions.php");

    //process the form when the button is clicked
    if (isset($_POST['passwordResetBtn'])){
        //setup an array to store error messages from the form
        $form_errors = array();

        //form validation
        $required_fields = array('email', 'new_password', 'confirm_password');

        //call the function to check empty field and merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

        //check fields that requires checking minimum length
        $fields_to_check_length = array('new_password' => 6, 'confirm_password' => 6);

        //function to check minimum required length and merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

        //email validation / merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_email($_POST));

        //check if error array is empty, if yes then process form data and insert record
        if (empty($form_errors)) {
            //collect form data and store in variables
            $email = $_POST['email'];
            $password1 = $_POST['new_password'];
            $password2 = $_POST['confirm_password'];

            //check if new password and confirm password match
            if($password1 != $password2){
                $result = displayMessage("New password and Confirm password doe not match");
            }else{
                try{
                    //using sql query to check if email address already exist in db
                    $sql = "SELECT email FROM users WHERE email =:email";

                    //using PDO prepare statement to sanitize query
                    $statement = $db->prepare($sql);

                    //execute sql query
                    $statement->execute(array(':email' => $email));

                    //check if record exist in db
                    if ($statement->rowCount() == 1) {
                        //hash password
                        $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

                        //sql query to update password
                        $sqlUpdate = "UPDATE users SET password =:password WHERE email=:email";

                        //using PDO prepare statement to sanitize sql query 
                        $statement = $db->prepare($sqlUpdate);

                        //execute sql query
                        $statement->execute(array(':password' => $hashed_password, ':email' => $email));

                        //display result 
                        $result = displayMessage("Password Reset Successful", "pass");
                    }else{
                        $result = displayMessage("Sorry the email address provided does not exist in our database, please try again");
                    }

                }catch(PDOException $ex){
                    $result = displayMessage("Sorry an error occurred: " .$ex->getMessage());
                }
            }
        } else{
            if (count($form_errors) == 1) {
                $result = displayMessage("There was an error in the form<br>");
            }else{
                $result = displayMessage("There were ".count($form_errors)." errors in the form <br>");
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
            <h2 class="text-center">Reset Password Form</h2> 
            
             <div class="form-group">
                <label for="email"><span class="required">*</span>Email</label>
                <input type="text" class="form-control" name="email">
            </div>

            <div class="form-group">
                <label for="new_password"><span class="required">*</span>New Password</label>
                <input type="password" class="form-control" name="new_password">
            </div>

            <div class="form-group">
                <label for="confirm_password"><span class="required">*</span>Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password">
            </div>

            <button type="submit" name="passwordResetBtn" value="Password Reset" class="btn btn-success btn-block">Submit</button><br>

            <!-- redirect to index page -->
            <a href="index.php" class="btn btn-default" role="button" aria-pressed="true" style="color:white;">Back</a>
            
          </form>

        </div>
        <div class="col-md-4 col-sm-4 col-xs-12"></div>
      
      </div>
    </div>