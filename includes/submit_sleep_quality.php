<?php
include 'includes/sessionconnection.php';

if (isset($_POST['user_id']) && isset($_POST['sleep_quality'])) {
    $user_id = $_POST['user_id'];
    $sleep_quality = $_POST['sleep_quality'];
    $finish_time = date("Y-m-d H:i:s");

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Update the existing sleep session record with finish time and sleep quality
        $sql = "UPDATE sleep_sessions SET finish_time = ?, sleep_quality = ? WHERE user_id = ? AND finish_time IS NULL ORDER BY id DESC LIMIT 1";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $finish_time, $sleep_quality, $user_id);
        mysqli_stmt_execute($stmt);
        
        // Commit transaction
        mysqli_commit($conn);
        
        echo json_encode(array('success' => true));
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        
        echo json_encode(array('success' => false, 'error' => $e->getMessage()));
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo json_encode(array('success' => false, 'error' => 'Invalid request'));
}
?>
