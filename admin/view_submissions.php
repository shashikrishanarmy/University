<?php
include '../config/db.php';

$message = "";

// --- HANDLE ASSIGNMENT DELETION ---
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // Fetch file path to clean up server storage before deleting database record
    $file_query = "SELECT file_path FROM assignments WHERE id = $delete_id";
    $file_result = mysqli_query($conn, $file_query);
    
    if ($file_row = mysqli_fetch_assoc($file_result)) {
        $physical_file = $file_row['file_path'];
        if (!empty($physical_file) && file_exists($physical_file)) {
            unlink($physical_file);
        }
    }

    $delete_query = "DELETE FROM assignments WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $message = "<p style='color:red; font-weight:bold;'>Database Error: Failed to remove assignment record.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - View Submissions</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; 
            font-family: Arial, sans-serif;
            background-color: #7480b5ff; 
            padding-top: 110px;
        }
        
        /* NAVBAR HEADER STYLING */
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
        nav a { color: white; text-decoration: none; margin: 10px; }
        nav a:hover { color: gold; }
        .logo-section { display: flex; align-items: center; gap: 10px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }
        
        /* CONTENT LAYOUT STYLING */
        .title { text-align: center; padding: 20px; }
        .title h1 { color: #0b1d51; font-size: 40px; }

        .table-container { display: flex; justify-content: center; margin-bottom: 60px; }
        table { width: 80%; border-collapse: collapse; background: white; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        table th { background: #0b1d51; color: white; padding: 15px; }
        table td { padding: 15px; text-align: center; border: 1px solid #ddd; vertical-align: middle; }
        table tr:hover { background: #f2f2f2; }
        
        /* UI BUTTONS */
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; font-size: 14px; margin: 2px; }
        .btn-download { background: #28a745; color: white; }
        .btn-download:hover { background: #218838; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-delete:hover { background: #c82333; }
        .disabled-link { background: #6c757d; color: #fff; cursor: not-allowed; pointer-events: none; opacity: 0.6; }
        .msg-box { text-align: center; margin-bottom: 15px; }

        footer {
            background: #0b1d51;
            color: white;
            padding: 20px;
        }
        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 80%;
            margin: 0 auto;
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

    <!-- SHARED NAVIGATION BAR HEADER SECTION -->
    <nav>
        <div class="logo-section">
            <img src="../assets/images/logo.png" alt="Logo" class="logo">
            <h2 style="color:white; margin:0;">NEXUS UNIVERSITY</h2>
        </div>
        <div>
            <a href="../index.php">HOME</a>
            <a href="../admin/coursesdash.php">COURSES</a>
            <a href="../admin/manage_materials.php">MATERIALS</a>
            <a href="../admin/view_submissions.php">SUBMISSIONS</a>
            <a href="../admin/manage_assignments.php">ASSIGNMENTS</a>
            <a href="../auth/logout.php">LOGOUT</a> 
        </div>
    </nav>

    <!-- CONTENT BODY TITLE HEADER -->
    <section class="title">
        <h1>Student Submissions</h1>
    </section>

    <div class="msg-box"><?php echo $message; ?></div>

    <!-- SUBMISSIONS RUNTIME DISPLAY -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Assignment Title</th>
                    <th>Description</th>
                    <th>Deadline</th>
                    <th>Attached Document</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT a.id AS assignment_id, a.title, a.description, a.deadline, a.file_path, c.course_name 
                          FROM assignments a
                          LEFT JOIN courses c ON a.course_id = c.id
                          ORDER BY a.id DESC";
                          
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $has_file = !empty($row['file_path']) && file_exists($row['file_path']);
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['course_name'] ?? 'N/A'); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td>
                                <?php 
                                echo !empty($row['deadline']) ? date('M d, Y - h:i A', strtotime($row['deadline'])) : 'No Deadline'; 
                                ?>
                            </td>
                            <td>
                                <?php if ($has_file): ?>
                                    <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">
                                        <code><?php echo basename($row['file_path']); ?></code>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #666; font-style: italic;">No file attached</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- DOWNLOAD BUTTON -->
                                <?php if ($has_file): ?>
                                    <a href="<?php echo htmlspecialchars($row['file_path']); ?>" 
                                       download="<?php echo basename($row['file_path']); ?>" 
                                       class="btn btn-download">
                                       Download
                                    </a>
                                <?php else: ?>
                                    <a href="#" class="btn disabled-link" title="No file available to download">
                                        Download
                                    </a>
                                <?php endif; ?>

                                <!-- DELETE BUTTON -->
                                <a href="?delete_id=<?php echo $row['assignment_id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Are you sure you want to permanently delete this assignment?');">
                                   Delete
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No assignments discovered in system database registry.</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
                <iframe 
                    src="https://www.google.com/maps/embed?pb=..."
                    style="border:0;" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>
        </div>
    </footer>

</body>
</html>