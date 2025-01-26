// Clock Functionality
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;

}

// Update clock every second
setInterval(updateClock, 1000);
updateClock(); // Initial call to display clock immediately

// Sign In Class
document.getElementById('classSignInForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const classCode = document.getElementById('classCode').value;
    alert(`Signed into class with code: ${classCode}`);
});

// student.js

// Add an event listener for the logout button
document.getElementById('logoutButton').addEventListener('click', function() {
    // Clear any session data if necessary (not shown here)
    // Redirect the user to the home page
    window.location.href = 'home.html';
});

// Check the toggle state on page load
window.onload = function() {
    const onlineSigningEnabled = localStorage.getItem('onlineSigningEnabled') === 'true';
  
    // Disable sign-in functionality if toggle is off
    document.querySelector('#classSignInForm').style.display = onlineSigningEnabled ? 'block' : 'none';
    document.getElementById('classCode').disabled = !onlineSigningEnabled;
    document.getElementById('viewCatDates').disabled = !onlineSigningEnabled;
    document.getElementById('viewExamDates').disabled = !onlineSigningEnabled;
  
    const messageElement = document.getElementById('signinMessage');
    messageElement.textContent = onlineSigningEnabled ? '' : 'Online signing in is disabled.';
  }
  
  // Continue with your sign-in logic...
  document.getElementById('classSignInForm').addEventListener('submit', function(event) {
    if (localStorage.getItem('onlineSigningEnabled') !== 'true') {
      alert("Online signing in is disabled.");
      event.preventDefault();
      return;
    }
    
    // Add your original sign-in validation logic here...
    
// JavaScript to handle class sign-in
document.getElementById('classSignInForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    const enteredCode = document.getElementById('classCode').value.trim();
    const storedClassCode = localStorage.getItem('classCode'); // Retrieve the stored class code
    
    const messageElement = document.getElementById('signinMessage');

    console.log("Entered Code:", enteredCode);
    console.log("Stored Class Code:", storedClassCode);

    if (enteredCode === storedClassCode) {
        messageElement.textContent = "Successfully signed in to class!";
        messageElement.style.color = "green";
        
        // Optionally redirect to the class page
        // window.location.href = 'class_page.html';
    } else {
        messageElement.textContent = "Access Denied: Invalid Class Code.";
        messageElement.style.color = "red";
    }

    // Show the message
    messageElement.classList.remove('hidden');
});
  });