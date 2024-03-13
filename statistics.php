<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleep Tracker Statistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('includes/images/StarsBG.png');
            background-attachment: fixed; /* This makes the background image fixed */
            background-position: center; /* Centers the image in the viewport */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            background-size: cover; /* Covers the entire viewport */
            
        }
        
        /* Add styles for your content and sections here */
        .section {
            /* Your section styles, for example: */
            position: relative; /* Ensures content is positioned over the background */
            z-index: 1; /* Higher than the default z-index of 0 */
            /* Other styles such as padding, margin, etc. */
        }
    </style>
<body class="bg-gray-900 font-inter text-white">

    <div class="min-h-screen flex">
   
        <?php  include 'includes/nav.php'; ?>

        <!-- Main Content -->
   

        <div class="w-3/4 p-10">
        </br>
        </br>
            <div class="flex justify-between">
                <!-- Left Column -->
                <div class="w-1/3 mr-5">
                    <!-- This Week Section -->
                    <div class="bg-gray-800 p-6 rounded-lg mb-5">
                        <h2 class="text-lg font-semibold mb-4">This Week</h2>
                        <!-- Placeholder for graph -->
                        <canvas id="timeAsleepChart" width="400" height="400"></canvas>
                        <div class="flex justify-between mt-5">
                            <button class="bg-blue-600 py-2 px-4 rounded-lg text-sm">Sleep Stats</button>
                            <button class="bg-blue-600 py-2 px-4 rounded-lg text-sm">103 days</button>
                        </div>
                    </div>

                    
                </div>

                <!-- Right Column -->
                <div class="w-1/3 mr-5">
                    <!-- My Sleep Section -->
                    <div class="bg-gray-800 p-6 rounded-lg mb-5">
                        <h2 class="text-lg font-semibold mb-4">My Sleep</h2>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <img src="https://placehold.co/40x40" alt="Improving sleep quality placeholder" class="rounded-full mr-2">
                                <span>Improving Sleep Quality</span>
                            </div>
                            <span class="text-sm">Monday</span>
                        </div>
                        <!-- Placeholder for more sleep data -->
                        <a href="#" class="text-blue-500 text-sm">View all sleep data</a>
                    </div>

                    <!-- Sleep Comparison Section -->
                    <div class="bg-gray-800 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold mb-4">Sleep Comparison</h2>
                        <div class="bg-gray-700 p-4 rounded-lg mb-3">
                            <div class="flex justify-between mb-1">
                                <span>Sleep Duration</span>
                                <span>7 hrs</span>
                            </div>
                            <!-- Placeholder for progress bar -->
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg mb-3">
                            <div class="flex justify-between mb-1">
                                <span>Quality of Sleep</span>
                                <span>70%</span>
                            </div>
                            <!-- Placeholder for progress bar -->
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <div class="flex justify-between mb-1">
                                <span>Sleep Efficiency</span>
                                <span>55%</span>
                            </div>
                            <!-- Placeholder for progress bar -->
                        </div>
                    </div>
                </div>
                <div class="w-1/3">
                    <!-- Sleep Performance Section -->
                    <div class="bg-gray-800 p-6 rounded-lg mb-4">
                        <h2 class="text-lg font-semibold mb-4">Sleep Performance</h2>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <p>Time Asleep</p>
                                <p>41.3 hours</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-star-half-alt fa-2x mb-2"></i>
                                <p>Average Sleep</p>
                                <p>7.2 hours</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <p>Sleep Goals</p>
                                <p>Sleep Earlier</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
<script>
    const ctx = document.getElementById('timeAsleepChart').getContext('2d');
    const timeAsleepChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Hours Asleep',
                data: [7, 6.5, 8, 5, 9, 7, 6],
                backgroundColor: 'rgba(29, 78, 216, 0.5)',
                borderColor: 'rgba(29, 78, 216, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10
                }
            }
        }
    });
</script>
</html>
