<?php
// Include database connection
include('../connections.php');
echo print_r($_FILES);
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_id = $_POST['table_id'];

    if ($table_id == 'products') {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];

        // Handle the image upload
        if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] === UPLOAD_ERR_OK) {
            $image_path = '../images/' . basename($_FILES['product_img']['name']);
            move_uploaded_file($_FILES['product_img']['tmp_name'], $image_path);
        } else {
            $image_path = ''; // or some default image
        }

        // Insert the new product into the database
        $insert_query = "INSERT INTO products (name, category, price, stock, description, image) VALUES ('$name', '$category', '$price', '$stock', '$description', '$image_path')";
        if ($connections->query($insert_query)) {
            $message = 'Product added successfully!';
        } else {
            $message = 'Error adding product: ' . $connections->error;
        }
    } elseif ($table_id == 'accounts') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $password = $_POST['password'];
        $account_type = $_POST['account_type'];

        // Insert the new account into the database
        $insert_query = "INSERT INTO accounts (name, email, address, password, account_type) VALUES ('$name', '$email', '$address', '$password', '$account_type')";
        if ($connections->query($insert_query)) {
            $message = 'Account added successfully!';
        } else {
            $message = 'Error adding account: ' . $connections->error;
        }
    }

    // Redirect back to the records page with a success message
    header("Location: records.php?table_id=$table_id");
    exit();
}
?>
