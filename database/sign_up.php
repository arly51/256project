<?php
session_start();
include "conf.php";
error_reporting(0);

$u_name = mysqli_real_escape_string($conn , $_POST["name"]);
$u_email = mysqli_real_escape_string($conn , $_POST["email"]);
$u_pass = mysqli_real_escape_string($conn , $_POST["pass"]);
$u_re_pass = mysqli_real_escape_string($conn , $_POST["re_pass"]);

if(!empty($u_name) && !empty($u_email) && !empty($u_pass)){
    // Check if the email is valid
    if(filter_var($u_email, FILTER_VALIDATE_EMAIL)){
        // Check if the email already exists in the database
        $q = mysqli_query($conn, "SELECT * FROM `register` WHERE email = '{$u_email}'");
        if(mysqli_num_rows($q) > 0){
            echo "$u_email - This email already exists";
        } else {
            // Check if the passwords match
            if($u_pass != $u_re_pass){
                echo "Your passwords do not match";
            } else {
                // Proceed with the registration process
                $status = "signal_cellular_4_bar";
                $unique_id = rand(time(), 10000);
                
                // Insert the user data into the database
                $final = $conn->query("INSERT INTO `register`( `unique_id`, `Name`, `email`, `password`, `status`) VALUES ('$unique_id', '$u_name', '$u_email', '$u_pass', '$status')");
                
                if($final === true){
                    $q3 = $conn->query("SELECT * FROM `register` WHERE email = '$u_email'");
                    if(mysqli_num_rows($q3) > 0){
                        $row = mysqli_fetch_assoc($q3);
                        $_SESSION["unique_id"] = $row["unique_id"];
                        $_SESSION["u_id"] = $row["u_id"];
                        $_SESSION["role_id"] = $row["role_id"];
                        echo true;
                    }
                } else {
                    echo 'Query failed';
                }
            }
        }
    } else {
        echo "$u_email - This email is not valid";
    }
} else {
    echo "All input fields are required";
}

?>
