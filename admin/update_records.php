<?php
// Include database connection
include('../connections.php');
echo print_r($_POST);
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the table_id from the POST data
    $table_id = $_POST['table_id'] ?? '';
    $id = $_POST['id'];
    // Handle product updates
    if ($table_id === 'products') {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        // Handle the image upload
        if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] === UPLOAD_ERR_OK) {
            $image_path = '../images/' . basename($_FILES['product_img']['name']);
            move_uploaded_file($_FILES['product_img']['tmp_name'], $image_path);
        } else {
            $image_path = $_POST['current_img'];
        }

        // Update the product in the database
        $update_query = "UPDATE products SET name='$name', category='$category', description='$description', price='$price', image='$image_path' WHERE product_id='$id'";
        if ($connections->query($update_query)) {
            $message = 'Product updated successfully!';
            echo $message;
        } else {
            $message = 'Error updating product: ' . $connections->error;
        }
    }
    if ($table_id === 'accounts') {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $account_type = $_POST['account_type'];

        // Update the product in the database
        $update_query = "UPDATE accounts SET name='$name', address='$address', email='$email', password='$password', account_type='$account_type' WHERE account_id='$id'";
        if ($connections->query($update_query)) {
            $message = 'Product updated successfully!';
            echo $message;
        } else {
            $message = 'Error updating product: ' . $connections->error;
        }
    }

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Record</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <?php if (isset($_GET['message'])): ?>
        <div class="fixed top-0 left-0 right-0 bg-green-500 text-white text-center py-2">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
        <script>
            setTimeout(function() {
                const messageBox = document.querySelector('.fixed');
                if (messageBox) {
                    messageBox.style.display = 'none';
                }
            }, 3000);
        </script>
    <?php endif; ?>
</body>
</html>

<?php 

    // Redirect back to the records page
    header("Location: records.php?table_id=$table_id");
    exit();
?>