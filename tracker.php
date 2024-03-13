
<?php

require 'process_form.php';

if (isset($_GET['status']) && $_GET['status'] === 'success') {
  echo "<p>Form submitted successfully!</p>";
}

 // Function to calculate sleep score - Placeholder for your logic
function calculateSleepScore($sleepQuality) {
    // Replace with your actual calculation logic
    $qualityScores = ['Excellent' => 100, 'Good' => 80, 'Fair' => 60, 'Bad' => 40, 'Very Bad' => 20];
    return $qualityScores[$sleepQuality] ?? 0;
}

// Function to calculate sleep streak
function calculateSleepStreak($userId, $conn) {
    $streak = 0;
    $today = new DateTime('today');
    
    // Prepare statement to get all sleep data for user ordered by date
    $stmt = $conn->prepare("SELECT date_of_sleep FROM sleep_tracker WHERE user_id = ? ORDER BY date_of_sleep DESC");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sleepDate = new DateTime($row['date_of_sleep']);
            $diff = $today->diff($sleepDate)->days;

            // If there's no gap in dates, increase streak
            if ($diff == $streak) {
                $streak++;
                // Update 'today' to check for the previous day in the next iteration
                $today->modify('-1 day');
            } else {
                // If there's a gap, streak ends
                break;
            }
        }
    }

    $stmt->close();
    return $streak;
}

// Assuming you have the user's ID from the session
$userId = $_SESSION['user_id'] ?? 1; // Fallback to user ID 1

// Fetch the latest sleep data entry
$stmt = $conn->prepare("SELECT sleep_quality, sleep_time, wake_time, date_of_sleep FROM sleep_tracker WHERE user_id = ? ORDER BY date_of_sleep DESC LIMIT 1");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$sleepData = $result->fetch_assoc();

// Initialize variables
$sleepScore = 0;
$hoursSlept = '0';
$wakeTime = '00:00';
$sleepStreak = 0;

if ($sleepData) {
    // Calculate sleep score
    $sleepScore = calculateSleepScore($sleepData['sleep_quality']);

    // Parse times
    $sleepDateTime = new DateTime($sleepData['sleep_time']);
    $wakeDateTime = new DateTime($sleepData['wake_time']);

    // Calculate hours slept (assuming 'sleep_time' and 'wake_time' are in 'HH:MM:SS' format)
    $interval = $sleepDateTime->diff($wakeDateTime);
    $hoursSlept = $interval->format('%h');
    $minutesSlept = $interval->format('%i');
    $hoursSleptText = $hoursSlept . 'h ' . $minutesSlept . 'm';

    // Format wake time
    $wakeTime = $wakeDateTime->format('g:i A');
}

// Calculate sleep streak
$sleepStreak = calculateSleepStreak($userId, $conn);

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Include Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


 <style type="text/css" src="trackerStyles"></style>
</head>
<body class="bg-gray-900 text-white">
<?php  include 'includes/nav.php'; ?>


  <!-- Hero Section -->
  <section id="section-1" class="relative h-screen flex flex-col justify-center items-center text-center text-white px-4">
    <img src="includes/images/evening_sky.png" alt="Night Sky" class="absolute top-0 left-0 w-full h-full object-cover" />

    <div class="z-10 flex justify-between items-center w-full max-w-4xl mx-auto">
        <!-- Left Div: Greeting and User Name -->
        <div>
            <h1 class="text-6xl font-bold mb-4 hero-title">Hello <?php echo $username; ?></h1>
            <p class="text-xl mb-8">Let's continue tracking your sleep</p>
        </div>


        <!-- Right Div: Sub Progress Bars -->
        <div class="mt-8 p-6 bg-white/30 backdrop-blur-lg rounded-xl border border-gray-200/50 flex items-center justify-between">
        
    <!-- Left Div: Main Sleep Score -->
    <div class="flex flex-col items-center mr-8">
        
        <div class="w-40 h-40 border-8 rounded-full border-green-500 text-5xl font-bold flex justify-center items-center">
            85%
        </div>
        <p class="text-xl mt-2">Sleep Score</p>
    </div>

    <!-- Right Div: Sub Progress Bars -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <span class="text-sm">Hours Slept</span>
            <span class="text-sm font-semibold">7h 30m</span>
            <div class="w-full bg-gray-200 h-2.5 rounded-full ml-2">
                <div class="bg-blue-500 h-2.5 rounded-full" style="width: 75%;"></div>
            </div>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm">Wake Time</span>
            <span class="text-sm font-semibold">6:00 AM</span>
            <div class="w-full bg-gray-200 h-2.5 rounded-full ml-2">
                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 50%;"></div>
            </div>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm">Sleep Streak</span>
            <span class="text-sm font-semibold">5 Days</span>
            <div class="w-full bg-gray-200 h-2.5 rounded-full ml-2">
                <div class="bg-red-500 h-2.5 rounded-full" style="width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

    </div>

    <!-- arrow to scroll -->
    <div class="absolute inset-x-0 bottom-0 h-16">
        <a href="#section-2" class="scroll-arrow">
            <i class="fa fa-arrow-down"></i> Continue here to begin sleep tracking
        </a>
    </div>
</section>


  <!-- Tool Section for actually tracking sleep -->
  <section id="section-2" class="scroll-arrow relative h-screen flex flex-col justify-center items-center text-center text-white px-4">
  <div class="grid grid-cols-2 gap-10">  


  <!-- Sleep & Description Section -->
  <form id='sleepTrackerForm' action="process_form.php" method="post" class="p-6 float-left">
    <div class="bg-gray-700 p-4 rounded-lg">
        <!-- Date of Sleep Input -->
        <div class="mb-4">
            <label for="dateOfSleep" class="block mb-2">Date of Sleep</label>
            <input type="date" id="dateOfSleep" name="dateOfSleep" class="w-full p-2 bg-gray-600 rounded" required>
        </div>

        <!-- Sleep Time Input -->
        <div class="mb-4">
            <label for="sleepTime" class="block mb-2">Sleep Time</label>
            <input type="time" id="sleepTime" name="sleepTime" class="w-full p-2 bg-gray-600 rounded" required>
        </div>

        <!-- Wake Time Input -->
        <div class="mb-4">
            <label for="wakeTime" class="block mb-2">Wake Time</label>
            <input type="time" id="wakeTime" name="wakeTime" class="w-full p-2 bg-gray-600 rounded" required>
        </div>

        <!-- Sleep Quality Dropdown -->
        <div class="mb-4">
            <label for="sleepQuality" class="block mb-2">Sleep Quality</label>
            <select id="sleepQuality" name="sleepQuality" class="w-full p-2 bg-gray-600 rounded">
                <option value="Very Bad">Very Bad</option>
                <option value="Bad">Bad</option>
                <option value="Fair">Fair</option>
                <option value="Good">Good</option>
                <option value="Excellent">Excellent</option>
            </select>
        </div>

        <!-- Comments Input -->
        <div class="mb-4">
            <label for="comments" class="block mb-2">Comments</label>
            <textarea id="comments" name="comments" placeholder="Add a comment" class="w-full p-2 bg-gray-600 rounded"></textarea>
        </div>
        
        <!-- Submit Button -->
        <div class="submit-button-container">
            <button type="submit" id='submit'  class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Submit Sleep Data
            </button>
        </div>
    </div>  
</form>


 
<!-- Calendar Widget Container -->
<div id="calendar-widget" class="calendar-container p-6">
  <div class="bg-gray-700 p-4 rounded-lg">
    <!-- Calendar Header -->
    <div class="calendar-header flex justify-between items-center mb-4">
      <button id="prev-month" class="calendar-nav p-2 bg-gray-600 rounded">
        <span class="fas fa-chevron-left"></span>
      </button>
      <div id="current-month" class="current-month">Month Year</div>
      <button id="next-month" class="calendar-nav p-2 bg-gray-600 rounded">
        <span class="fas fa-chevron-right"></span>
      </button>
    </div>

    <!-- Calendar Grid -->
    <div class="calendar-grid grid grid-cols-7 gap-4">
      <!-- Weekday Headers -->
      <div class="text-center font-bold">Sun</div>
      <div class="text-center font-bold">Mon</div>
      <div class="text-center font-bold">Tue</div>
      <div class="text-center font-bold">Wed</div>
      <div class="text-center font-bold">Thu</div>
      <div class="text-center font-bold">Fri</div>
      <div class="text-center font-bold">Sat</div>
      <!-- Day Cells (to be populated by JavaScript) -->
    </div>
  </div>
</div>


</div>
<!-- arrow to next section -->
<div class="absolute inset-x-0 bottom-0 h-16">
        <a href="#section-3" class="scroll-arrow">
            <i class="fa fa-arrow-down"></i> Continue 
        </a>
    </div>
  </section>
        <!-- Dream Diary entery? -->
        <!-- Section 3: Sleep Overview -->
        <section id='section-3' class="scroll-arrow relative flex flex-col text-gray justify-center py-8 px-4 items-center text-center bg-gray-600 text-white" style= "background-image: url('includes/images/sunn3.png')";>
        <!-- <img src="includes/images/sunn3.png" alt="MorningSky" class="absolute top-0 left-0 w-full h-full object-cover" /> -->
    <!-- Header -->
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold mb-2">Your Sleep Overview</h2>
        <p class="text-lg">Explore your sleep patterns and get tips for better rest.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Sleep Trend Chart Container -->
        <div class="chart-container p-4 bg-gray-700 text-white rounded-lg shadow-lg">
            <canvas id="sleepTrendChart"></canvas>
        </div>

        <!-- Sleep Insights Container -->
        <div class="insights-container p-4 bg-gray-700 text-white rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-3">Sleep Insights</h3>
            <ul id="sleepInsights" class="list-disc list-inside">
                <!-- Sleep insights will be populated here -->
            </ul>
        </div>
    </div>

    <!-- Additional Charts -->
    <div class="additional-charts grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
        <div class="sleep-quality-chart bg-gray-700 rounded-lg shadow-lg p-4">
            <canvas id="sleepQualityChart"></canvas>
        </div>
            <!-- Personalized Sleep Tips Container -->
        <div class="tips-container p-4 bg-gray-700 text-white rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold mb-3">Personalized Sleep Tips</h3>
        <!-- Sleep tips will be populated here -->
    </div>
        
    </div>


    
</section>


        <!-- footer -->
        <?php include 'includes/footer.php'?>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
       //scroll feature for screen
    //     document.querySelectorAll('.scroll-arrow').forEach(anchor => {
    //     anchor.addEventListener('click', function (e) {
    //         e.preventDefault();

    //         const targetSection = document.querySelector(this.getAttribute('href'));
    //         if(targetSection) {
    //             targetSection.scrollIntoView({ 
    //                 behavior: 'smooth' 
    //             });
    //         }
    //     });
    // });

  


    // Grims code for form functionality
    document.getElementById('sleepTrackerForm').addEventListener('submit', function(e) {
      console.log('Form attempted to submit');
        // Example client-side validation
        let dateOfSleep = document.getElementById('dateOfSleep').value;
        let sleepTime = document.getElementById('sleepTime').value;
        let wakeTime = document.getElementById('wakeTime').value;
        let sleepQuality = document.getElementById('sleepQuality').value;

        if (!dateOfSleep || !sleepTime || !wakeTime || sleepQuality === "") {
            e.preventDefault(); // Prevent form submission
            alert('Please fill in all required fields.');
        }
    // Parse dates
    let sleepDateTime = new Date(dateOfSleep + ' ' + sleepTime);
    let wakeDateTime = new Date(dateOfSleep + ' ' + wakeTime);

    // Account for crossing over midnight
    if (wakeDateTime < sleepDateTime) {
        wakeDateTime.setDate(wakeDateTime.getDate() + 1);
    }

    // Calculate duration in milliseconds
    let durationMs = wakeDateTime - sleepDateTime;

    // Convert milliseconds into hours and minutes
    let durationHours = Math.floor(durationMs / (3600 * 1000));
    let durationMinutes = Math.floor((durationMs % (3600 * 1000)) / 60000);

    // Construct duration string in "HH:MM" format
    let sleepDuration = `${durationHours.toString().padStart(2, '0')}:${durationMinutes.toString().padStart(2, '0')}`;

    // Debugging: Log the calculated sleep duration
    console.log(`Calculated Sleep Duration: ${sleepDuration}`);


    let formData = new FormData();
    formData.append('dateOfSleep', dateOfSleep);
    formData.append('sleepTime', sleepTime);
    formData.append('wakeTime', wakeTime);
    formData.append('sleepDuration', sleepDuration);
    formData.append('sleepQuality', sleepQuality);

     // AJAX call to send the form data to process_form.php
     fetch('process_form.php', {
        method: 'POST',
        body: new URLSearchParams(new FormData(document.getElementById('sleepTrackerForm')))
    })
    .then(response => response.json())
    .then(data => {
        // Handle successful response here, e.g., show a message or redirect
    })
    .catch(error => {
        // Handle network errors here
        console.error('Error:', error);
    });
});




 
//   const prevButton = document.getElementById('prev-month');
//   const nextButton = document.getElementById('next-month');
//   const currentMonthDiv = document.getElementById('current-month');
//   const grid = document.querySelector('.calendar-grid');
//   let currentDate = new Date();
  
//   function populateCalendar(date) {
//     // Clear the grid
//     while (grid.children.length > 7) {
//       grid.removeChild(grid.lastChild);
//     }

//     // Set the current month display
//     currentMonthDiv.textContent = date.toLocaleDateString('default', { month: 'long', year: 'numeric' });

//     // Start at the first of the month
//     let tempDate = new Date(date.getFullYear(), date.getMonth(), 1);
//     let dayToAdd = tempDate.getDay();
//     let daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

//     // Add empty cells for days of the week before the first of the month
//     for (let i = 0; i < dayToAdd; i++) {
//       let emptyCell = document.createElement('div');
//       emptyCell.className = 'day-cell';
//       grid.appendChild(emptyCell);
//     }

//     // Add day cells for each day of the month
//     for (let day = 1; day <= daysInMonth; day++) {
// let dayCell = document.createElement('div');
// dayCell.className = 'day-cell py-2 bg-gray-600 rounded text-center';
// dayCell.textContent = day;
//  // Highlight the current day
//  if (day === currentDate.getDate() && date.getMonth() === currentDate.getMonth() && date.getFullYear() === currentDate.getFullYear()) {
//     dayCell.classList.add('bg-blue-600');
//     dayCell.classList.remove('bg-gray-600');
//   }

//   dayCell.addEventListener('click', function() {
//     // If there's any previously selected day, remove the highlight
//     let selected = grid.querySelector('.bg-blue-600');
//     if (selected) {
//       selected.classList.add('bg-gray-600');
//       selected.classList.remove('bg-blue-600');
//     }
//     // Highlight the clicked day
//     dayCell.classList.add('bg-blue-600');
//     dayCell.classList.remove('bg-gray-600');
//   });

//   grid.appendChild(dayCell);
// }
// }

// function navigateMonths(step) {
// currentDate.setMonth(currentDate.getMonth() + step);
// populateCalendar(currentDate);
// }

// // Populate the calendar with the current month
// populateCalendar(currentDate);

// // Event listeners for navigation
// prevButton.addEventListener('click', () => navigateMonths(-1));
// nextButton.addEventListener('click', () => navigateMonths(1));



 });



    </script>
    </body>
</html>