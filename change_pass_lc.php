<?php
session_start(); // Start the session to access session variables

// Include the database connection configurations
require 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is being sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the entered data from the form
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    // Assuming the user is logged in and their ID is stored in a session variable
    $userId = $_SESSION['user_id']; // Replace with your session management logic

    // Fetch the user's current password hash from the database
    $sql = "SELECT password FROM admins WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verify the current password
        if (password_verify($currentPassword, $row['password'])) {
            // Update with the new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql = "UPDATE admins SET password = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('si', $newPasswordHash, $userId);

            if ($updateStmt->execute()) {
                echo "Password successfully changed.";
            } else {
                echo "Error updating password: " . $conn->error;
            }

            $updateStmt->close();
        } else {
            echo "Current password is incorrect.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}

// Close the connection
$conn->close();
?>