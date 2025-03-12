
<?php
// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set response header to JSON
//header('Content-Type: application/json');

// Database connection parameters
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "school";

try {
    // Establish PDO connection
    $pdo = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Send success response in JSON
    //echo json_encode(['success' => true, 'message' => 'Connected successfully']);
} catch (PDOException $e) {
    // Send error response in JSON
   // echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Initialize response
$response = [];

// Check if form data is being sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted data
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain-text password from the form
    $userType = $_POST['userType'];

    try {
        if ($userType === 'student') {
            // Check for student
            $sql = "SELECT * FROM students WHERE username = :username";
        } elseif ($userType === 'admin') {
            // Check for admin
            $sql = "SELECT * FROM admins WHERE username = :username";
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Invalid user type.';
            echo json_encode($response);
            exit;
        }

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the user record
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // If login is successful
                $response['status'] = 'success';
                if ($userType === 'student') {
                    $response['redirect'] = 'student.html';
                } else {
                    $response['redirect'] = 'admin.html';
                }
            } else {
                // If password verification fails
                $response['status'] = 'error';
                $response['message'] = 'Invalid username or password.';
            }
        } else {
            // If no matching user is found
            $response['status'] = 'error';
            $response['message'] = 'Invalid username or password.';
        }
    } catch (PDOException $e) {
        // Handle database errors
        $response['status'] = 'error';
        $response['message'] = 'Database error: ' . $e->getMessage();
    }

    // Return the response as JSON
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Landing Page</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li><a href="#project" onclick="closeSidebar()">Project Overview</a></li>
            <li><a href="#contact" onclick="closeSidebar()">Contact Us</a></li>
            <li><a href="#login" onclick="closeSidebar()">Login</a></li>
            <li><a href="#home_settings" onclick="closeSidebar()">Settings</a></li>
        </ul>
    </div>

    <div class="overlay" id="overlay"></div>
    
    <header>
        <div class="container">
            <h1>The Smart Classroom Attendance System</h1>
            <div class="hamburger" onclick="toggleSidebar()">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>
    </header>

    <main>
        <section id="project" class="section">
            <div class="container">
                <h2>Project Overview</h2>
                <p><strong>Smart Classroom Attendance System</strong><br>An advanced biometric-based solution designed to automate student attendance using fingerprint recognition. This system eliminates manual roll-calling, ensuring accuracy, efficiency, and security in classrooms.</p>
                <h3>How It Works</h3>
                <ul>
                    <li><strong>Fingerprint Authentication:</strong> Students scan their fingerprints using an AS608 Optical Fingerprint Sensor.</li>
                    <li><strong>Real-time Verification:</strong> Fingerprint data is transmitted to a local database for recognition.</li>
                    <li><strong>Attendance Logging:</strong> Upon successful identification, the student's name is displayed on an I2C LCD, and their attendance is recorded.</li>
                </ul>
                <h3>Feedback System</h3>
                <ul>
                    <li><strong>Success:</strong> Green LED blinks twice per second.</li>
                    <li><strong>Failure:</strong> Red LED blinks three times per second with a buzzer alert.</li>
                </ul>
                <h3>Key Features</h3>
                <ul>
                    <li>✅ Fast & Secure – Accurate fingerprint matching.</li>
                    <li>✅ User-Friendly – LCD provides real-time feedback.</li>
                    <li>✅ Offline Operation – No reliance on internet connectivity.</li>
                    <li>✅ Automation & Efficiency – Eliminates errors in attendance.</li>
                </ul>
                <h3>Applications</h3>
                <ul>
                    <li>✔ University & College Classrooms</li>
                    <li>✔ Corporate Training Sessions</li>
                    <li>✔ Secure Access Control</li>
                </ul>
                <p>This innovative solution enhances the traditional attendance system, making it more reliable and intelligent.</p>
                <div class="image-container">
                    <img src="project-image.jpg" alt="Project image" class="project-image" />
                </div>
            </div>
        </section>

        <section id="contact" class="section">
            <div class="container">
                <h2>Contact Information</h2>
                <p>For inquiries or more information, reach out to us:</p>
                <ul class="contact-list">
                    <li>Email: <a href="mailto:info@project.com">info@project.com</a></li>
                    <li>Phone: +254 717052939</li>
                </ul>
            </div>
        </section>

        <section id="founders" class="section">
            <div class="container">
                <h2>Meet Our Founders</h2>
                <p>Our team consists of passionate individuals dedicated to the project's success:</p>
                <ul class="founders-list">
                    <li><strong>VICTOR MUKUNGA</strong> - <a href="mailto:mukungavi344@gmail.com">mukungavi344@gmail.com</a>, <a href="https://github.com/QUASAR378" target="_blank">GitHub</a>, <a href="https://linkedin.com/in/VICTOR KAMAU" target="_blank">LinkedIn</a></li>
                    <li><strong>LAVENDA SHIPICHIRA</strong> - <a href="mailto:lavendadoris20@gmail.com">lavendadoris20@gmail.com</a>, <a href="https://github.com/lavenda-d" target="_blank">GitHub</a>, <a href="https://linkedin.com/in/LAVENDA SHIPICHIRA, MIEEE" target="_blank">LinkedIn</a></li>
                </ul>
            </div>
        </section>

        <section id="loginForm" class="section">
            <div class="container">
                <h2>Login</h2>
                <form action="homelogin.php" method="POST" class="form">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="userType">I am a:</label>
                    <select id="userType" name="userType" required>
                        <option value="student">Student</option>
                        <option value="lecturer">Lecturer</option>
                        <option value="admin">Admin</option>
                    </select>

                    <button type="submit">Login</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Our Project. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // home.js

// Define your bypass key for the admin
const bypassKey = "admin123";

// Add event listener for form submission
document.getElementById('loginForm').addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent default form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const userType = document.getElementById('userType').value;

    // Check if the user is trying to log in as an admin using the bypass key
    if (userType === 'admin' && password === bypassKey) {
        // Redirect directly to admin page
        window.location.href = 'admin.html';
        return; // Exit the function
    }

    if (username && password) {
        try {
            // Make an AJAX request to the login PHP API
            const response = await fetch('homelogin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&userType=${encodeURIComponent(userType)}`
            });

            const result = await response.json();

            if (result.status === 'success') {
                // Redirect based on user type
                window.location.href = result.redirect;
            } else {
                alert(result.message); // Show error message
            }
        } catch (error) {
            console.error('Error:', error);
            alert('There was an error with your request.');
        }
    } else {
        alert('Please fill in all fields.');
    }
});

// Toggle Dropdown for Account Management
function toggleDropdown() {
    const dropdown = document.getElementById("accountDropdown");
    const hamburger = document.querySelector(".hamburger");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    hamburger.classList.toggle("active");
}

// Close dropdown if clicked outside of it
window.onclick = function(event) {
    if (!event.target.matches('.hamburger')) {
        const dropdowns = document.getElementsByClassName("dropdown");
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.style.display === 'block') {
                openDropdown.style.display = 'none';
            }
        }
    }
};

function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.querySelector(".overlay");
    const hamburger = document.querySelector(".hamburger");

    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
    hamburger.classList.toggle("active");
}

function closeSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.querySelector(".overlay");
    const hamburger = document.querySelector(".hamburger");

    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    hamburger.classList.remove("active");
}

// Close sidebar when clicking outside
document.querySelector(".overlay").addEventListener("click", closeSidebar);
    </script>
</body>
</html>