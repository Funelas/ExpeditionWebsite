<?php
session_start();
include('../connections.php'); // Include database connection

if (!isset($_SESSION['account_id'])) {
    header('Location: login.php'); // Redirect if not logged in
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $account_id = $_SESSION['account_id'];
    $product_id = $_POST['product_id'];
    $product_stmt = $connections->prepare("SELECT * FROM products WHERE product_id = ?");
    $product_stmt->bind_param("i", $product_id);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result()->fetch_assoc();
    $name = $product_result["name"];
    $category = $product_result["category"];
    $image = $product_result["image"];
    $price = $product_result["price"];
    $quantity = 1; // Default quantity

    // Check if the item is already in the cart
    $stmt = $connections->prepare("SELECT quantity FROM cart WHERE account_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $account_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
   
    if ($result->num_rows > 0) {
        // If product exists, update quantity
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + 1;
        $update_stmt = $connections->prepare("UPDATE cart SET quantity = ? WHERE account_id = ? AND product_id = ?");
        $update_stmt->bind_param("iii", $new_quantity, $account_id, $product_id);
        $update_stmt->execute();
    } else {
        // Insert new item into the cart
        $insert_stmt = $connections->prepare("INSERT INTO cart (account_id, product_id, product_name, product_img, quantity, price) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("iissii", $account_id, $product_id, $name, $image, $quantity, $price);
        $insert_stmt->execute();
    }

    header("Location: index.php"); // Redirect to cart page
    exit();
}
?>