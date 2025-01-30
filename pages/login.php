<?php
session_start();
require("../includes/connect.php");
ini_set('display_errors', 0);  // Disable PHP error reporting
error_reporting(E_ALL);  // Report all errors

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Registration Handling
    if (isset($_POST['register'])) {
        if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
            $name = trim($_POST["name"]);
            $email = trim($_POST["email"]);
            $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

            // Check if email already exists
            $check_email_stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
            $check_email_stmt->bind_param("s", $email);
            $check_email_stmt->execute();
            $check_email_stmt->store_result();

            if ($check_email_stmt->num_rows > 0) {
                echo json_encode(["status" => "error", "message" => "Email already registered"]);
            } else {
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $password);

                if ($stmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Registered successfully"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error during registration"]);
                }
                $stmt->close();
            }
            $check_email_stmt->close();
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "Please fill all fields"]);
            exit;
        }
    }

    // Login Handling
    if (isset($_POST['login'])) {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $email = trim($_POST["email"]);
            $password = trim($_POST['password']);

            $stmt = $conn->prepare("SELECT email, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['email'] = $email;
                    echo json_encode(["status" => "success", "message" => "Login successful"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Invalid password"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "User not found"]);
            }

            $stmt->close();
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "Please fill all fields"]);
            exit;
        }
    }
}

// Logout Handling
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

