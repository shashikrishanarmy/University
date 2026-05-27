<?php
session_start();
include '../config/db.php';

// Check if there is a redirection message in the URL
$message = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'updated') {
        $message = "Timetable Entry Updated!";
    } elseif ($_GET['msg'] === 'deleted') {
        $message = "Timetable Entry Deleted!";
    }
}

// ================= ADD TIMETABLE ENTRY =================
if(isset($_POST['add_timetable'])){

    $course_id = intval($_POST['course_id']);
    $lecture_title = mysqli_real_escape_string($conn, $_POST['lecture_title']);
    $room = mysqli_real_escape_string($conn, $_POST['room']);
    
    // FORMAT FIX: Convert datetime-local format to standard MySQL timestamp layout
    $raw_schedule = $_POST['schedule_date_time'];
    $schedule_date_time = date('Y-m-d H:i:s', strtotime($raw_schedule));

    $query = "INSERT INTO timetables (course_id, lecture_title, schedule_date_time, room)
              VALUES ($course_id, '$lecture_title', '$schedule_date_time', '$room')";

    mysqli_query($conn, $query);
    $message = "Lecture Scheduled Successfully!";
}

// ================= DELETE TIMETABLE ENTRY =================
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM timetables WHERE id=$id");

    header("Location: manage_timetable.php?msg=deleted");
    exit();
}

// ================= UPDATE TIMETABLE ENTRY =================
if(isset($_POST['update_timetable'])){

    $id = intval($_POST['id']);
    $course_id = intval($_POST['course_id']);
    $lecture_title = mysqli_real_escape_string($conn, $_POST['lecture_title']);
    $room = mysqli_real_escape_string($conn, $_POST['room']);
    
    // FORMAT FIX: Convert datetime-local format to standard MySQL timestamp layout
    $raw_schedule = $_POST['schedule_date_time'];
    $schedule_date_time = date('Y-m-d H:i:s', strtotime($raw_schedule));

    $query = "UPDATE timetables SET 
              course_id=$course_id,
              lecture_title='$lecture_title',
              schedule_date_time='$schedule_date_time',
              room='$room'
              WHERE id=$id";

    mysqli_query($conn, $query);

    // Redirecting here clears out '?edit=' from the URL, automatically hiding the form
    header("Location: manage_timetable.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Manage Timetable</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #7480b5ff;
            padding-top: 110px;
        }

        nav {
            background: #0b1d51;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 10px;
        }

        nav a:hover {
            color: gold;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .logo-section h2 {
            color: white;
            margin: 0;
        }

        .title {
            text-align: center;
            padding: 20px 20px;
        }

        .title h1 {
            color: #0b1d51;
            font-size: 40px;
            margin: 0 0 10px 0;
        }

        /* TIMEOUT ALERT POPUP CONTAINER */
        .alert-box {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 12px;
            width: 50%;
            margin: 15px auto 0 auto;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }

        .container {
            width: 80%;
            margin: auto;
            margin-bottom: 60px;
        }

        form {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        form h3 {
            color: #0b1d51;
            margin-top: 0;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background: #0b1d51;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 4px;
        }

        button:hover {
            background: #112b73;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        th {
            background: #0b1d51;
            color: white;
            padding: 15px;
        }

        td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table tr:hover {
            background: #f2f2f2;
        }

        .btn {
            text-decoration: none;
            padding: 6px 12px;
            color: white;
            font-weight: bold;
            font-size: 14px;
            border-radius: 4px;
            display: inline-block;
            margin: 2px;
        }

        .edit {
            background: orange;
        }

        .delete {
            background: red;
        }

        footer {
            background: #0b1d51;
            color: white;
            padding: 20px;
            margin-top: 40px;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-left p {
            margin: 5px 0;
        }

        .map-container iframe {
            width: 300px;
            height: 200px;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 15px;
            border-top: 1px solid #ffffff33;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo-section">
            <img src="../assets/images/logo.png" alt="NEXUS UNIVERSITY Logo" class="logo">
            <h2>NEXUS UNIVERSITY</h2>
        </div>
        <div>
           <a href="../admin/dashboard.php">PANEL</a>
            <a href="../admin/manage_requests.php">REQUESTS</a>
            <a href="../admin/coursesdash.php">COURSES</a>
            <a href="../admin/manage_enrollments.php">ENROLLMENTS</a>
            <a href="../admin/manage_materials.php">MATERIALS</a>
            <a href="../admin/view_submissions.php">SUBMISSIONS</a>
            <a href="../admin/manage_assignments.php">ASSIGNMENTS</a>
            <a href="../admin/manage_timetable.php">TIMETABLE</a>
            <a href="../auth/logout.php">LOGOUT</a>  
        </div>
    </nav>

    <section class="title">
        <h1>Manage Lecture Timetables</h1>
        
        <?php if(!empty($message)): ?>
            <div id="status-alert" class="alert-box">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
    </section>

    <div class="container">

        <form method="POST">
            <h3>Schedule a New Lecture</h3>

            <select name="course_id" required>
                <option value="">Select Course</option>
                <?php
                $courses = mysqli_query($conn, "SELECT * FROM courses");
                while($c = mysqli_fetch_assoc($courses)){
                    echo "<option value='{$c['id']}'>" . htmlspecialchars($c['course_name']) . "</option>";
                }
                ?>
            </select>

            <input type="text" name="lecture_title" placeholder="Lecture Session Title (e.g., Intro to PHP Variables)" required>
            <input type="datetime-local" name="schedule_date_time" required>
            <input type="text" name="room" placeholder="Location / Lecture Room (e.g., Lab 04, Auditorium B)" required>
            <br><br>

            <button type="submit" name="add_timetable">Schedule Lecture</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Module</th>
                    <th>Lecture Session</th>
                    <th>Scheduled Date & Time</th>
                    <th>Room Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT t.*, c.course_name FROM timetables t INNER JOIN courses c ON t.course_id = c.id ORDER BY t.schedule_date_time ASC");
                while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><strong><?php echo htmlspecialchars($row['course_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['lecture_title']); ?></td>
                    <td><?php echo date('Y-m-d h:i A', strtotime($row['schedule_date_time'])); ?></td>
                    <td><code><?php echo htmlspecialchars($row['room']); ?></code></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>" class="btn edit">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn delete" onclick="return confirm('Are you sure you want to cancel this scheduled lecture?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php
        if(isset($_GET['edit'])){
            $id = intval($_GET['edit']);
            $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM timetables WHERE id=$id"));
            if($edit){
        ?>
        <br><hr style="border: 0; border-top: 1px solid #ffffff66;"><br>
        
        <form method="POST">
            <h3>Update Scheduled Lecture</h3>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit['id']); ?>">

            <select name="course_id" required>
                <option value="">Select Course</option>
                <?php
                $courses = mysqli_query($conn, "SELECT * FROM courses");
                while($c = mysqli_fetch_assoc($courses)){
                    $selected = ($c['id'] == $edit['course_id']) ? "selected" : "";
                    echo "<option value='{$c['id']}' $selected>" . htmlspecialchars($c['course_name']) . "</option>";
                }
                ?>
            </select>

            <input type="text" name="lecture_title" value="<?php echo htmlspecialchars($edit['lecture_title']); ?>" required>
            <input type="datetime-local" name="schedule_date_time" value="<?php echo date('Y-m-d\TH:i', strtotime($edit['schedule_date_time'])); ?>" required>
            <input type="text" name="room" value="<?php echo htmlspecialchars($edit['room']); ?>" required>
            <br><br>

            <button type="submit" name="update_timetable">Update Entry</button>
        </form>
        <?php } } ?>

    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <h3>Contact Us</h3>
                <p>📞 Call: 0114325690</p>
                <p>✉️ Email: nexusuniversity@gmail.com</p>
                <p>📍 Address: Nexus University, New Kandy Road, Malabe</p>
            </div>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=..." style="border:0;" loading="lazy"></iframe>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertElement = document.getElementById("status-alert");
            if (alertElement) {
                // Keep visible for 3.5 seconds, then transition transparency
                setTimeout(function() {
                    alertElement.style.opacity = "0";
                    
                    // Completely discard the layout element space after fade structural cycle drops
                    setTimeout(function() {
                        alertElement.remove();
                    }, 500);
                }, 3500);
            }
        });
    </script>

</body>
</html>