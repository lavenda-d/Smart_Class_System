<?php
// Include the database connection configurations
include('config.php');

// Initialize variables for form data and messages
$name = $email = $telephone = $fingerprintFileName = "";
$successMessage = $errorMessage = "";

// Check if form data is being sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form and sanitize it
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);

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
            // Insert the lecturer data into the database using PDO
            $sql = "INSERT INTO lecturers (name, email, telephone, fingerprint_template) VALUES (:name, :email, :telephone, :fingerprint_template)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':fingerprint_template', $fingerprintFileName);

            // Execute the statement
            if ($stmt->execute()) {
                $successMessage = "Lecturer account successfully created for $name.";
            } else {
                $errorMessage = "Error: Unable to create lecturer account. Debug: " . implode(" ", $stmt->errorInfo());
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
  <title>Create Lecturer Account</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }
    header {
      background-color: #4CAF50;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      background: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }
    h2 {
      color: #333;
      text-align: center;
      margin-bottom: 20px;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    label {
      font-weight: bold;
      color: #555;
    }
    input, select {
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 4px;
      text-align: center;
    }
    button:hover {
      background-color: #45a049;
    }
    .error {
      color: #f44336;
      font-size: 14px;
    }
    .success {
      color: #28a745;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Create Lecturer Account</h1>
  </header>

  <div class="container">
    <h2>Lecturer Details</h2>
    <?php
    // Display success or error messages
    if (!empty($successMessage)) {
        echo "<div class='success'>$successMessage</div>";
    }
    if (!empty($errorMessage)) {
        echo "<div class='error'>$errorMessage</div>";
    }
    ?>
    <form action="" method="POST" id="create-lecturer-form" enctype="multipart/form-data">
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
      <div>
        <label for="fingerprint">Fingerprint Template</label>
        <input type="file" id="fingerprint" name="fingerprint_template" accept=".txt,.json,.bin" required>
      </div>
      <div class="error" id="error-message"></div>
      <button type="submit">Create Lecturer Account</button>
    </form>
  </div>

  <script>
    const form = document.getElementById('create-lecturer-form');
    const errorMessage = document.getElementById('error-message');

    form.addEventListener('submit', (e) => {
      // Gather form data
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const telephone = document.getElementById('telephone').value.trim();
      const fingerprint = document.getElementById('fingerprint_template').files[0];

      // Basic validation
      if (!name || !email || !telephone || !fingerprint) {
        e.preventDefault(); // Prevent form submission
        errorMessage.textContent = "❌ Please fill out all fields.";
        return;
      }
    });
  </script>
</body>
</html>