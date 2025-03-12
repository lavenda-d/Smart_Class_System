<?php
// Include the database connection configurations
include('config.php');

// Initialize variables for form data and messages
$name = $email = $telephone = $username = $password = $fingerprintFileName = "";
$successMessage = $errorMessage = "";

// Check if form data is being sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form and sanitize it
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password

    // Handle fingerprint file upload
    if (isset($_FILES['fingerprint_template']) && $_FILES['fingerprint_template']['error'] === UPLOAD_ERR_OK) {
        $fingerprintFileName = basename($_FILES['fingerprint_template']['name']);
        $targetDirectory = "uploads/"; // Ensure this directory exists and is writable
        $targetFilePath = $targetDirectory . $fingerprintFileName;

        // Create the uploads directory if it doesn't exist
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['fingerprint_template']['tmp_name'], $targetFilePath)) {
            // Insert the user data into the database using PDO
            $sql = "INSERT INTO admins (name, email, telephone, fingerprint_template, username, password) VALUES (:name, :email, :telephone, :fingerprint_template, :username, :password)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':fingerprint_template', $fingerprintFileName);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            // Execute the statement
            if ($stmt->execute()) {
                $successMessage = "Account successfully created for $name.";
            } else {
                $errorMessage = "Error: Unable to create account. Debug: " . implode(" ", $stmt->errorInfo());
            }
        } else {
            $errorMessage = "Error uploading the fingerprint file.";
        }
    } else {
        $errorMessage = "Fingerprint file is not uploaded or there's an error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create New Account</title>
  <style>
    /* General Styles */
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #f1f6f8, #ffffff);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    header {
      background: #2e4051;
      color: white;
      padding: 20px;
      text-align: center;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .container {
      width: 500px;
      margin: 20px;
      padding: 30px;
      background: white;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      border-radius: 12px;
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      color: #1c3f62;
      text-align: center;
      margin-bottom: 20px;
      font-size: 24px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    label {
      font-weight: 600;
      color: #555;
      display: block;
      margin-bottom: 8px;
    }

    input, select {
      width: 95%;
      padding: 12px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    input:focus, select:focus {
      border-color: #0073e6;
      outline: none;
      box-shadow: 0 0 8px rgba(0, 115, 230, 0.2);
    }

    button {
      background: #194067;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      border-radius: 8px;
      text-align: center;
      transition: background 0.3s, transform 0.2s;
    }

    button:hover {
      background: #005bb5;
      transform: translateY(-2px);
    }

    button:active {
      transform: translateY(0);
    }

    .error {
      color: #ff4d4d;
      font-size: 14px;
      margin-top: 10px;
      text-align: center;
    }

    .success {
      color: #28a745;
      font-size: 14px;
      margin-top: 10px;
      text-align: center;
    }

    .file-input {
      position: relative;
      overflow: hidden;
      display: inline-block;
    }

    .file-input input[type="file"] {
      font-size: 16px;
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
      cursor: pointer;
    }

    .file-input label {
      display: block;
      padding: 12px;
      background: #062f58;
      color: white;
      border-radius: 8px;
      text-align: center;
      cursor: pointer;
      transition: background 0.3s;
    }

    .file-input label:hover {
      background: #005bb5;
    }

    .file-input span {
      font-size: 14px;
      color: #555;
      display: block;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Create a New Account</h1>
  </header>

  <div class="container">
    <h2>New Account Details</h2>
    <?php
    // Display success or error messages
    if (!empty($successMessage)) {
        echo "<div class='success'>$successMessage</div>";
    }
    if (!empty($errorMessage)) {
        echo "<div class='error'>$errorMessage</div>";
    }
    ?>
    <form action="" method="POST" id="create-account-form" enctype="multipart/form-data">
      <div>
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Enter full name" value="<?php echo htmlspecialchars($name); ?>" required>
      </div>
      <div>
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter email address" value="<?php echo htmlspecialchars($email); ?>" required>
      </div>
      <div>
        <label for="telephone">Telephone Number</label>
        <input type="tel" id="telephone" name="telephone" placeholder="Enter telephone number" value="<?php echo htmlspecialchars($telephone); ?>" required>
      </div>
      <div class="file-input">
        <label for="fingerprint">📁 Upload Fingerprint Template</label>
        <input type="file" id="fingerprint_template" name="fingerprint_template" accept=".txt,.json,.bin" required>
        <span>Accepted formats: .txt, .json, .bin</span>
      </div>
      <div>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($username); ?>" required>
      </div>
      <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>
      </div>
      <div class="error" id="error-message"></div>
      <button type="submit">Create Account</button>
    </form>
  </div>

  <script>
    const form = document.getElementById('create-account-form');
    const errorMessage = document.getElementById('error-message');

    form.addEventListener('submit', (e) => {
      // Gather form data
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const telephone = document.getElementById('telephone').value.trim();
      const fingerprint = document.getElementById('fingerprint_template').files[0];
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value.trim();

      // Basic validation
      if (!name || !email || !telephone || !username || !fingerprint || !password) {
        e.preventDefault(); // Prevent form submission
        errorMessage.textContent = "❌ Please fill out all fields.";
        return;
      }

       // Perform backend interaction (e.g., API request)
      // For demonstration, we display a success message
      alert(`✅ Account successfully created for ${name}.`);
      form.reset(); // Clear the form
      errorMessage.textContent = ""; // Clear error message
      
    });
  </script>
</body>
</html>