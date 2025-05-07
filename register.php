<?php 
include ("connections.php");
$name = $address = $email = $password = $cpassword =  "";
$nameErr = $addressErr = $emailErr = $passwordErr = $cpasswordErr = "" ; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = $_POST["name"];
    }
    if (empty($_POST["address"])) {
        $addressErr = "Address is required";
    } else {
        $address = $_POST["address"];
    }
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = $_POST["email"];
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
    }

    if (empty($_POST["cpassword"])) {
        $cpasswordErr = "Confirm Password is required";
    } else {
        $cpassword = $_POST["cpassword"];
    }

    if ($password != $cpassword){
        $passwordErr = $cpasswordErr = "Password does not match.";
    }
   
    if($name && $address && $email && $password && $cpassword && ($password == $cpassword)){
        $check_email = mysqli_query($connections, "SELECT * FROM accounts WHERE email='$email'");
        $check_email_row = mysqli_num_rows($check_email);

        if($check_email_row > 0){
            $emailErr = "Email is already registered!";
        }else{
            $query = mysqli_query($connections, "INSERT INTO accounts (name,address,email,password,account_type) VALUES ('$name','$address','$email','$cpassword', '2')");
            // Echo the SweetAlert2 script here for redirection after success
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Registration Successful!',
                        text: 'You have been registered successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        timer: 3000, // 3 seconds timer
                        timerProgressBar: true, 
                        willClose: () => {
                            window.location.href = 'index.php'; // Redirect after timer
                        }
                    });
                });
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedition - Register</title>
    

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Lexend+Deca&family=Orbitron:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<header></header>
<body class="w-full">
    <div class="w-full bg-[#0d0f0f]"><?php include('nav.php')?></div>
    <div class="[font-family:'Kanit'] bg-[#dfe0dc] w-full flex flex-col md:flex-row md:justify-around md:items-around justify-center items-center">
        <?php include ('header.php'); ?>
        <div class="w-full md:w-[50%] px-4 py-2 flex flex-col justify-center items-center my-3">
            <img class="w-[96px] h-[96px]" src="https://img.icons8.com/fluency-systems-regular/96/add-user-male--v1.png" alt="add-user-male--v1"/>
            <h3 class="text-[#0d0f0f] text-3xl">Member Registration</h3>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="px-4 py-2 bg-[#0d0f0f] rounded-3xl border-4 shadow flex flex-col justify-center items-center w-full">
                <div class="w-full mx-4 my-2 text-xl text-white"><label>Name:</label></div>
                <input class="w-full rounded-full px-4 py-2 text-[#0d0f0f] text-xl" type="text" name="name" value="<?php echo $name; ?>">
                <span class="text-[#dc3545]"><?php echo $nameErr; ?></span>

                <div class="w-full mx-4 my-2 text-xl text-white"><label>Address:</label></div>
                <input class="w-full rounded-full px-4 py-2 text-[#0d0f0f] text-xl" type="text" name="address" value="<?php echo $address; ?>">
                <span class="text-[#dc3545]"><?php echo $addressErr; ?></span>

                <div class="w-full mx-4 my-2 text-xl text-white"><label>Email:</label></div>
                <input class="w-full rounded-full px-4 py-2 text-[#0d0f0f] text-xl" type="text" name="email" value="<?php echo $email; ?>">
                <span class="text-[#dc3545]"><?php echo $emailErr; ?></span>

                <div class="w-full mx-4 my-2 text-xl text-white"><label>Password:</label></div>
                <input class="w-full rounded-full px-4 py-2 text-[#0d0f0f] text-xl" type="password" name="password" value="<?php echo $password; ?>">
                <span class="text-[#dc3545]"><?php echo $passwordErr; ?></span>

                <div class="w-full mx-4 my-2 text-xl text-white"><label>Confirm Password:</label></div>
                <input class="w-full rounded-full px-4 py-2 text-[#0d0f0f] text-xl" type="password" name="cpassword" value="<?php echo $cpassword; ?>">
                <span class="text-[#dc3545]"><?php echo $cpasswordErr; ?></span>

                <input id="submitButton"type="submit" value="Submit" class="rounded-xl bg-[#808080] hover:bg-green-500 hover:text-white w-full px-2 py-1 my-3">
            </form>
        </div>
    </div>
    
</body>
</html>
