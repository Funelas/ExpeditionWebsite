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
            echo "<script language = 'javascript'>alert('New record has been inserted!')</script>";
            echo "<script>window.location.href='index.php'</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedition - Register</title>
</head>
<body>
<form method= "POST" action= "<?php htmlspecialchars("PHP_SELF");?>">
    <span>Name: <input type="text" name = "name" value = "<?php echo $name; ?>"></span> <br>
    <span class= "error"> <?php echo $nameErr ;?></span><br>
    <span>Address: <input type="text" name = "address" value = "<?php echo $address ; ?>"></span> <br>
    <span class= "error"> <?php echo $addressErr ;?></span><br>
    <span>Email: <input type="text" name = "email" value = "<?php echo $email;?>"> </span><br>
    <span class= "error"> <?php echo $emailErr ;?></span><br>
    <span>Password: <input type="password" name = "password" value = "<?php echo $password;?>"></span> <br>
    <span class= "error"> <?php echo $passwordErr ;?></span><br>
    <span>Confirm Password: <input type="password" name = "cpassword" value = "<?php echo $cpassword;?>"></span> <br>
    <span class= "error"> <?php echo $cpasswordErr ;?></span><br>
    <input type="submit" value= "Submit"> <br>
 </form>
 
</body>
</html>