<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lexend+Deca&family=Orbitron:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 bg-gray-100 h-full [font-family:'Kanit'] flex flex-col justify-center items-center w-full">
<?php include('header.php'); ?>
<?php
    session_start();
    // Include database connection
    include('../connections.php');

    // Get the user's account ID from the session
    $account_id = $_SESSION['account_id'] ?? null;

    // Handle checkout process
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $cart_query = $connections->prepare("SELECT product_id, quantity FROM cart WHERE account_id = ?");
        $cart_query->bind_param("i", $account_id);
        $cart_query->execute();
        $cart_result = $cart_query->get_result();

        while ($cart_item = $cart_result->fetch_assoc()) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];

            // Update product stock
            $update_stock = $connections->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
            $update_stock->bind_param("ii", $quantity, $product_id);
            $update_stock->execute();
        }

        // Clear the cart after checkout
        $clear_cart = $connections->prepare("DELETE FROM cart WHERE account_id = ?");
        $clear_cart->bind_param("i", $account_id);
        $clear_cart->execute();

        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Checkout Successful!',
                        text: 'You have bought items successfully.',
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

    // Fetch cart items and calculate the total amount
    $cart_query = $connections->prepare("SELECT product_id, product_name, price, quantity, product_img FROM cart WHERE account_id = ?");
    $cart_query->bind_param("i", $account_id);
    $cart_query->execute();
    $cart_result = $cart_query->get_result();
    $total_amount = 0;
?>
<h2 class="text-5xl font-semibold mb-4 mt-[100px]">Checkout</h2>
<div class="p-6 bg-gray-100 min-h-screen ">
    <?php if ($cart_result->num_rows > 0): ?>
        <ul class="space-y-4">
            <?php while ($cart_item = $cart_result->fetch_assoc()): ?>
                <?php $item_total = $cart_item['price'] * $cart_item['quantity']; ?>
                <?php $total_amount += $item_total; ?>
                <li class="flex items-center space-x-4 p-4 bg-white rounded shadow">
                    <img src="<?= htmlspecialchars($cart_item['product_img']) ?>" alt="Product Image" class="w-24 h-24 object-cover">
                    <div>
                        <p class="font-semibold"><?= htmlspecialchars($cart_item['product_name']) ?></p>
                        <p>₱<?= htmlspecialchars($cart_item['price']) ?> x <?= htmlspecialchars($cart_item['quantity']) ?></p>
                        <p>Total: ₱<?= $item_total ?></p>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
        <div class="mt-6 p-4 bg-white rounded shadow">
            <p class="text-xl">Total Amount: ₱<?= $total_amount ?></p>
            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class= "flex justify-center items-center">
                <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded hover:bg-blue-600 text-xl w-full">Checkout</button>
            </form>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>
</body>
</html>
