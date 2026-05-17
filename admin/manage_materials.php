<?php
include '../config/db.php';

$message = "";

// --- 1. HANDLE MATERIAL UPLOAD (CREATE) ---
if (isset($_POST['upload_material'])) {
    $course_id = intval($_POST['course_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    
    // File Upload Handling
    $target_dir = "../uploads/materials/";
    
    // Check if the directory exists, if not, create it dynamically
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["material_file"]["name"]);
    // Clean file name to avoid spacing errors in URLs
    $file_name = str_replace(' ', '_', $file_name); 
    $target_file = $target_dir . time() . "_" . $file_name; // Add timestamp to prevent overwrite
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Restrict allowed file formats for security purposes
    $allowed_types = array("pdf", "doc", "docx", "ppt", "pptx", "txt", "zip");

    if ($course_id > 0 && !empty($title) && !empty($file_name)) {
        if (in_array($file_type, $allowed_types)) {
            // Attempt to move the temporary uploaded file to the permanent directory
            if (move_uploaded_file($_FILES["material_file"]["tmp_name"], $target_file)) {
                
                // Save relative path pathing details inside the database
                $insert_query = "INSERT INTO course_materials (course_id, title, file_path) 
                                 VALUES ($course_id, '$title', '$target_file')";
                
                if (mysqli_query($conn, $insert_query)) {
                    $message = "<p style='color:green; font-weight:bold;'>Material uploaded and saved successfully!</p>";
                } else {
                    $message = "<p style='color:red; font-weight:bold;'>Database Error: Failed to log material details.</p>";
                }
            } else {
                $message = "<p style='color:red; font-weight:bold;'>Error: Failed to upload file to the server folder directory.</p>";
            }
        } else {
            $message = "<p style='color:red; font-weight:bold;'>Error: Invalid file format. Only PDF, DOCX, PPTX, TXT, and ZIP are allowed.</p>";
        }
    } else {
        $message = "<p style='color:red; font-weight:bold;'>Error: All fields are required.</p>";
    }
}

// --- 2. HANDLE MATERIAL DELETION (DELETE) ---
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // Fetch file path before deleting record from DB to clear physical server storage
    $file_query = "SELECT file_path FROM course_materials WHERE id = $delete_id";
    $file_result = mysqli_query($conn, $file_query);
    
    if ($file_row = mysqli_fetch_assoc($file_result)) {
        $physical_file = $file_row['file_path'];
        // Delete the actual physical file from storage disk if it exists
        if (file_exists($physical_file)) {
            unlink($physical_file);
        }
    }

    // Erase table reference row
    $delete_query = "DELETE FROM course_materials WHERE id = $delete_id";
    mysqli_query($conn, $delete_query);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NEXUS UNIVERSITY - Manage Materials</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; font-family: Arial, sans-serif;
            background-color: #7480b5ff; padding-top: 110px;
        }
        nav {
            background: #0b1d51; padding: 15px; display: flex;
            justify-content: space-between; align-items: center;
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        }
        nav a { color: white; text-decoration: none; margin: 10px; }
        nav a:hover { color: gold; }
        .logo-section { display: flex; align-items: center; gap: 10px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }
        
        .title { text-align: center; padding: 20px; }
        .title h1 { color: #0b1d51; font-size: 40px; }

        .form-container { display: flex; justify-content: center; margin-bottom: 30px; }
        .upload-form {
            background: white; padding: 25px; border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1); width: 80%;
            display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;
        }
        .form-group { display: flex; flex-direction: column; flex: 1; min-width: 200px; }
        .form-group label { margin-bottom: 5px; color: #0b1d51; font-weight: bold; }
        .form-group select, .form-group input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: white; }
        
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-submit { background: #28a745; color: white; }
        .btn-delete { background: #dc3545; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 14px;}

        .table-container { display: flex; justify-content: center; margin-bottom: 60px; }
        table { width: 80%; border-collapse: collapse; background: white; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        table th { background: #0b1d51; color: white; padding: 15px; }
        table td { padding: 15px; text-align: center; border: 1px solid #ddd; }
        table tr:hover { background: #f2f2f2; }
        .msg-box { text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

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
            <a href="../auth/logout.php">LOGOUT</a> 
        </div>
    </nav>

    <section class="title">
        <h1>Manage Course Learning Materials</h1>
    </section>

    <div class="msg-box"><?php echo $message; ?></div>

    <!-- MATERIAL UPLOAD FORM -->
    <div class="form-container">
        <!-- CRITICAL: enctype="multipart/form-data" must be included for files to upload successfully -->
        <form action="" method="POST" enctype="multipart/form-data" class="upload-form">
            
            <!-- Target Course Dropdown -->
            <div class="form-group">
                <label>Target Course</label>
                <select name="course_id" required>
                    <option value="">-- Choose Course --</option>
                    <?php
                    $course_query = "SELECT id, course_name FROM courses";
                    $course_result = mysqli_query($conn, $course_query);
                    while ($course = mysqli_fetch_assoc($course_result)) {
                        echo "<option value='".$course['id']."'>".$course['course_name']."</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Material Document Title -->
            <div class="form-group">
                <label>Material / Lecture Title</label>
                <input type="text" name="title" placeholder="e.g., Week 1: Intro to PHP" required>
            </div>

            <!-- File Upload Input Field -->
            <div class="form-group">
                <label>Select Document File</label>
                <input type="file" name="material_file" required>
            </div>

            <div>
                <button type="submit" name="upload_material" class="btn btn-submit">Upload Material</button>
            </div>
        </form>
    </div>

    <!-- CURRENT MATERIALS VISUAL LIST -->
    <div class="table-container">
        <table>
            <tr>
                <th>Course Name</th>
                <th>Material Title</th>
                <th>Uploaded Filename</th>
                <th>Date Added</th>
                <th>Action</th>
            </tr>

            <?php
            // SQL JOIN: Pulls the correct descriptive course text string instead of numeric cross-reference identifiers
            $query = "SELECT m.id AS material_id, m.title, m.file_path, m.uploaded_at, c.course_name 
                      FROM course_materials m
                      JOIN courses c ON m.course_id = c.id
                      ORDER BY m.id DESC";
                      
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['course_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><code><?php echo basename($row['file_path']); ?></code></td>
                        <td><?php echo date('M d, Y', strtotime($row['uploaded_at'])); ?></td>
                        <td>
                            <a href="?delete_id=<?php echo $row['material_id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Are you sure you want to permanently delete this material file?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='5'>No documents or resource materials uploaded yet.</td></tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>