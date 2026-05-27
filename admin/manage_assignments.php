<?php
session_start();
include '../config/db.php';

$message = "";

// Check if there is a redirection message in the URL
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'updated') {
        $message = "Assignment Updated!";
    } elseif ($_GET['msg'] === 'deleted') {
        $message = "Assignment Deleted!";
    }
}

// ================= ADD ASSIGNMENT =================
if(isset($_POST['add_assignment'])){

    $course_id = intval($_POST['course_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // CRITICAL FORMAT FIX: Convert datetime-local format to standard MySQL timestamp layout
    $raw_deadline = $_POST['deadline'];
    $deadline = date('Y-m-d H:i:s', strtotime($raw_deadline));

    $file_path = "";

    // FILE UPLOAD
    if(!empty($_FILES['file']['name'])){

        $target_dir = "../uploads/assignments/";

        if(!file_exists($target_dir)){
            mkdir($target_dir,0777,true);
        }

        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;

        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        $file_path = mysqli_real_escape_string($conn, $target_file);
    }

    $query = "INSERT INTO assignments (course_id,title,description,deadline,file_path)
              VALUES ($course_id,'$title','$description','$deadline','$file_path')";

    mysqli_query($conn,$query);

    $message = "Assignment Added!";
}

// ================= DELETE =================
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']); // Sanitize ID input

    mysqli_query($conn,"DELETE FROM assignments WHERE id=$id");

    header("Location: manage_assignments.php?msg=deleted");
    exit();
}

// ================= UPDATE =================
if(isset($_POST['update_assignment'])){

    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // CRITICAL FORMAT FIX: Convert datetime-local format to standard MySQL timestamp layout
    $raw_deadline = $_POST['deadline'];
    $deadline = date('Y-m-d H:i:s', strtotime($raw_deadline));

    $file_update = "";

    if(!empty($_FILES['file']['name'])){
        $target_dir = "../uploads/assignments/";
        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;

        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        
        $target_file_escaped = mysqli_real_escape_string($conn, $target_file);
        $file_update = ", file_path='$target_file_escaped'";
    }

    $query = "UPDATE assignments SET 
              title='$title',
              description='$description',
              deadline='$deadline'
              $file_update
              WHERE id=$id";

    mysqli_query($conn,$query);

    // Redirecting here strips '?edit=' out of the URL, hiding the form instantly
    header("Location: manage_assignments.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Manage Assignments</title>
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
            top: 0; left: 0; right: 0;
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

        /* NEW STYLE: ALERT SUCCESS DISAPPEAR CONTAINER BOX */
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

        .edit { background: orange; }
        .delete { background: red; }
        .view-btn { background: #007bff; }

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

        .footer-left p { margin: 5px 0; }
        .map-container iframe { width: 300px; height: 200px; }
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
        <h1>Manage Assignments</h1>
        
        <?php if(!empty($message)): ?>
            <div id="status-alert" class="alert-box">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
    </section>

    <div class="container">

        <form method="POST" enctype="multipart/form-data">
            <h3>Add Assignment</h3>

            <select name="course_id" required>
                <option value="">Select Course</option>
                <?php
                $courses = mysqli_query($conn,"SELECT * FROM courses");
                while($c = mysqli_fetch_assoc($courses)){
                    echo "<option value='{$c['id']}'>" . htmlspecialchars($c['course_name']) . "</option>";
                }
                ?>
            </select>

            <input type="text" name="title" placeholder="Assignment Title" required>
            <textarea name="description" placeholder="Description"></textarea>
            <input type="datetime-local" name="deadline" required>
            <input type="file" name="file">
            <br><br>

            <button type="submit" name="add_assignment">Add Assignment</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Deadline</th>
                <th>File</th>
                <th>Actions</th>
            </tr>

            <?php
            $result = mysqli_query($conn,"SELECT * FROM assignments");
            while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['deadline']); ?></td>
                <td>
                    <?php if($row['file_path']){ ?>
                        <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="btn view-btn">View</a>
                    <?php } else { echo "None"; } ?>
                </td>
                <td>
                    <a href="?edit=<?php echo $row['id']; ?>" class="btn edit">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this assignment?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>

        <?php
        if(isset($_GET['edit'])){
            $id = intval($_GET['edit']);
            $edit = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM assignments WHERE id=$id"));
            if($edit){
        ?>
        <br><hr style="border: 0; border-top: 1px solid #ffffff66;"><br>
        
        <form method="POST" enctype="multipart/form-data">
            <h3>Update Assignment</h3>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit['id']); ?>">

            <input type="text" name="title" value="<?php echo htmlspecialchars($edit['title']); ?>" required>
            <textarea name="description"><?php echo htmlspecialchars($edit['description']); ?></textarea>
            <input type="datetime-local" name="deadline" value="<?php echo date('Y-m-d\TH:i', strtotime($edit['deadline'])); ?>" required>
            <input type="file" name="file">
            <br><br>

            <button type="submit" name="update_assignment">Update</button>
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
                // Wait 3500ms (3.5 seconds) then trigger the fade transition out effect
                setTimeout(function() {
                    alertElement.style.opacity = "0";
                    
                    // Wait another 500ms for the styling animation cleanup to clear the space allocation
                    setTimeout(function() {
                        alertElement.remove();
                    }, 500);
                }, 3500);
            }
        });
    </script>

</body>
</html>