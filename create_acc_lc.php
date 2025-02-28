<?php
// Include the database connection configurations
require 'config.php';

// Create a new connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is being sent
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get and sanitize form data
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $telephone = $conn->real_escape_string(trim($_POST['telephone']));
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password
    
    // Handle fingerprint file upload
    if (isset($_FILES['fingerprint']) && $_FILES['fingerprint']['error'] === UPLOAD_ERR_OK) {
        $fingerprintFileName = basename($_FILES['fingerprint']['name']);
        $targetDirectory = "uploads/"; // Make sure this directory exists and is writable
        $targetFilePath = $targetDirectory . $fingerprintFileName;

        // Move the uploaded file
        if (move_uploaded_file($_FILES['fingerprint']['tmp_name'], $targetFilePath)) {
            // Insert the user data into the database
            $sql = "INSERT INTO admins (name, email, telephone, fingerprint, username, password) 
                    VALUES ('$name', '$email', '$telephone', '$fingerprintFileName', '$username', '$password')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Account successfully created for " . htmlspecialchars($name) . ".";
            } else {
                echo "Error: " . htmlspecialchars($sql) . "<br>" . htmlspecialchars($conn->error);
            }
        } else {
            echo "Error uploading the fingerprint file.";
        }
    } else {
        echo "Fingerprint file is not uploaded or there's an error: " . htmlspecialchars($_FILES['fingerprint']['error']);
    }
}

// Close the database connection
$conn->close();
?>