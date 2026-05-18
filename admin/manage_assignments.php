<?php
session_start();
include '../config/db.php';

$message = "";

// ================= ADD ASSIGNMENT =================
if(isset($_POST['add_assignment'])){

    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

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

        $file_path = $target_file;
    }

    $query = "INSERT INTO assignments (course_id,title,description,deadline,file_path)
              VALUES ('$course_id','$title','$description','$deadline','$file_path')";

    mysqli_query($conn,$query);

    $message = "Assignment Added!";
}

// ================= DELETE =================
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    mysqli_query($conn,"DELETE FROM assignments WHERE id=$id");

    header("Location: manage_assignments.php");
}

// ================= UPDATE =================
if(isset($_POST['update_assignment'])){

    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

    $file_update = "";

    if(!empty($_FILES['file']['name'])){
        $target_dir = "../uploads/assignments/";
        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;

        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        $file_update = ", file_path='$target_file'";
    }

    $query = "UPDATE assignments SET 
              title='$title',
              description='$description',
              deadline='$deadline'
              $file_update
              WHERE id=$id";

    mysqli_query($conn,$query);

    $message = "Assignment Updated!";
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Assignments</title>

<style>
body {
            margin: 0;
            font-family: Arial;
            background-color: #7480b5ff;
            padding-top: 110px;
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

.container{
    width:80%;
    margin:auto;
}

form{
    background:white;
    padding:20px;
    margin-bottom:20px;
    border-radius:8px;
}

input,textarea,select{
    width:100%;
    padding:10px;
    margin:8px 0;
}

button{
    padding:10px;
    background:#0b1d51;
    color:white;
    border:none;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th{
    background:#0b1d51;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}

a{
    text-decoration:none;
    padding:5px 10px;
    color:white;
}

.edit{background:orange;}
.delete{background:red;}
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
            <a href="../admin/coursesdash.php">COURSES</a>
            <a href="../admin/manage_materials.php">MATERIALS</a>
            <a href="../admin/view_submissions.php">SUBMISSIONS</a>
            <a href="../admin/manage_assignments.php">ASSIGNMENTS</a>
            <a href="../auth/logout.php">LOGOUT</a> 
        </div>
    </nav>

<div class="container">

<h2>Manage Assignments</h2>

<p style="color:green;"><?php echo $message; ?></p>

<!-- ADD FORM -->
<form method="POST" enctype="multipart/form-data">

    <h3>Add Assignment</h3>

    <select name="course_id" required>
        <option value="">Select Course</option>

        <?php
        $courses = mysqli_query($conn,"SELECT * FROM courses");
        while($c = mysqli_fetch_assoc($courses)){
            echo "<option value='{$c['id']}'>{$c['course_name']}</option>";
        }
        ?>
    </select>

    <input type="text" name="title" placeholder="Assignment Title" required>

    <textarea name="description" placeholder="Description"></textarea>

    <input type="datetime-local" name="deadline" required>

    <input type="file" name="file">

    <button type="submit" name="add_assignment">Add Assignment</button>

</form>

<!-- ASSIGNMENTS TABLE -->
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
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['title']; ?></td>
    <td><?php echo $row['deadline']; ?></td>
    <td>
        <?php if($row['file_path']){ ?>
            <a href="<?php echo $row['file_path']; ?>" target="_blank">View</a>
        <?php } ?>
    </td>
    <td>
        <a href="?edit=<?php echo $row['id']; ?>" class="edit">Edit</a>
        <a href="?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Delete?')">Delete</a>
    </td>
</tr>

<?php } ?>

</table>

<!-- EDIT FORM -->
<?php
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM assignments WHERE id=$id"));
?>

<form method="POST" enctype="multipart/form-data">

    <h3>Update Assignment</h3>

    <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">

    <input type="text" name="title" value="<?php echo $edit['title']; ?>" required>

    <textarea name="description"><?php echo $edit['description']; ?></textarea>

    <input type="datetime-local" name="deadline" value="<?php echo date('Y-m-d\TH:i', strtotime($edit['deadline'])); ?>" required>

    <input type="file" name="file">

    <button type="submit" name="update_assignment">Update</button>

</form>

<?php } ?>

</div>

</body>
</html>