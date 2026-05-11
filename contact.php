
<?php

include 'config/db.php';

if(isset($_POST['Send'])){

    $contact_name = $_POST['contact_name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $query = "INSERT INTO contacts(contact_name, email, message)
              VALUES('$contact_name', '$email', '$message')";

    $result = mysqli_query($conn, $query);

    if($result){
        echo "<script>alert('Message Sent Successfully');</script>";
    }
    else{
        echo "<script>alert('Failed to Send Message');</script>";
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>NEXUS UNIVERSITY - Contact Us</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg, #0b1d51, #1e3c72);
        }

        .contact-container{
            width:380px;
            background:white;
            padding:40px;
            border-radius:15px;
            box-shadow:0px 8px 25px rgba(0,0,0,0.3);
            text-align:center;
        }

        .contact-container h1{
            color:#0b1d51;
            margin-bottom:10px;
        }

        .contact-container p{
            color:gray;
            margin-bottom:30px;
        }

        .input-box{
            margin-bottom:20px;
            text-align:left;
        }

        .input-box label{
            display:block;
            margin-bottom:8px;
            color:#333;
            font-weight:bold;
        }

        .input-box input{
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:8px;
            outline:none;
            transition:0.3s;
        }

        .input-box input:focus{
            border-color:#0b1d51;
            box-shadow:0px 0px 8px rgba(11,29,81,0.3);
        }

        textarea{
            width:100%;
            min-height:140px;
            padding:15px;
            border:2px solid #dcdcdc;
            border-radius:12px;
            font-size:16px;
            font-family:Arial, sans-serif;
            background:#f9f9f9;
            color:#333;
            outline:none;
            resize:vertical;
            transition:0.3s ease;
            box-sizing:border-box;
        }

        textarea:focus{
            border-color:#0b1d51;
            background:white;
            box-shadow:0px 0px 8px rgba(11,29,81,0.3);
        }

        textarea::placeholder{
            color:#888;
            font-size:15px;
        }

        .send-btn{
            width:100%;
            padding:12px;
            border:none;
            border-radius:8px;
            background:#0b1d51;
            color:white;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
        }

        .send-btn:hover{
            background:gold;
            color:#0b1d51;
            font-weight:bold;
        }

        .error{
            background:#ffe5e5;
            color:red;
            padding:10px;
            border-radius:8px;
            margin-bottom:15px;
        }

        

        

    </style>
</head>

<body>

    <div class="contact-container">

        <h1>NEXUS UNIVERSITY</h1>
        <p>Please Contact Us for More Info</p>

        <?php if(!empty($error)) : ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-box">
                <label>Full Name</label>
                <input type="contact_name" name="contact_name" placeholder="Enter your Full Name" required>
            </div>

            <div class="input-box">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your Email" required>
            </div>

            <div class="input-box">
                <label>Message</label>

                <textarea 
                    name="message" 
                    placeholder="Enter your Message here" 
                    rows="5" 
                    required>
                </textarea>

            </div>

            <button type="submit" name="Send" class="send-btn">
                Send
            </button>

        </form>

        

    </div>

</body>
</html>