<?php
include '../config/db.php';

if (isset($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    
    // Convert both the DB column and search input to lowercase for case-insensitivity
    $query = "SELECT contact_number, email, nic_or_passport, highest_qualification 
              FROM request_courses 
              WHERE LOWER(student_name) = LOWER('$name') 
              ORDER BY id DESC LIMIT 1";
              
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $student_data = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'data' => $student_data]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}
?>