<?php

    require 'sql_connect.php';

    /**
     * If you are a PHP developer you know that to get the information from a POST request 
     * you need to define a variable with the $_POST  *method !! 
     * Well this method is obsolete because the JsonObjectRequest needs the parameters to be JSONObject 
     * so in the php file we need to decode a json array.
     * Source : http://androidprogamminghelpguide.blogspot.com/2014/09/how-to-use-volley-and-php-post.html
     */

    // decoding the json array
    $post = json_decode(file_get_contents("php://input"), true);
    $response = array();


    $sql = "TRUNCATE TABLE steps_table";
    if (mysqli_query($connection, $sql)) {
        array_push($response, array('Success truncate' => "1"));
    } else {
        array_push($response, array('Failed truncate' => "0"));
    }


    /**
     * When you open this php file, it will shows you a warning, it's normal
     * because the variable $post isn't initialize before the POST request
     */ 

    foreach ($post as $index => $value) {
        $step_count = $value['step_count'];
        $date_creation = $value['date_creation'];

        $sql = "INSERT INTO steps_table(step_count, date_creation)
            SELECT '$step_count', '$date_creation'
            WHERE NOT EXISTS (
                SELECT step_count, date_creation FROM steps_table
                WHERE step_count = '$step_count' AND date_creation = '$date_creation'
            )";
        $result_query = mysqli_query($connection, $sql);
    }

    if ($result_query) {
        $response_insert = array('Success insert' => "1");
        array_push($response, $response_insert);
        $response = json_encode($response);
        echo $response;
    } else {
        $response_insert = array('Failed insert' => "0");
        array_push($response, $response_insert);
        $response = json_encode($response);
        echo $response;
    }
?>