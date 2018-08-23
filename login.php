<?php 
  include_once("res/session.php");
  include_once("res/db.php");
  include_once("res/functions.php");

  if(isset($_POST['loginBtn'])){
        //setup error array
        $form_errors = array();

        //form validation
        $required_fields = array('username', 'password');

        //checking if fields are empty
        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

        if(empty($form_errors)){
            //collect form data
            $user = $_POST['username'];
            $password = $_POST['password'];

            //check if user exist in db
            $sql = "SELECT * FROM users WHERE username = :username";
            $statement = $db->prepare($sql);
            $statement->execute(array(':username' => $user));

            while($row = $statement->fetch()){
                $id = $row['id'];
                $hashed_password = $row['password'];
                $username = $row['username'];

                if (password_verify($password, $hashed_password)){
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $username;
                    redirectTo('profile');
                }else{
                    $result = displayMessage("Invalid Username or Password, Please try again!");
                }
            }

        }else{
            if(count($form_errors) == 1){
                $result = displayMessage("There was an error in the form");
            } else {
                $result = displayMessage("There were ".count($form_errors)." errors in the form");
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

            <h2 class="text-center">Login Form</h2> 
            <?php if(isset($result)) echo $result; ?>
            <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
            <div class="form-group">
                <label for="username"><span class="required">*</span>Username</label>
                <input type="text" class="form-control" name="username">
            </div>

            <div class="form-group">
                <label for="password"><span class="required">*</span>Password</label>
                <input type="password" class="form-control" name="password">
            </div>

            <!-- <div class="checkbox">
              <label>
                <input type="checkbox"> Remember Me
              </label>
            </div> -->

            <button type="submit" name="loginBtn" class="btn btn-success btn-block">Submit</button><br>

            <a href="forgot_password.php" class="btn btn-default" role="button" aria-pressed="true" style="color:white; float:left;">Password Reset</a>

            <!-- redirect to index page -->
            <a href="index.php" class="btn btn-default" role="button" aria-pressed="true" style="color:white; float:right;">Back</a>
            
          </form>

        </div>
        <div class="col-md-4 col-sm-4 col-xs-12"></div>

      
      </div>
    </div>

          