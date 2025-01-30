<?php
session_start();
include 'includes/connect.php';

$name = $email = $message = "";
$success_msg = $error_msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));

    // Basic validation
    if (!empty($name) && !empty($email) && !empty($message)) {
        $query = "INSERT INTO contact_form (name, email, message) VALUES ('$name', '$email', '$message')";
        if (mysqli_query($conn, $query)) {
            $success_msg = "Your message has been sent successfully!";
            $name = $email = $message = ""; // Clear input fields
        } else {
            $error_msg = "Failed to send message. Please try again.";
        }
    } else {
        $error_msg = "All fields are required.";
    }
}
?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <main class="main-content">
        <section class="contact-form-section">
            <h2>Contact Us</h2>
            <?php if (!empty($success_msg)) { echo '<p class="success-msg">' . $success_msg . '</p>'; } ?>
            <?php if (!empty($error_msg)) { echo '<p class="error-msg">' . $error_msg . '</p>'; } ?>

            <form action="contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="form-group">
                    <label for="issue">Issue</label>
                    <input type="text" id="issue" name="issue" value="<?= htmlspecialchars($issue) ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($description) ?></textarea>
                </div>

                <button type="submit" class="btn">Submit</button>
            </form>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/script.js"></script>
</body>
</html> -->
