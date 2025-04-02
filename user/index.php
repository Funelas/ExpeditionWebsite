<?php 
session_start();

// Check if user is logged in
if (!isset($_SESSION['account_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
include('../connections.php');


// Handle "Add to Cart" form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_id = $_SESSION['account_id'];
    $product_id = $_POST['product_id'];

    echo "<h1>here</h1>";
    // Fetch product details
    $product_stmt = $connections->prepare("SELECT * FROM products WHERE product_id = ?");
    $product_stmt->bind_param("i", $product_id);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result()->fetch_assoc();

    if ($product_result) {
        $name = $product_result["name"];
        $category = $product_result["category"];
        $image = $product_result["image"];
        $price = $product_result["price"];
        $stock = $product_result["stock"]; // Fetch the available stock
        $quantity = 1; // Default quantity

        // Check if the item is already in the cart
        $stmt = $connections->prepare("SELECT quantity FROM cart WHERE account_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $account_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If product exists, get current quantity
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + 1;

            // Check if the new quantity exceeds the available stock
            if ($new_quantity > $stock) {
                // Pass the error message to the frontend using a session variable
                $_SESSION['error_message'] = "Cannot add more than available stock!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                // Update quantity in the cart
                $update_stmt = $connections->prepare("UPDATE cart SET quantity = ? WHERE account_id = ? AND product_id = ?");
                $update_stmt->bind_param("iii", $new_quantity, $account_id, $product_id);
                $update_stmt->execute();
                // Redirect to avoid form resubmission issues
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            // Check if the requested quantity exceeds available stock
            if ($quantity > $stock) {
                $_SESSION['error_message'] = "Cannot add more than available stock!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                // Insert new item into the cart
                $insert_stmt = $connections->prepare("INSERT INTO cart (account_id, product_id, product_name, product_img, quantity, price) VALUES (?, ?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("iissii", $account_id, $product_id, $name, $image, $quantity, $price);
                $insert_stmt->execute();
                // Redirect to avoid form resubmission issues
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }

        
    }
}

// Fetch all products grouped by category
$categories = ['Mountain', 'Road', 'Gravel', 'Accessory'];
$products_by_category = [];
foreach ($categories as $category) {
    $stmt = $connections->prepare("SELECT product_id, name, stock, price, image FROM products WHERE category = ? AND stock != 0");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $products_by_category[$category] = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedition Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body class="bg-gray-100">
<?php include('header.php'); ?>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Welcome to Expedition Shop</h1>

        <!-- Category Filter Radio Buttons -->
        <div class="mb-4">
            <label><input type="radio" name="category" value="All" checked onclick="filterCategory('All')"> All</label>
            <?php foreach ($categories as $category): ?>
                <label><input type="radio" name="category" value="<?= $category ?>" onclick="filterCategory('<?= $category ?>')"> <?= $category ?></label>
            <?php endforeach; ?>
        </div>

        <!-- Products by Category -->
        <?php foreach ($products_by_category as $category => $products): ?>
            <div class="category-section" data-category="<?= $category ?>">
                <h2 class="text-xl font-semibold my-2"><?= $category ?> Bikes</h2>
                <div class="flex flex-wrap gap-4">
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-lg shadow-md p-4 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 hover:shadow-xl transition">
                            <a href="product_details.php?product_id=<?= $product['product_id'] ?>">
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="w-full h-48 object-cover rounded mb-2 cursor-pointer">
                                <h2 class="text-lg font-semibold"> <?= htmlspecialchars($product['name']) ?> </h2>
                                <p class="text-gray-700">Stock: <?= htmlspecialchars($product['stock']) ?></p>
                                <p class="text-gray-900 font-bold">₱<?= htmlspecialchars($product['price']) ?></p>
                            </a>
                            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                <button type="submit" name="add_to_cart" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Add to Cart</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (isset($_SESSION['error_message'])): ?>
    <div id="stockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Error</h2>
            <p class="text-gray-700 mb-4"><?= $_SESSION['error_message']; ?></p>
            <button onclick="closeModal()" class="px-4 py-2 bg-red-500 text-white rounded">Close</button>
        </div>
    </div>
    <script>
        function closeModal() {
            document.getElementById("stockModal").style.display = "none";
        }
    </script>
    <?php unset($_SESSION['error_message']); endif; ?>
    <!-- Logout Modal -->
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
<script src= "script.js"></script>
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
