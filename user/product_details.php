<?php 
    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['account_id'])) {
        header('Location: login.php');
        exit();
    }

    // Include database connection
    include('../connections.php');
    include('header.php');
    // Fetch product details based on product_id passed via GET
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];

        // Prepare and execute the SQL query
        $stmt = $connections->prepare("SELECT product_id, name, description, stock, price, image, category FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id); // Bind the product_id to the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the product exists
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        } else {
            // If no product found
            echo "Product not found.";
            exit();
        }

        // Fetch random products for the 'You Might Like' section
        $recommend_stmt = $connections->query("SELECT product_id, name, price, image FROM products WHERE product_id != $product_id ORDER BY RAND() LIMIT 6");
        $recommendations = $recommend_stmt->fetch_all(MYSQLI_ASSOC);
    } else {
        // Redirect if no product_id is passed
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lexend+Deca&family=Orbitron:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 [font-family:'Kanit']">

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Product Details</h1>

        <!-- Flex container for the layout -->
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mt-[50px]">

            <!-- Product Info on the Left -->
            <div class="flex-1">
                <h2 class="text-3xl font-semibold"><?= htmlspecialchars($product['name']) ?></h2>
                <p class="text-lg text-gray-700 mt-2"><?= htmlspecialchars($product['description']) ?></p>
                <p class="text-gray-700 mt-4"><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
                <p class="text-gray-700 mt-2"><strong>Stock:</strong> <?= htmlspecialchars($product['stock']) ?></p>
                <p class="text-gray-900 font-bold text-xl mt-2">₱<?= htmlspecialchars($product['price']) ?></p>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                    <button type="submit" name="add_to_cart" class="mt-6 px-6 py-3 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Add to Cart</button>
                </form>
            </div>

            <!-- Product Image on the Right -->
            <div class="flex-1">
                <img src="<?=htmlspecialchars($product['image']) ?>" alt="Product Image" class="w-full h-auto object-cover rounded shadow-xl">
            </div>

        </div>

        <!-- You Might Like Section -->
        <h2 class="text-xl font-bold mt-8">You Might Like</h2>
        <div class="flex gap-4 overflow-x-auto py-4">
            <?php foreach ($recommendations as $item): ?>
                <div class="flex-none bg-white rounded-lg shadow-md p-4 w-48 hover:shadow-lg transition">
                    <a href="product_details.php?product_id=<?= $item['product_id'] ?>">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="Product Image" class="w-full h-32 object-cover rounded mb-2">
                        <h3 class="text-md font-semibold"><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="text-gray-900 font-bold">₱<?= htmlspecialchars($item['price']) ?></p>
                    </a>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                        <button type="submit" name="add_to_cart" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p class="text-lg">Are you sure you want to logout?</p>
                <div class="flex justify-end space-x-2 mt-4">
                    <button id="confirmLogout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Yes</button>
                    <button id="cancelLogout" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">No</button>
                </div>
            </div>
        </div>

</body>
<script>
    const logoutModal = document.getElementById('logoutModal');
    const confirmLogout = document.getElementById('confirmLogout');
    const cancelLogout = document.getElementById('cancelLogout');
    window.addEventListener('showLogoutModal', () => {
        logoutModal.classList.remove('hidden');
    });

    cancelLogout.addEventListener('click', () => {
        logoutModal.classList.add('hidden');
    });

    confirmLogout.addEventListener('click', () => {
        window.location.href = 'logout.php';
        
    });
</script>
</html>