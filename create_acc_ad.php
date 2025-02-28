<?php
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
    // Get the data from the form and sanitize it
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $telephone = $conn->real_escape_string(trim($_POST['telephone']));
    $username = $conn->real_escape_string(trim($_POST['username']));
    
    // Hash the password before storing it in the database
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Use default algorithm (Bcrypt)
    
    // Handle fingerprint file upload
    if (isset($_FILES['fingerprint']) && $_FILES['fingerprint']['error'] === UPLOAD_ERR_OK) {
        $fingerprintFileName = basename($_FILES['fingerprint']['name']);
        $targetDirectory = "uploads/"; // Ensure this directory exists and is writable
        $targetFilePath = $targetDirectory . $fingerprintFileName;

        // Move the uploaded file
        if (move_uploaded_file($_FILES['fingerprint']['tmp_name'], $targetFilePath)) {
            // Insert the user data into the database
            $sql = "INSERT INTO admins (name, email, telephone, fingerprint, username, password) VALUES ('$name', '$email', '$telephone', '$fingerprintFileName', '$username', '$password')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Account successfully created for $name.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error uploading the fingerprint file.";
        }
    } else {
        echo "Fingerprint file is not uploaded or there's an error.";
    }
}

// Close the connection
$conn->close();
?>