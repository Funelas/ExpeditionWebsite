<?php 
    session_start();
    $email = $password = "";
    $emailErr = $passwordErr = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty($_POST["email"])){
            $emailErr = "Email is required!";
        } else{
            $email = $_POST["email"];
        }
    
        if(empty($_POST["password"])){
            $passwordErr = "Password is required!";
        } else{
           $password = $_POST["password"];
        }
        
        if ($email && $password){
            include("connections.php");
            $check_email = mysqli_query($connections, "SELECT * FROM accounts WHERE email = '$email'");
            $check_email_row = mysqli_num_rows($check_email);
            if($check_email_row > 0){
                while($row = mysqli_fetch_assoc($check_email)){
                    $db_password = $row["password"];
                    $db_account_type = $row["account_type"];
                    $db_id = $row["account_id"];
                    if ($password == $db_password){
                        if ($db_account_type == "1"){
                            $_SESSION["account_id"] = $db_id;
                            echo "<script>window.location.href='admin'</script>";
                        }else{
                            $_SESSION["account_id"] = $db_id;
                            echo "<script>window.location.href='user'</script>";
                        }
                    }else{
                        $passwordErr = "Account not found.";
                    }
                }
            } else{
                $emailErr = "Account not found.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en" class= "h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedition</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lexend+Deca&family=Orbitron:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class= "[font-family:'Kanit'] bg-[#dfe0dc] w-full h-full flex flex-col lg:flex-row lg:justify-around lg:items-around justify-center items-center text-[#dfe0dc]">
    <?php include ('header.php');?>
    <div class="w-full md:w-[50%] px-4 py-2 flex flex-col justify-center items-center">
        <img class="w-[96px] w-[96px]" src="https://img.icons8.com/fluency-systems-regular/96/user-male-circle--v1.png" alt="user-male-circle--v1"/>
        <h3 class="text-[#0d0f0f] text-3xl">Member Login</h3>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method= "POST" class= "px-4 py-2 px-4 py-2 bg-[#0d0f0f] rounded-3xl border-4 shadow flex flex-col justify-center items-center md:w-[50%] lg:w-[50%]">
            <div class="flex flex-end w-full mx-4 my-2 text-xl"><h3>Email:</h3></div>
            <input class= "w-full rounded-full px-4 py-2 text-[#0d0f0f] text-xl" type="text" name = "email" value="<?php echo $email;?>">
            <span class= 'text-[#dc3545]'><?php echo $emailErr?></span>
            <div class="flex flex-end w-full mx-4 my-2 text-xl"><h3>Password:</h3></div>
            <input class= "w-full rounded-full px-4 py-2 text-[#0d0f0f] text-xl" type="password" name = "password" value="<?php echo $password; ?>">
            <span class= 'text-[#dc3545]'><?php echo $passwordErr?></span><br>
            <input type="submit" value= "Submit" class="rounded-xl bg-[#808080] hover:bg-green-500 hover:text-white w-full px-2 py-1 mb-3">
            <span>Still not registered?<a href="register.php" class="text-[#dc3545] hover:text-green-500"> Click Here!</a></span>
        </form>
        
    </div>
    
</body>
</html>