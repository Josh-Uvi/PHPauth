<?php 
    function check_empty_fields($required_fields_array){
        //setup an array to store error messages
        $form_errors = array();

        //loop through the required fields array
        foreach($required_fields_array as $required_field) {
            if(!isset($_POST[$required_field]) || $_POST[$required_field] == NULL) {
                $form_errors[] = $required_field . " is a required field";
            }
        }

        return $form_errors;
    }
    
    function check_min_length($fields_to_check_length){
        //setup an array to store error messages
        $form_errors = array();

        //loop through the required fields 
        foreach($fields_to_check_length as $name_of_field => $minimum_length_required) {
            if(strlen(trim($_POST[$name_of_field])) < $minimum_length_required){
                $form_errors[] = $name_of_field . " is too short, must be {$minimum_length_required} characters long";
            }
        }

        return $form_errors;
    }

    function check_email($data){
        //setup an array to store error messages
        $form_errors = array();

        //set variable $key to check if email exist in array
        $key = 'email';

        //check array for email
        if(array_key_exists($key, $data)){

            //check if the email field has a value
            if($_POST[$key] != null){

                //remove all invalid characters from email field
                $key = filter_var($key, FILTER_SANITIZE_EMAIL);

                //check if input is a valid email address
                if(filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) === false) {
                    $form_errors[] = $key . " is not a valid email address";
                }
            }
        }

        return $form_errors;
    }

    function show_errors($form_errors_array){
        $errors = "<p><ul style='color: red;'>";

        //loop through error array and display all items in a list
        foreach($form_errors_array as $the_error) {
            $errors .= "<li> {$the_error} </li>";
        }
        $errors .= "</ul></p>";

        return $errors;
    }

    function displayMessage($message, $status = "fail"){
        if($status === "pass"){
            $display = "<p style='padding: 2px; border: 1px solid grey; color: green;'>{$message}</p>";
        }else{
            $display = "<p style='padding: 2px; border: 1px solid grey; color: red;'>{$message}</p>";
        }
        return $display;
    }

    function redirectTo($page){
        header("location: {$page}.php");
    }

    function checkDuplicateEntries($table, $column_name, $value, $db){
        try{
            $sqlQuery = "SELECT * FROM " .$table. " WHERE " .$column_name."=:$column_name";
            $statement =  $db->prepare($sqlQuery);
            $statement->execute(array(":$column_name" => $value));

            if($row = $statement->fetch()){
                return true;
            }
            return false;
        }catch(PDOException $ex){
            //handle exception here
            $result = displayMessage("Sorry an error occurred: " .$ex->getMessage());
        }
    }
?>