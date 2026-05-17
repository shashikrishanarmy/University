<?php
include '../config/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Submissions</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background-color: #7480b5ff;
            padding-top: 110px;
        }

        nav {
            background: #0b1d51;
            padding: 15px;
            position: fixed;
            width: 100%;
            top: 0;
        }

        nav a {
            color: white;
            margin: 10px;
            text-decoration: none;
        }

        nav a:hover {
            color: gold;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #0b1d51;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .btn {
            background: green;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
        }
    </style>
</head>

<body>

<nav>
            <a href="../index.php">HOME</a>
            <a href="../admin/coursesdash.php">COURSES</a>
            <a href="../admin/manage_materials.php">MATERIALS</a>
            <a href="../admin/view_submissions.php">SUBMISSIONS</a>
            <a href="../auth/logout.php">LOGOUT</a> 
</nav>

<h2 style="text-align:center;">Student Submissions</h2>

<table>
    <tr>
        <th>User ID</th>
        <th>Name</th>
        <th>Assignment</th>
        <th>File</th>
        <th>Date</th>
        <th>Download</th>
    </tr>

<?php
$query = "SELECT 
            u.id,
            u.name,
            a.title,
            s.file_path,
            s.submitted_at
          FROM submissions s
          JOIN users u ON s.user_id = u.id
          JOIN assignments a ON s.assignment_id = a.id";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){
?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['title']; ?></td>
    <td><?php echo basename($row['file_path']); ?></td>
    <td><?php echo $row['submitted_at']; ?></td>
    <td>
        <a href="<?php echo $row['file_path']; ?>" class="btn" download>Download</a>
    </td>
</tr>

<?php } ?>

</table>

</body>
</html>