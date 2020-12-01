<?php
    require 'sql_connect.php';

    $sql = "SELECT step_count, date_creation FROM steps_table";
    $result_data = [];

    if ($result_request = mysqli_query($connection, $sql)) {
        if ($result_request->num_rows > 0) {
            while ($row = $result_request->fetch_assoc()){
                // I don't know why but this value is a String so I cast it
                $row["step_count"] = (int)$row["step_count"];
                array_push($result_data, $row);
            }
        }
        //echo 'Success';
        //print json_encode($result_data);
    } else {
        echo 'Failed select steps entries';
    }
    
?>