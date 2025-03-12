<?php
// Include the database connection configurations
include('config.php');

// Initialize variables for form data and messages
$name = $registrationNumber = $email = $telephone = $username = $password = $fingerprint = "";
$successMessage = $errorMessage = "";

// Check if form data is being sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form and sanitize it
    $name = trim($_POST['name']);
    $registrationNumber = trim($_POST['registration_number']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password
    $fingerprint = trim($_POST['fingerprint']);

    // Insert the student data into the database using PDO
    $sql = "INSERT INTO students (name, registration_number, email, telephone, fingerprint_template, username, password) 
            VALUES (:name, :registration_number, :email, :telephone, :fingerprint_template, :username, :password)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':registration_number', $registrationNumber);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':fingerprint_template', $fingerprint);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Execute the statement
    if ($stmt->execute()) {
        $successMessage = "Student account successfully created for $name.";
    } else {
        $errorMessage = "Error: Unable to create student account. Debug: " . implode(" ", $stmt->errorInfo());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f4f7f8, #cceeff);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            color: #0073e6;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        label {
            font-weight: 600;
            color: #555;
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="password"]:focus,
        textarea:focus {
            border-color: #0073e6;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 115, 230, 0.2);
        }

        textarea {
            resize: vertical; /* Allow vertical resizing */
            min-height: 100px;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #0073e6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: #005bb5;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }

        .status {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .status.connected {
            color: #00cc66;
        }

        .status.disconnected {
            color: #ff4d4d;
        }

        .success {
            color: #28a745;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: #f44336;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <script>
        // Connect to the WebSocket server
        const socket = new WebSocket("ws://localhost:8765");

        socket.onmessage = (event) => {
            const fingerprintField = document.getElementById("fingerprint");
            fingerprintField.value = event.data; // Update the fingerprint field with received data
            document.getElementById("status").textContent = "Fingerprint data received!";
            document.getElementById("status").classList.add("connected");
        };

        socket.onopen = () => {
            console.log("WebSocket connection established");
            document.getElementById("status").textContent = "Connected to WebSocket server.";
            document.getElementById("status").classList.add("connected");
        };

        socket.onclose = () => {
            console.log("WebSocket connection closed");
            document.getElementById("status").textContent = "Disconnected from WebSocket server.";
            document.getElementById("status").classList.remove("connected");
            document.getElementById("status").classList.add("disconnected");
        };
    </script>
</head>
<body>
    <form action="" method="POST" id="student-registration-form">
        <h1>Register a New Student</h1>
        
        <?php
        // Display success or error messages
        if (!empty($successMessage)) {
            echo "<div class='success'>$successMessage</div>";
        }
        if (!empty($errorMessage)) {
            echo "<div class='error'>$errorMessage</div>";
        }
        ?>

        <label for="name">Student Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter student name" value="<?php echo htmlspecialchars($name); ?>" required>
        
        <label for="registration_number">Registration Number:</label>
        <input type="text" id="registration_number" name="registration_number" placeholder="Enter registration number" value="<?php echo htmlspecialchars($registrationNumber); ?>" required>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Enter email address" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="telephone">Telephone Number:</label>
        <input type="tel" id="telephone" name="telephone" placeholder="Enter telephone number" value="<?php echo htmlspecialchars($telephone); ?>" required>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($username); ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>

        <label for="fingerprint">Fingerprint Template:</label>
        <textarea id="fingerprint" name="fingerprint" readonly placeholder="Fingerprint data will appear here..." required><?php echo htmlspecialchars($fingerprint); ?></textarea>

        <button type="submit">Submit</button>

        <div id="status" class="status">Connecting to WebSocket server...</div>
    </form>
</body>
</html>