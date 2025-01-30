<?php
function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function login($email, $password) {
    global $conn;
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['user_id'] = mysqli_fetch_assoc($result)['id'];
        return true;
    }
    return false;
}

function register($email, $password) {
    global $conn;
    $query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
    return mysqli_query($conn, $query);
}
?>
