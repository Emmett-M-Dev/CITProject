<?php include 'includes/sessionconnection.php'; ?>
<?php 

// Check if the user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$user_id = $_SESSION['user_id'];
$start_time = $finish_time = $sleep_quality = "";
$session_started = $session_ended = false;

// Check if the user clicked the "Start" button
if (isset($_POST['start_session'])) {
    // Capture the start time of the sleep session
    $start_time = date("Y-m-d H:i:s");
    $session_started = true;
    
    // Insert a new sleep session record into the database
    $sql = "INSERT INTO sleep_sessions (user_id, start_time) VALUES ('$user_id', '$start_time')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: ". $sql. "<br>". $conn->error;
    }
}


// Check if the user clicked the "Submit Quality" button
if (isset($_POST['submit_quality'])) {
    // Capture the sleep quality input
    $sleep_quality = $_POST['sleep_quality'];

    // Update the existing sleep session record with sleep quality
    $sql = "UPDATE sleep_sessions SET sleep_quality = '$sleep_quality' WHERE user_id = '$user_id' AND finish_time IS NULL ORDER BY id DESC LIMIT 1";
    
    if (mysqli_query($conn, $sql)) {
        // Sleep quality updated successfully
    } else {
        echo "Error updating sleep quality: " . mysqli_error($conn);
    }
}

// Check if the user clicked the "Finish" button
if (isset($_POST['finish_session'])) {
    // Capture the finish time of the sleep session
    $finish_time = date("Y-m-d H:i:s");
    $session_ended = true;
    
    // Update the existing sleep session record with finish time
    $sql = "UPDATE sleep_sessions SET finish_time='$finish_time' WHERE user_id='$user_id' AND finish_time IS NULL ORDER BY id DESC LIMIT 1";
    
    if (mysqli_query($conn, $sql)) {
        // Sleep session finished successfully
    } else {
        echo "Error finishing sleep session: " . mysqli_error($conn);
    }
}




// Retrieve sleep session data for the current user
$sql = "SELECT * FROM sleep_sessions WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1";
// Execute the SQL query to fetch the most recent sleep session

// Display sleep session data
if ($result = mysqli_query($conn, $sql)) {
    if ($row = mysqli_fetch_assoc($result)) {
        $start_time = $row['start_time'];
        $finish_time = $row['finish_time'];
        $sleep_quality = $row['sleep_quality'];
    }
}

// Close the database connection
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleepio Dashboard Update</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

       

        @keyframes gradientBackground {
    0% {
      background-position: 0% 50%;
    }
    50% {
      background-position: 100% 50%;
    }
    100% {
      background-position: 0% 50%;
    }
  }

  
  /* Styles for the popup container */
.popup-container {
            display: none; /* Changed to block to make it visible */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: auto; /* Adjust the width as needed */
            max-width: 600px; /* Set a max-width for medium size */
            background: rgba( 255, 255, 255, 0.25 );
            box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
            backdrop-filter: blur( 8.5px );
            -webkit-backdrop-filter: blur( 8.5px );
            border-radius: 10px;
            border: 1px solid rgba( 255, 255, 255, 0.18 );
            padding: 2rem; /* Add padding */
            color: #FFF; /* Text color */
            text-align: center;
            z-index: 1000;
        }


/* Styles for the overlay background */
.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

/* Styles for the questionnaire form */
.questionnaire-form input[type="number"] {
            width: 80%; /* Full width of the popup */
            padding: 0.5rem;
            margin-bottom: 1rem; /* Space between inputs */
            border: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2); /* Add some shadow */
        }

        .questionnaire-form button {
            cursor: pointer;
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            border: none;
            background-color: #4C1D95; /* Tailwind purple-800 */
            transition: background-color 0.3s ease;
        }

        .questionnaire-form button:hover {
            background-color: #5B21B6; /* Tailwind purple-700 for hover effect */
        }

/* Style for the close button */
/* .close-button {
    cursor: pointer;
    margin-top: 10px;
    padding: 5px 10px;
    background-color: #ccc;
    border: none;
    border-radius: 5px;
} */

    </style>
</head>
<body class="bg-purple-800 font-roboto">
    <!-- Navigation Bar -->
    <?php  include 'includes/nav.php'; ?>


      <!-- Popup container -->
      <div id="popupContainer" class="popup-container">
        <h2>Questionnaire</h2>
        <form class="questionnaire-form">
            <!-- Add your questionnaire fields here -->
            <label for="question1">Sleep Quality:</label>
            <input type="number" class="text-black" name="sleep_quality" id="sleepQuality" min="1" max="5" required><br><br>

            <label for="question2">No. of Wake ups:</label>
            <input type="number" id="question2" name="question2" class="text-black" required><br><br>


            <!-- Close button -->
            <button id="closePopupButton"  name="submit_quality"  class="bg-blue-500 text-white rounded-full py-2 px-4 text-xl">Submit</button>
        </form>
    </div>



    <!-- Main Content -->
    <div class="flex flex-wrap">
        <!-- Left Panel -->
        <div class="w-full md:w-1/2 h-screen flex flex-col items-center justify-center bg-black text-white">
            <img src="includes/images/MoonIcon1.png" alt="Sleepio logo with moon and stars" class="mb-8 w-20">
            <h1 class="text-5xl font-bold mb-6">Sleepio.io</h1>
            <?php if ($session_started && !$session_ended): ?>
                <button id="openPopupButton" class="bg-red-500 text-white rounded-full py-2 px-4 text-xl">End</button>          
                <?php else: ?>
                <form method="post" action="">
                    <button type="submit" name="start_session" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mb-2">Begin Sleep</button>
                </form>
            <?php endif; ?>
            
        </div>

        <!-- Right Panel -->
        <div class="w-full md:w-1/2 h-screen flex flex-col items-center justify-center" style="
                background: linear-gradient(130deg, #1d34fd, #fcb045);
                background-size: 200% 200%;
                animation: gradientBackground 15s ease infinite;
                ">
            <div class="text-black text-center">
                <h2 class="text-9xl font-bold mb-6" style="color: #e6dfff;">11.35 pm</h2>
                <p class="text-2xl mb-8" style="color: #c1b3e9;">Begin your sleep cycle.<br>Analyse your results</p>
                <button class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg">See more</button>
            </div>
        </div>
    </div>

    <script>
      
         // Function to open the popup
         function openPopup() {
            document.getElementById('popupContainer').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        // Function to close the popup
        function closePopup() {
                // Get the form element
             var form = document.querySelector('.questionnaire-form');

            // Check if the form exists and has a submit button (adjust this condition as needed)
            if (form && form.querySelector('[type="submit"]')) {
                // Submit the form
                form.submit();
    
            // Add your session finishing logic here (e.g., updating session status)
            // ...

            // you can make an AJAX request to update the session status
            // Replace the following example with your actual session finishing logic
        fetch('finish_session.php', {
            method: 'POST',
            body: JSON.stringify({ user_id: <?php echo $user_id; ?> }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response data as needed
            console.log(data);

            // Close the popup
            document.getElementById('popupContainer').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
            })
            .catch(error => {
            console.error('Error:', error);

            // Handle errors as needed
        });
        }
    }

        // Attach event listeners to open and close buttons
        document.getElementById('openPopupButton').addEventListener('click', openPopup);
        document.getElementById('closePopupButton').addEventListener('click', closePopup);
    


       
    </script>
</body>
</html>

</html>
