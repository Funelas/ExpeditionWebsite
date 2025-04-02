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
                        $passwordErr = "Password is incorrect!";
                    }
                }
            } else{
                $emailErr = "Email is not registered!";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedition</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method= "POST">
        <h3>Email:</h3>
        <input type="text" name = "email" value="<?php echo $email;?>">
        <span><?php echo $emailErr?></span>
        <h3>Password:</h3>
        <input type="password" name = "password" value="<?php echo $password; ?>">
        <span><?php echo $passwordErr?></span><br>
        <input type="submit">
    </form>
    <span>Still not registered?<a href="register.php">Click Here!</a></span>
</body>
</html>