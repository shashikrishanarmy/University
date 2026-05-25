<?php
session_start();
include '../config/db.php';

$message = "";
$msg_style = "";

// ================= ACTION HANDLER: ACCEPT, DECLINE, OR REGISTER =================
if (isset($_GET['id']) && isset($_GET['action'])) {
    $request_id = intval($_GET['id']);
    $action = $_GET['action']; 
    
    // Fetch targeted entry details
    $fetch_query = "SELECT * FROM request_courses WHERE id = $request_id LIMIT 1";
    $result = mysqli_query($conn, $fetch_query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
        $student_email = $student['email'];
        $student_name = $student['student_name'];
        $course_name = $student['requested_course']; 
        
        if ($action === 'accept') {
            mysqli_query($conn, "UPDATE request_courses SET status = 'Accepted' WHERE id = $request_id");
            
            // Compose Acceptance Email
            $subject = "Enrollment Update: Request Approved - Nexus University";
            $email_content = "
            <html>
            <head><title>Nexus University</title></head>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <div style='background: #0b1d51; padding: 20px; color: white; text-align: center;'>
                    <h2>NEXUS UNIVERSITY</h2>
                </div>
                <div style='padding: 20px; border: 1px solid #ddd;'>
                    <p>Dear <strong>$student_name</strong>,</p>
                    <p>We are pleased to inform you that your registration request for the <strong>$course_name</strong> program has been reviewed and <strong>APPROVED</strong> by our academic board.</p>
                    <p>Our student management team will contact you shortly on <strong>{$student['contact_number']}</strong> to guide you through the remaining steps.</p>
                    <br>
                    <p>Best Regards,<br>Academic Registry Office<br>Nexus University, Malabe</p>
                </div>
            </body>
            </html>";
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: nexusuniversity@gmail.com" . "\r\n";
            @mail($student_email, $subject, $email_content, $headers);

            $message = "Request for $student_name has been Approved and an email dispatched.";
            $msg_style = "background: #eef0f8; color: #0b1d51; border: 1px solid #0b1d51;";

        } elseif ($action === 'decline') {
            mysqli_query($conn, "UPDATE request_courses SET status = 'Declined' WHERE id = $request_id");
            
            // Compose Rejection Email
            $subject = "Enrollment Update: Request Status - Nexus University";
            $email_content = "
            <html>
            <head><title>Nexus University</title></head>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <div style='background: #0b1d51; padding: 20px; color: white; text-align: center;'>
                    <h2>NEXUS UNIVERSITY</h2>
                </div>
                <div style='padding: 20px; border: 1px solid #ddd;'>
                    <p>Dear <strong>$student_name</strong>,</p>
                    <p>Thank you for your interest in the educational tracks offered by Nexus University.</p>
                    <p>We regret to inform you that after careful evaluation, we are unable to approve your application request for the <strong>$course_name</strong> track at this time.</p>
                    <br>
                    <p>Best Regards,<br>Admissions Registry Desk<br>Nexus University, Malabe</p>
                </div>
            </body>
            </html>";
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: nexusuniversity@gmail.com" . "\r\n";
            @mail($student_email, $subject, $email_content, $headers);

            $message = "Request for $student_name has been Declined and a status update email sent.";
            $msg_style = "background: #ffdddd; color: red; border: 1px solid red;";
            
        } elseif ($action === 'register') {
            // NEW STEP: Flag record status as Registered before navigating to signup form
            mysqli_query($conn, "UPDATE request_courses SET status = 'Registered' WHERE id = $request_id");
            
            // Securely transfer to original profile generation module screen
            $redirect_url = "../auth/signup.php?name=" . urlencode($student_name) . "&email=" . urlencode($student_email);
            header("Location: " . $redirect_url);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NEXUS UNIVERSITY - Manage Course Requests</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background-color: #7480b5ff; padding-top: 110px; }
        nav { background: #0b1d51; padding: 15px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; }
        nav a { color: white; text-decoration: none; margin: 10px; font-weight: bold;}
        nav a:hover { color: gold; }
        .logo-section { display: flex; align-items: center; gap: 10px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }
        .logo-section h2 { color: white; margin: 0; }
        .title { text-align: center; padding: 20px; }
        .title h1 { color: #0b1d51; font-size: 38px; margin: 0; }
        .container { width: 95%; margin: auto; margin-bottom: 60px; }
        .msg-box { padding: 15px; margin-bottom: 25px; border-radius: 4px; text-align: center; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0px 0px 12px rgba(0,0,0,0.15); border-radius: 6px; overflow: hidden; }
        th { background: #0b1d51; color: white; padding: 14px; font-size: 14px; }
        td { padding: 12px; border: 1px solid #ddd; text-align: center; font-size: 14px; }
        table tr:hover { background: #f9f9f9; }
        .btn-action { text-decoration: none; padding: 6px 14px; color: white; font-weight: bold; border-radius: 4px; display: inline-block; margin: 2px; font-size: 13px; transition: transform 0.1s; }
        .btn-action:active { transform: scale(0.95); }
        .accept { background: #28a745; }
        .accept:hover { background: #218838; }
        .decline { background: #dc3545; }
        .decline:hover { background: #c82333; }
        .create-account { background: #ffc107; color: #212529; }
        .create-account:hover { background: #e0a800; color: #000; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        .badge-pending { background: #ffeeba; color: #856404; }
        .badge-accepted { background: #d4edda; color: #155724; }
        .badge-declined { background: #f8d7da; color: #721c24; }
        .badge-registered { background: #cce5ff; color: #004085; } /* Styled badge layout for Registered tracking */
        footer { background: #0b1d51; color: white; padding: 20px; margin-top: 60px; }
        .footer-container { display: flex; justify-content: space-between; align-items: center; }
        .footer-left p { margin: 5px 0; }
        .map-container iframe { width: 300px; height: 200px; }
        .footer-bottom { text-align: center; margin-top: 15px; border-top: 1px solid #ffffff33; padding-top: 10px; }
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
        <h1>Review Student Registration Requests</h1>
    </section>

    <div class="container">
        
        <?php if(!empty($message)): ?>
            <div class="msg-box" style="<?php echo $msg_style; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Contact Info</th>
                    <th>NIC / Passport</th>
                    <th>Requested Program</th>
                    <th>Highest Qualification</th>
                    <th>Status</th>
                    <th>Actions Decision Matrix</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM request_courses ORDER BY id DESC";
                $result = mysqli_query($conn, $query);

                if($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $current_status = $row['status'];
                ?>
                    <tr>
                        <td style="text-align: left;"><strong><?php echo htmlspecialchars($row['student_name']); ?></strong></td>
                        <td style="text-align: left; font-size: 13px;">
                            📞 <?php echo htmlspecialchars($row['contact_number']); ?><br>
                            ✉️ <?php echo htmlspecialchars($row['email']); ?>
                        </td>
                        <td><code><?php echo htmlspecialchars($row['nic_or_passport']); ?></code></td>
                        <td style="color: #0b1d51; font-weight: bold;"><?php echo htmlspecialchars($row['requested_course']); ?></td>
                        <td><?php echo htmlspecialchars($row['highest_qualification']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($current_status); ?>">
                                <?php echo htmlspecialchars($current_status); ?>
                            </span>
                        </td>
                        <td>
                            <?php if($current_status === 'Pending') { ?>
                                <a href="?id=<?php echo $row['id']; ?>&action=accept" class="btn-action accept" onclick="return confirm('Are you sure you want to ACCEPT this student and send an automated notification email?')">Accept</a>
                                <a href="?id=<?php echo $row['id']; ?>&action=decline" class="btn-action decline" onclick="return confirm('Are you sure you want to DECLINE this student and send a status update email?')">Decline</a>
                            <?php } elseif($current_status === 'Accepted') { ?>
                                <a href="?id=<?php echo $row['id']; ?>&action=register" class="btn-action create-account">👤 Create Account</a>
                            <?php } elseif($current_status === 'Registered') { ?>
                                <span style="color: #28a745; font-weight: bold; font-style: normal;">✓ Account Created</span>
                            <?php } else { ?>
                                <span style="color: #666; font-style: italic;">Declined / Processed</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' style='padding: 30px; font-weight: bold;'>No enrollment requests submitted yet.</td></tr>";
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
                <iframe src="https://www.google.com/maps/embed?pb=..." style="border:0;" loading="lazy"></iframe>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 NEXUS UNIVERSITY | All Rights Reserved</p>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const msgBox = document.querySelector('.msg-box');
            if (msgBox) {
                setTimeout(function() {
                    msgBox.style.transition = "opacity 0.5s ease";
                    msgBox.style.opacity = "0";
                    setTimeout(function() {
                        msgBox.remove();
                    }, 500);
                }, 4000);
            }
        });
    </script>

</body>
</html>