<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // remove after login system
}

$user_id = $_SESSION['user_id'];
$message = "";

// ================= UPLOAD LOGIC =================
if (isset($_POST['submit_assignment'])) {

    $assignment_id = intval($_POST['assignment_id']);

    // Check deadline
    $check = mysqli_query($conn, "SELECT deadline FROM assignments WHERE id=$assignment_id");
    $row_deadline = mysqli_fetch_assoc($check);

    if (date('Y-m-d H:i:s') > $row_deadline['deadline']) {
        $message = "<p style='color:red;'>Deadline passed!</p>";
    } else {

        $target_dir = "../uploads/assignments/"; // UPDATED

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["assignment_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed = ['pdf','doc','docx','zip'];

        if (in_array($file_type, $allowed)) {

            if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target_file)) {

                // Check existing submission
                $check_sub = mysqli_query($conn,
                    "SELECT id FROM submissions 
                     WHERE assignment_id=$assignment_id AND user_id=$user_id");

                if (mysqli_num_rows($check_sub) > 0) {

                    mysqli_query($conn,
                        "UPDATE submissions 
                         SET file_path='$target_file', submitted_at=NOW()
                         WHERE assignment_id=$assignment_id AND user_id=$user_id");

                    $message = "<p style='color:green;'>Updated successfully!</p>";

                } else {

                    mysqli_query($conn,
                        "INSERT INTO submissions (assignment_id, user_id, file_path) 
                         VALUES ($assignment_id, $user_id, '$target_file')");

                    $message = "<p style='color:green;'>Submitted successfully!</p>";
                }

            } else {
                $message = "<p style='color:red;'>Upload failed!</p>";
            }

        } else {
            $message = "<p style='color:red;'>Invalid file type!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Assignments</title>

<style>
body {
    margin:0;
    font-family:Arial;
    background:#7480b5ff;
    padding-top:110px;
}

nav {
    background:#0b1d51;
    padding:15px;
    position:fixed;
    top:0;
    width:100%;
}

nav a {
    color:white;
    margin:10px;
    text-decoration:none;
}

nav a:hover { color:gold; }

.container {
    width:80%;
    margin:auto;
}

.card {
    background:white;
    padding:20px;
    margin-bottom:20px;
    border-radius:8px;
}

.btn {
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
    color:white;
}

.download { background:#007bff; }
.submit { background:#28a745; }

.status {
    font-weight:bold;
    margin-top:10px;
}
</style>

</head>
<body>

<nav>
    <a href="home.php">HOME</a>
    <a href="#">COURSES</a>
    <a href="#">MATERIALS</a>
    <a href="../auth/logout.php">LOGOUT</a>
</nav>

<div class="container">

<h2 style="text-align:center;">My Assignments</h2>

<div><?php echo $message; ?></div>

<?php

$query = "SELECT a.*, s.file_path AS my_file, s.submitted_at
          FROM assignments a
          LEFT JOIN submissions s 
          ON a.id = s.assignment_id AND s.user_id = $user_id
          ORDER BY a.deadline ASC";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){

    $deadline = $row['deadline'];
    $expired = (date('Y-m-d H:i:s') > $deadline);
    $submitted = !empty($row['my_file']);
?>

<div class="card">

<h3><?php echo $row['title']; ?></h3>
<p><?php echo $row['description']; ?></p>

<p><b>Deadline:</b> <?php echo $deadline; ?></p>

<!-- DOWNLOAD ASSIGNMENT FILE (ADMIN UPLOAD) -->
<?php if (!empty($row['file_path'])) { ?>
    <a href="<?php echo $row['file_path']; ?>" class="btn download" download>
        Download Assignment
    </a>
<?php } ?>

<!-- STATUS -->
<div class="status">
<?php
if ($submitted) {
    echo "✅ Submitted on " . $row['submitted_at'];
} elseif ($expired) {
    echo "❌ Deadline Passed";
} else {
    echo "⏳ Not Submitted";
}
?>
</div>

<!-- UPLOAD -->
<?php if (!$expired) { ?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
    <br>
    <input type="file" name="assignment_file" required>
    <br><br>
    <button type="submit" name="submit_assignment" class="btn submit">
        <?php echo $submitted ? "Re-submit" : "Submit"; ?>
    </button>
</form>

<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>