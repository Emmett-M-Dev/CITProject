<?php 
require 'includes/sessionConnection.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize form inputs
    $dateOfSleep = filter_input(INPUT_POST, 'dateOfSleep', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sleepTime = filter_input(INPUT_POST, 'sleepTime', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $wakeTime = filter_input(INPUT_POST, 'wakeTime', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sleepQuality = filter_input(INPUT_POST, 'sleepQuality', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Assuming $userId is retrieved from session or another source
    $userId = $_SESSION['user_id'] ?? 0; // Replace 0 with a failure or redirect condition

    // Validate input (basic example, expand according to your needs)
    if (empty($dateOfSleep) || empty($sleepTime) || empty($wakeTime) || empty($sleepQuality)) {
        // Handle error - redirect or display an error message
        echo "Please fill in all required fields.";
        exit;
    }

    // Calculate sleep duration
    $sleepDateTime = new DateTime($dateOfSleep . ' ' . $sleepTime);
    $wakeDateTime = new DateTime($dateOfSleep . ' ' . $wakeTime);

    // If wake time is less than sleep time, it means the user woke up the next day.
    if ($wakeDateTime <= $sleepDateTime) {
        $wakeDateTime->modify('+1 day');
    }

    // Calculate the interval
    $interval = $sleepDateTime->diff($wakeDateTime);
    $sleepDuration = $interval->format('%H:%I:%S');

    // SQL to insert data into the sleep_tracker table
    $sql = "INSERT INTO sleep_tracker (user_id, date_of_sleep, sleep_time, wake_time, sleep_duration, sleep_quality, comments) VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issssss", $userId, $dateOfSleep, $sleepTime, $wakeTime, $sleepDuration, $sleepQuality, $comments);

        if ($stmt->execute()) {
            // Successfully inserted
            header('Location: tracker.php?status=success');
            exit(); 
        } else {
            // Handle insertion error
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Handle preparation error
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
} 
?>
