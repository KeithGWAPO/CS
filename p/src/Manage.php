<?php
session_start();
// Retrieve email from session variable
$email = $_SESSION["email"];

// Database connection
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "admin"; // Change this to your database name 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute query to fetch firstname and gender based on email
$stmt = $conn->prepare("SELECT firstname, lastname, gender, email, password FROM acc WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if query returned a result
if ($result->num_rows > 0) {
    // Fetch the firstname and gender
    $row = $result->fetch_assoc();
    $firstname = $row["firstname"];
    $lastname = $row["lastname"];
    $gender = $row["gender"];
    $email = $row["email"];
    $password = $row["password"];
} else {
    // If no result found, handle accordingly (e.g., display a default value)
    $firstname = "User"; // Default value
}

// Close connection
$conn->close();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Madelyn&display=swap');

        .admin-btn {
            background-color: white;
        }
        h1 {
            font-family: 'Madelyn', cursive;
            font-style: italic;
        }
        .welcome-text {
            color: black;
            font-size: 2rem;
            text-align: center;
        }

        .center-logo {
            width: 200px; /* Set the width of the logo */
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px; /* Add margin for spacing */
        }

        /* Modal styles */
        .modal {
            display: none;
            position: absolute;
            z-index: 1001;
            top: calc(100% + 10px); /* Adjust the distance from the top of the button */
            left: 50%; /* Center horizontally */
            transform: translateX(-50%);
            background-color:#d3d3d3;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 20px;
            text-align: center;
            width:300px;
        }

        /* Style for the email */
        #userEmail {
            border-bottom: 2px solid none;
        }
        #logoutBtn {
            border-top: 2px solid none; 
            color:red;
        }
        .border-line {
            border-bottom: 1px solid black; /* Add a bottom border */
            margin: 20px 0; /* Adjust margin for spacing */
        }

        /* Style for the greeting and logout button */
        #greeting,
        #logoutBtn {
            margin-top: 16px;
        }

        /* Style for the admin icon */
        #adminIcon {
            font-size: 40px;
            color: #333;
            margin-bottom: 20px;
        }
        .confirmation-modal {
            position: fixed;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 9999;
        }

        .confirmation-content {
            text-align: center;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .confirmation-modal button {
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        #confirmLogout {
            background-color: #4CAF50; /* Green */
            color: white;
        }

        #cancelLogout {
            background-color: #f44336; /* Red */
            color: white;
        }

        .left-navigation {
    position: absolute;
    top: calc(100% + 1px);
    left: 0;
    height: 488px;
    display: flex;
    flex-direction: column;
    align-items: center;
    border-right: 2px solid black; /* Add border on the right side */
    padding-right: 10px; /* Add padding to separate from the main content */
    background-color: black;
    width: 200px;
    transition: width 0.5s, opacity 0.5s; /* Add transition for smooth animation */
    opacity: 1; /* Initially visible */
}

.left-navigation.minimized {
    width: 0; /* Width when minimized */
    overflow: hidden; /* Hide the minimized navigation */
    opacity: 0; /* Make it invisible */
}

.main-content {
    transition: margin-left 0.5s; /* Remove width transition */
}

.main-content.expanded {
    margin-left: -202px; /* Adjusted margin to account for the left navigation */
}
.main-content.expanded h2{
    margin-left:110px;
}
.main-content.expanded .slideshow-container{
    padding-left:-95px;
    width:86.4%;
}

        .left-navigation-item {
            margin-bottom: -10px;
            font-size: 20px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-top:90px;
            margin-left:10px;
        }

        .left-navigation-item i {
            margin-right: 8px; /* Adjust margin for spacing */
        }
.greet{
    margin-left:500px;
}
.greet h2{
    font-size:25px;
    padding:2px;
}
.info{
    margin-left:-280px;
    font-size:25px;
}
.btn{
    border:5px solid black;
    background:black;
    color:white;
    width:2%;
    margin-left:200.2px;
    margin-top:-230px;
    padding:9px;
    font-size:20px;
    text-align:center;
    border-top-right-radius: 15px;
    border-bottom-right-radius: 15px;
}
#accountContainer {
    width: 80%;
    font-size: 20px;
    margin: 0 auto;
    text-align: center;
    margin-left: 256px;
    overflow-y: auto; /* Ito ay natitanggal upang mapanatili ang pagsasaayos sa oras ng pagdaragdag ng nilalaman */
    font-weight:600;
}


.form-group {
    margin-top:-5px;
    margin-bottom: 25px;
    text-align: left; /* Reset text alignment for form elements */
    height:55px;
}

.form-group label {
    display: block;
    margin-bottom: 0px;
    text-align: left; /* Align form labels to the left */
}

.form-group input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Ensure input width includes padding and border */
}
/* Adjust the button's position to the center */
.float-right {
    display: inline-block; /* Change display to inline-block */
    margin-top: -1px; /* Add margin to separate from the form */
}




  </style>
</head>
<body>
    <nav class="bg-cover bg-center bg-no-repeat bg-opacity-80 border-b-4 border-gray-700 flex justify-between items-center p-4 relative" style="background-image: url('background.png');">
        <div class="flex items-center text-white mx-4">
            <img src="new logo.png" alt="logo" class="w-16 mr-2">
            <h1 class="text-2xl text-white">R.V.M</h1>
        </div>

        <!-- New Left Navigation -->
        <div class="left-navigation">
        <div class="left-navigation-item">
    <a href="Dashboard.php" class="text-white">
        <i class="fas fa-tachometer-alt"></i>
        Dashboard
    </a>
</div>
            <div class="left-navigation-item">
                <i class="fas fa-cash-register"></i>
                Cashiering
            </div>
            <div class="left-navigation-item">
                <i class="fas fa-box"></i>
                Product Lists
            </div>
            <div class="left-navigation-item">
                <i class="fas fa-cog"></i>
                Settings
            </div>
        </div>
        <!-- Administrator Button -->
        <div class="flex items-center relative mx-10"> <!-- Make the container relative -->
            <h2 id="adminBtn" class="flex items-center text-black font-semibold py-2 px-7 transition duration-300 rounded-full admin-btn">
                <i class="fas fa-user-tie mr-2"></i>
                Administrator
                <button id="toggleModalBtn" class="flex items-center justify-center bg-transparent border-none"><i id="caretIcon" class="fas fa-caret-down ml-2"></i></button>
            </h2>
            <!-- Administrator Modal -->
            <div id="adminModal" class="modal -ml-0">
                <!-- User's email -->
                <p id="userEmail" class="text-bold mb-2"><?php echo $email; ?></p>
                <div class="border-line"></div>  
                <!-- Greeting message -->
                <div class="bg-white pt-3 pb-3">
                    <i id="adminIcon" class="fas fa-user-tie"></i> <!-- Admin icon -->
                    <h2 id="greeting" class="text-2xl mb-4">Hello, <?php echo $firstname; ?></h2>
                    <!-- Manage account button -->
                    <button class="bg-blue-500 text-white text-bold py-2 px-4 rounded-full hover:bg-blue-600">Manage Account</button>
                </div>
                <div class="border-line"></div>
                <!-- Logout button -->
                <button id="logoutBtn" class="text-bold py-2 px-4 float-right">Log Out</button>
            </div>
        </div>
    </nav>

    <div class="main-content">
    <div id="accountContainer" class="border p-4" style="max-height: 465px; overflow-y: auto;">
    <h2 class="text-center -mb-5">Manage Your Account</h2>
    <form id="updateAccountForm">
        <div class="form-group">
            <label for="firstname">Firstname:</label>
            <input type="text" id="firstname" name="firstname"  placeholder="<?php echo $firstname; ?>">
        </div>
        <div class="form-group">
            <label for="lastname">Lastname:</label>
            <input type="text" id="lastname" name="lastname"  placeholder="<?php echo $lastname; ?>">
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <input type="text" id="gender" name="gender"  placeholder="<?php echo $gender; ?>">
        </div>
        <div class="form-group">
        <label for="email">Email:</label>
        <span class="text-blue-700" id="userEmail"><?php echo $email; ?></span>
    </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="<?php echo $password; ?>">
        </div>
        <!-- Submit button inside the bordered box -->
        <button type="submit" form="updateAccountForm" class="bg-blue-500 text-white text-bold py-2 px-4  rounded-full hover:bg-blue-600 float-right">Update</button>
    </form>
</div>  

  

<div class="btn" id="toggleNavigationBtn"><button><</button></div>
</div>

<script>
      const adminBtn = document.getElementById('adminBtn');
        const adminModal = document.getElementById('adminModal');
        const caretButton = document.querySelector('#adminBtn button'); // Select the button with the caret icon

        // Function to toggle modal visibility and caret icon
        function toggleModal() {
            if (adminModal.style.display === 'block') {
                adminModal.style.display = 'none';
                caretButton.querySelector('i').classList.remove('fa-caret-up'); // Remove caret-up class
                caretButton.querySelector('i').classList.add('fa-caret-down'); // Add caret-down class
            } else {
                adminModal.style.display = 'block';
                caretButton.querySelector('i').classList.remove('fa-caret-down'); // Remove caret-down class
                caretButton.querySelector('i').classList.add('fa-caret-up'); // Add caret-up class
            }
        }

        // Open or close modal when caret button is clicked
        caretButton.addEventListener('click', toggleModal);

        // Logout action
        logoutBtn.addEventListener('click', () => {
            // Create the confirmation dialog
            const confirmationDiv = document.createElement('div');
            confirmationDiv.classList.add('confirmation-modal');
            confirmationDiv.innerHTML = `
                <div class="confirmation-content">
                    <p>Are you sure you want to log out?</p>
                    <div class="btn-container">
                        <button id="confirmLogout">OK</button>
                        <button id="cancelLogout">Cancel</button>
                    </div>
                </div>
            `;

            // Append the confirmation dialog to the body
            document.body.appendChild(confirmationDiv);

            // Center the confirmation dialog
            confirmationDiv.style.top = `${(window.innerHeight - confirmationDiv.offsetHeight) / 2}px`;
            confirmationDiv.style.left = `${(window.innerWidth - confirmationDiv.offsetWidth) / 2}px`;

            // Event listener for confirm logout button
            const confirmLogoutBtn = document.getElementById('confirmLogout');
            confirmLogoutBtn.addEventListener('click', () => {
                // Redirect to Login.php after logout
                window.location.href = 'Login.php';
            });

            // Event listener for cancel logout button
            const cancelLogoutBtn = document.getElementById('cancelLogout');
            cancelLogoutBtn.addEventListener('click', () => {
                // Remove the confirmation dialog from the DOM
                confirmationDiv.remove();
            });
        });

       // JavaScript to toggle visibility of account management form
    const manageAccountBtn = document.getElementById('manageAccountBtn');
    const accountForm = document.getElementById('accountForm');

    // JavaScript to handle form submission asynchronously (you can add this)
    const updateAccountForm = document.getElementById('updateAccountForm');

    updateAccountForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission behavior

        // Fetch URL for handling form submission
        const url = 'updateAccount.php'; // Update with actual URL

        // Fetch input values
        const formData = new FormData(updateAccountForm);

        // Send POST request to update account
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Assuming server responds with JSON
        .then(data => {
            // Handle response (e.g., display success message)
            console.log(data);
        })
        .catch(error => {
            // Handle error (e.g., display error message)
            console.error('Error:', error);
        });
    });

        const toggleNavigationBtn = document.getElementById('toggleNavigationBtn');
const leftNavigation = document.querySelector('.left-navigation');
const mainContent = document.querySelector('.main-content');

function toggleNavigation() {
    leftNavigation.classList.toggle('minimized');
    mainContent.classList.toggle('expanded');
    slideshowContainer.classList.toggle('expanded'); // Add this line to toggle slideshow container width
}

toggleNavigationBtn.addEventListener('click', toggleNavigation);

    </script>
</body>
</html>
