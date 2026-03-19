window.onload = function() {
    document.getElementById("loginForm").reset();
    document.getElementById("registerForm").reset();
}

//for tab
document.getElementById("defaultOpen").click();
function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

// Get all elements with class="tabcontent" and hide them
tabcontent = document.getElementsByClassName("tabcontent");
for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
}

// Get all elements with class="tablinks" and remove the class "active"
tablinks = document.getElementsByClassName("tablinks");
for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
}

// Show the current tab, and add an "active" class to the button that opened the tab
document.getElementById(cityName).style.display = "block";
evt.currentTarget.className += " active";
}

// JavaScript for password visibility toggle
function togglePasswordVisibility() {
    var passwordInput = document.getElementById("passwordInput");
    var eyeIcon = document.getElementById("eyeIcon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.src = "assets/images/eyeClose.png"; // Replace with the path to your close eye image
    } else {
        passwordInput.type = "password";
        eyeIcon.src = "assets/images/eyeOpen.png"; // Replace with the path to your open eye image
    }
}

// Function to check password strength and display a message
function checkPasswordStrength(password, message) {
    // Handle empty password case
    if (!password){
        message.textContent = "* Please enter a password.";
        return; // Exit the function early to avoid further checks
    }

    // Minimum length requirement
    if (password.length < 8) {
        message.textContent = "* Password should be at least 8 characters long.";
    }

    // Check for uppercase and lowercase letters
    else if (!/[a-z]/.test(password) || !/[A-Z]/.test(password)) {
        message.textContent = "* Password should include both uppercase and lowercase letters.";
    }

    // Check for numbers
    else if (!/\d/.test(password)) {
        message.textContent = "* Password should include at least one number.";
    }

    // Check for symbols
    else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        message.textContent = "* Password should include at least one symbol.";
    }

    // Strong password criteria met
    else {
        message.textContent = "Password meets recommended criteria!";
        message.style.color = "#00335C";
    }
}

//Function to confirm password and display a message
function confirmPassword(password, message, inputfield){
    // passwordMessage.textContent ="";
    // Handle empty password case
    if (!password) {
        message.textContent = "* Please enter both passwords.";
        return; // Exit the function early to avoid further checks
    }
    else if (password === inputfield.value) {
        message.textContent = "Match!";
        message.style.color = "#00335C";
    } else {
        message.textContent = "* Password does not match, type again.";
        message.style.color = "red";
    }
}

function focus1(password, message1, message2) {
    // Handle empty password case
    if (!password){
        message1.textContent = "* Please enter a password.";
    }
    message1.style.display = "block";
    message2.style.display = "none";
}

function focus2(password, message1, message2, inputfield) {
    // Handle empty password case
    if (!password || !inputfield.value) {
        message2.textContent = "* Please enter both passwords.";
    }
    message1.style.display = "none";
    message2.style.display = "block";
}

function hide(message1, message2) {
    message1.style.display = "none";
    message2.style.display = "none";
}

const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 13000); // 10 seconds in milliseconds
    }

function promptForEmail() {
  var email = prompt("Enter your email address:");
  if (email) {
    window.location.href = "http://localhost:8080/uos2024/private/forgot_password.php?email=" + email;
  }
}

function confirmDelete() {
    var userConfirmed = confirm("Are you sure you want to delete your account?");
    
    if (userConfirmed) {
        // User clicked "OK", navigate to delete_user.php
        window.location.href = "http://localhost:8080/uos2024/private/delete_user.php";
    }
}

//source code hidden for short cut keys
document.addEventListener("keydown", function (event){
    if (event.ctrlKey){
       event.preventDefault();
    }
    if(event.keyCode == 123){
       event.preventDefault();
    }
});

//source code hidden for right click
document.addEventListener('contextmenu', 
     event => event.preventDefault()
);

