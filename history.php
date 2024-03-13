<?php include 'includes/sessionconnection.php'; ?>

<?php

// Assuming you have the user's ID stored in a session or a variable.
// Replace with the actual user's ID retrieval method
$userId = $_SESSION['user_id'] ?? 25; // Example user_id set to 25 for demonstration

// Query to fetch sleep data for the specific user
$query = "SELECT date_of_sleep, sleep_time, wake_time, sleep_duration, sleep_quality, comments FROM sleep_tracker WHERE user_id = ? ORDER BY date_of_sleep DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId); // 'i' specifies the variable type => 'integer'

// Execute the query
$stmt->execute();

// Bind the result variables
$stmt->bind_result($dateOfSleep, $sleepTime, $wakeTime, $sleepDuration, $sleepQuality, $comments);

// Fetch values and construct the sleep data array
$sleepData = [];
while ($stmt->fetch()) {
    $sleepData[] = [
        'date' => $dateOfSleep,
        'sleepTime' => $sleepTime,
        'wakeTime' => $wakeTime,
        'duration' => substr($sleepDuration, 0, 5), // Assuming sleep_duration is in 'HH:MM:SS' format
        'quality' => $sleepQuality,
        'notes' => $comments
    ];
}

// Convert the PHP array into a JSON string to be used by JavaScript
$sleepDataJson = json_encode($sleepData);

// Close the statement and database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - Sleep.io</title>
    <!-- Include Tailwind CSS from CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body{
        background-image: url('includes/images/StarsBG.png');
    }

.bg-dark-panel {
  background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent dark background for panels */
}



</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-blue-900 text-white font-sans">
    <!-- Fixed navigation bar  -->
    <?php include 'includes/nav.php'; ?>

    <!-- Content section for sleep history and trends -->
</br>
</br>

    <!-- Content section for sleep history and trends -->
    <div class="container mx-auto mt-24 p-8">
        <h2 class="text-3xl text-white mb-4">Hello, <?php echo $username ?></h2>
        <div class="bg-dark-panel rounded-lg p-8 shadow-lg">
            <p class="text-2xl">Your sleep history and trends will display here.</p>
            
<div class="container mx-auto mt-8 p-4">
    <!-- Chart Section -->
    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full lg:w-1/2 px-3 mb-6 md:mb-0">
            <div class="p-6 bg-dark-panel rounded-lg">
                <h3 class="text-xl mb-4">Sleep Duration Over Time</h3>
                <canvas id="sleepDurationChart"></canvas> <!-- Placeholder for Sleep Duration Chart -->
            </div>
        </div>
        <div class="w-full lg:w-1/2 px-3">
            <div class="p-6 bg-dark-panel rounded-lg" style="height: 400px;  overflow: hidden;">
                <h3 class="text-xl mb-4">Sleep Quality Distribution</h3>
                <canvas id="sleepQualityChart" style="  display: block; max-height: 80%;"></canvas> <!-- Placeholder for Sleep Quality Chart -->
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto mt-8 p-4">
    <!-- Customization Section -->
    <div class="flex flex-wrap -mx-3 mb-6 justify-between items-center">
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                Choose Chart Type
            </label>
            <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white" id="chartType">
                <option>Bar</option>
                <option>Line</option>
               
            </select>
        </div>
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                Select Data Columns
            </label>
            <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white" id="qualityDropdown" multiple>
                <option>Very Bad</option>
                <option>Bad</option>
                <option>Fair</option>
                <option>Good</option>
                <option>Excellent</option>
                <option>All Qualities</option> 
            </select>
        </div>
    </div>
</div>
<!-- Interactive Data Table -->
<div class="overflow-x-auto mt-4">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Date of Sleep
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Sleep Time
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Wake Time
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Duration (HH:MM)
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Sleep Quality
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Comments
                </th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <!-- Data rows will be inserted here dynamically -->
        </tbody>
    </table>
</div>
        </div>
    </div>
</body>
<script>

    // Sample Data for Charts and Table
// Use the PHP variable to initialize the sleep data in JavaScript
const sleepData = <?php echo $sleepDataJson; ?>;


// Populate Sleep History Table
// Populate Sleep History Table
function populateTable() {
    const tableBody = document.querySelector('tbody');
    sleepData.forEach(entry => {
        let row = tableBody.insertRow();
        // Create cells for each piece of data
        let dateCell = row.insertCell();
        dateCell.textContent = entry.date;
        
        let sleepTimeCell = row.insertCell();
        sleepTimeCell.textContent = entry.sleepTime;
        
        let wakeTimeCell = row.insertCell();
        wakeTimeCell.textContent = entry.wakeTime;
        
        let durationCell = row.insertCell();
        durationCell.textContent = entry.duration;
        
        let qualityCell = row.insertCell();
        qualityCell.textContent = entry.quality;
        
        let notesCell = row.insertCell();
        notesCell.textContent = entry.notes;
    });
}

// Chart.js Initialization

    let sleepDurationChart = null; // Global variable to hold the chart instance

    // Function to initialize or update the sleep duration chart
    function updateSleepDurationChart(chartType) {
    const ctxDuration = document.getElementById('sleepDurationChart').getContext('2d');
    
    // Destroy the existing chart instance if it exists
    if (sleepDurationChart) {
        sleepDurationChart.destroy();
    }

    // Create a new chart instance with the selected type
    sleepDurationChart = new Chart(ctxDuration, {
        type: chartType,
        data: {
            labels: sleepData.map(entry => entry.date),
            datasets: [{
                label: 'Sleep Duration',
                data: sleepData.map(entry => entry.duration),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
}

    // Event Listener for Chart Type Dropdown Changes
    document.getElementById('chartType').addEventListener('change', (event) => {
        updateSleepDurationChart(event.target.value.toLowerCase());
    });



// Initial population of the table and charts
window.onload = () => {
    populateTable();
    updateSleepDurationChart('Bar'); // Initialize with default type
    updateSleepQualityChart('All Qualities'); // Initialize with default quality
};


// Chart 2 - sleep quality chart


// Function to calculate the percentage of each sleep quality
function calculateQualityPercentages() {
    const totalEntries = sleepData.length;
    const qualityCounts = sleepData.reduce((acc, entry) => {
        acc[entry.quality] = (acc[entry.quality] || 0) + 1;
        return acc;
    }, {});

    const qualityPercentages = Object.keys(qualityCounts).reduce((acc, quality) => {
        acc[quality] = (qualityCounts[quality] / totalEntries) * 100;
        return acc;
    }, {});

    return qualityPercentages;
}

// Function to update the chart based on the selected quality
function updateSleepQualityChart(selectedQuality) {

    const chartElement = document.getElementById('sleepQualityChart');
    if (!chartElement) {
        console.error('No element found with ID sleepQualityChart');
        return;
    }


    const ctxQuality = document.getElementById('sleepQualityChart').getContext('2d');
    const qualityPercentages = calculateQualityPercentages();

    // Check if the chart instance exists and is a Chart
    if (window.sleepQualityChart && window.sleepQualityChart.destroy instanceof Function) {
        window.sleepQualityChart.destroy();
    }


    if (selectedQuality === 'All Qualities') {
        // Create a pie chart for all qualities
        window.sleepQualityChart = new Chart(ctxQuality, {
            type: 'pie',
            data: {
                labels: Object.keys(qualityPercentages),
                datasets: [{
                    data: Object.values(qualityPercentages),
                    backgroundColor: ['red', 'orange', 'yellow', 'green', 'blue'],
                    // ... other pie chart configurations
                }]
            },
            // ... pie chart options
        });
    } else {
        // Create a bar chart for a specific quality
        window.sleepQualityChart = new Chart(ctxQuality, {
            type: 'bar',
            data: {
                labels: [selectedQuality],
                datasets: [{
                    label: selectedQuality + ' Quality Percentage',
                    data: [qualityPercentages[selectedQuality] || 0],
                    backgroundColor: 'blue',
                    // ... other bar chart configurations
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100 // Set y-axis maximum to 100%
                    }
                }
            }
        });
    }
}

// Event Listener for Sleep Quality Dropdown Changes
document.getElementById('qualityDropdown').addEventListener('change', (event) => {
    updateSleepQualityChart(event.target.value);
});

window.onload = () => {
    populateTable();
    updateSleepQualityChart('All Qualities'); // Initialize with 'All Qualities'
    
};

</script>
</html>
