<?php
include '../config/db.php';

if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);

    // 1. Find the student's name and email from the users table
    $user_query = mysqli_query($conn, "SELECT name, email FROM users WHERE id = $student_id LIMIT 1");
    
    if ($user_query && mysqli_num_rows($user_query) > 0) {
        $user = mysqli_fetch_assoc($user_query);
        $student_name = mysqli_real_escape_string($conn, $user['name']);
        $student_email = mysqli_real_escape_string($conn, $user['email']);

        // 2. Find the string value of the requested course from request_courses table
        // We match by email and name to ensure we get the correct profile data
        $request_query = mysqli_query($conn, "SELECT requested_course FROM request_courses 
                                              WHERE (email = '$student_email' OR student_name = '$student_name') 
                                              ORDER BY id DESC LIMIT 1");

        if ($request_query && mysqli_num_rows($request_query) > 0) {
            $request = mysqli_fetch_assoc($request_query);
            $requested_course_name = mysqli_real_escape_string($conn, $request['requested_course']);

            // 3. Find the official ID of that course from the main courses table
            $course_query = mysqli_query($conn, "SELECT id, course_name FROM courses WHERE course_name = '$requested_course_name' LIMIT 1");
            
            if ($course_query && mysqli_num_rows($course_query) > 0) {
                $course = mysqli_fetch_assoc($course_query);
                echo json_encode(['success' => true, 'id' => $course['id'], 'course_name' => $course['course_name']]);
                exit;
            }
        }
    }
}

// Return fallback if no record or match is found
echo json_encode(['success' => false]);
?>