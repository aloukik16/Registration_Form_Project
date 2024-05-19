<?php
include 'connect.php';

if(isset($_POST['signUp'])){
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);
    
    // Get client's IP address
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if($result->num_rows > 0){
        echo "Email Address Already Exists!";
    }
    else{
        $insertQuery = "INSERT INTO users(firstName, lastName, email, password, ip_address)
                        VALUES ('$firstName', '$lastName', '$email', '$password', '$ipAddress')";
        if($conn->query($insertQuery) == TRUE){
            header("location: index.php");
        }
        else{
            echo "Error: " . $conn->error;
        }
    }
}

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];

        // Get client's IP address
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        // Update user's IP address in the database
        $updateQuery = "UPDATE users SET ip_address='$ipAddress' WHERE email='$email'";
        $conn->query($updateQuery);

        header("Location: homepage.php");
        exit();
    }
    else{
        echo "Not Found, Incorrect Email or Password";
    }
}
?>
