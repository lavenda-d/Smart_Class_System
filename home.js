// home.js

// Define your bypass key for the admin
const bypassKey = "admin123";

// Add event listener for form submission
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const userType = document.getElementById('userType').value;

    // Check if the user is trying to log in as an admin using the bypass key
    if (userType === 'admin' && password === bypassKey) {
        // Redirect directly to admin page
        window.location.href = 'admin.html';
    } else if (username && password) {
        // Make an AJAX request to the login PHP API
        fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&userType=${encodeURIComponent(userType)}`
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                // Redirect based on user type
                if (userType === 'student') {
                    window.location.href = 'student.html';
                } else if (userType === 'lecturer') {
                    window.location.href = 'lecturer.html';
                } else if (userType === 'admin') {
                    window.location.href = 'admin.html';
                }
            } else {
                alert(result.message); // Show error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error with your request.');
        });
    } else {
        alert('Please fill in all fields.');
    }
});

// Toggle Dropdown for Account Management
function toggleDropdown() {
    const dropdown = document.getElementById('accountDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
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