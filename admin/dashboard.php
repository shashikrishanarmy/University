<?php
// This is used to prevent back button access
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

session_start();

include '../config/db.php'; // configuration file for database connection

// Admin protection
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

//ADD NEW COURSE 
if (isset($_POST['add'])) {
    $title  = mysqli_real_escape_string($conn, $_POST['title']);
    $desc   = mysqli_real_escape_string($conn, $_POST['description']);
    $row_id = intval($_POST['row_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $image = $_FILES['image']['name'];
    $tmp   = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "../assets/images/" . $image);

    mysqli_query($conn, "INSERT INTO home_sections (title, description, image, status, row_id) 
                         VALUES ('$title', '$desc', '$image', '$status', $row_id)");
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

//DELETE COURSE 
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM home_sections WHERE id=$id");
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

//UPDATE INDIVIDUAL CARD WITH IT'S ROW GROUP NUMBER
if (isset($_POST['update'])) {
    $id     = intval($_POST['id']); 
    $title  = mysqli_real_escape_string($conn, $_POST['title']);
    $desc   = mysqli_real_escape_string($conn, $_POST['description']);
    $row_id = intval($_POST['row_id']); // Capture the modified Row ID grouping number

    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $image);

        mysqli_query($conn, "UPDATE home_sections SET title='$title', description='$desc', image='$image', row_id=$row_id WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE home_sections SET title='$title', description='$desc', row_id=$row_id WHERE id=$id");
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

//UPDATE ENTIRE ROW VISIBILITY STATUS
if (isset($_POST['update_row_status'])) {
    $target_row = intval($_POST['target_row_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['row_status']);

    // Bulk updates all 3 courses linked to this row value simultaneously
    mysqli_query($conn, "UPDATE home_sections SET status='$new_status' WHERE row_id=$target_row");

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* ================= FETCH & GROUP OPERATIONS ================= */
$result = mysqli_query($conn, "SELECT * FROM home_sections ORDER BY row_id ASC, id ASC");

// Sort cards dynamically into row containers using multidimensional PHP arrays
$rows_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows_data[$row['row_id']]['items'][] = $row;
    $rows_data[$row['row_id']]['status'] = $row['status'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <script>
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.location.href = "../index.php";
    };
    </script>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; margin: 0; padding: 20px; padding-top: 100px; }
        h2 { text-align: center; }
        .container { width: 90%; margin: auto; }
        nav { background: #0b1d51; padding: 15px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; box-sizing: border-box; }
        nav a { color:white; text-decoration:none; margin:10px; }
        nav a:hover { color:gold; }
        .logo-section { display: flex; align-items: center; gap: 10px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }
        .logo-section h2 { color: white; margin: 0; }
        
        .form-box { background: white; padding: 20px; margin-bottom: 35px; border-radius: 8px; }
        input, textarea, select { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
        button { background: #0b1d51; color: white; padding: 10px; border: none; cursor: pointer; }
        
        .row-block { background: white; border: 2px solid #0b1d51; border-radius: 12px; padding: 25px; margin-bottom: 35px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .row-title { color: #0b1d51; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 8px; }
        
        .card-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
        .card { background: #f9f9f9; padding: 15px; border-radius: 10px; border: 1px solid #e0e0e0; box-sizing: border-box; }
        .card img { width: 100%; height: 150px; object-fit: cover; border-radius: 4px; }
        .card label { font-size: 12px; font-weight: bold; color: #555; }
        .actions { margin-top: 10px; }
        .actions a { color: red; text-decoration: none; margin-right: 10px; }

        .row-control-panel { background: #0b1d51; color: white; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-top: 15px; }
        .row-control-panel select { width: auto; min-width: 150px; padding: 6px; margin: 0 10px; }
        .row-control-panel button { background: gold; color: #0b1d51; font-weight: bold; padding: 8px 15px; border-radius: 4px; }
    </style>
</head>
<body>

    <nav>
        <div class="logo-section">
            <img src="../assets/images/logo.png" alt="NEXUS UNIVERSITY Logo" class="logo">
            <h2>NEXUS UNIVERSITY</h2>
        </div>
        <div>
            <a href="../index.php">HOME</a>
            <a href="../users/courses.php">COURSES</a>
            <a href="../auth/logout.php">LOGOUT</a> 
        </div>
    </nav>

    <div class="container">
        <h2>Admin Dashboard</h2>

        <!-- ADD FORM -->
        <div class="form-box">
            <h3>Add New Course</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <input type="file" name="image" required>
                
                <label>Assign to Row Group (1, 2, 3, etc.):</label>
                <input type="number" name="row_id" value="1" min="1" required>
                
                <select name="status">
                    <option value="visible">Visible</option>
                    <option value="hidden">Hidden</option>
                </select>
                <button name="add">Add Course</button>
            </form>
        </div>

        <!-- DISPLAY ITEMS BY ROWS -->
        <?php foreach ($rows_data as $row_id => $rowData) { ?>
            <div class="row-block">
                <h3 class="row-title">Row Group #<?php echo $row_id; ?></h3>
                
                <div class="card-container">
                    <?php foreach ($rowData['items'] as $item) { ?>
                        <div class="card">
                            <!-- EDIT FORM INSIDE CARD -->
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                
                                <label>Course Title:</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>">
                                
                                <img src="../assets/images/<?php echo $item['image']; ?>" alt="course image">
                                
                                <label>Description:</label>
                                <textarea name="description"><?php echo htmlspecialchars($item['description']); ?></textarea>
                                
                                <label>Change Image:</label>
                                <input type="file" name="image">
                                
                                <!-- FIXED: Input field allows moving cards between Row 1, Row 2, etc. -->
                                <label>Row Group Number:</label>
                                <input type="number" name="row_id" value="<?php echo $item['row_id']; ?>" min="1" required>

                                <button name="update">Update Card</button>
                            </form>
                            <div class="actions">
                                <a href="?delete=<?php echo $item['id']; ?>" onclick="return confirm('Delete this card?')">Delete</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <!-- ROW ACTIONS Panel at the bottom of the grid -->
                <div class="row-control-panel">
                    <span><strong>Visibility status for Row Group #<?php echo $row_id; ?>:</strong></span>
                    <form method="POST" style="display: flex; align-items: center; margin: 0;">
                        <input type="hidden" name="target_row_id" value="<?php echo $row_id; ?>">
                        <select name="row_status">
                            <option value="visible" <?php if($rowData['status'] == 'visible') echo 'selected'; ?>>Visible (Show Row)</option>
                            <option value="hidden" <?php if($rowData['status'] == 'hidden') echo 'selected'; ?>>Hidden (Hide Row)</option>
                        </select>
                        <button name="update_row_status">Apply to Row</button>
                    </form>
                </div>
            </div>
        <?php } ?>

    </div>
</body>
</html>