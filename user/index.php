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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_id = $_SESSION['account_id'];
    $product_id = $_POST['product_id'];
    $action = $_POST['action'] ?? 'add'; // Default action is "add"

    // Fetch product details including stock
    $product_stmt = $connections->prepare("SELECT * FROM products WHERE product_id = ?");
    $product_stmt->bind_param("i", $product_id);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result()->fetch_assoc();

    if ($product_result) {
        $name = $product_result["name"];
        $category = $product_result["category"];
        $image = $product_result["image"];
        $price = $product_result["price"];
        $stock = $product_result["stock"]; // Available stock
        $quantity = 1; // Default quantity for adding new item

        // Check if the item is already in the cart
        $stmt = $connections->prepare("SELECT quantity FROM cart WHERE account_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $account_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Determine the new quantity based on the action
        if ($result->num_rows > 0) {
            // If product exists in cart, get the current quantity
            $row = $result->fetch_assoc();
            $current_quantity = $row['quantity'];

            // Increase or decrease quantity
            if ($action == 'increase') {
                $new_quantity = $current_quantity + 1;
            } elseif ($action == 'decrease') {
                $new_quantity = $current_quantity - 1;
            } else {
                $new_quantity = $current_quantity + 1;
            }

            // Handle stock limitation (for increase and add)
            if (($action == 'increase' || $action == 'add') && $new_quantity > $stock) {
                $_SESSION['error_message'] = "Cannot add more than available stock!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }

            // Update or remove the item based on the new quantity
            if ($new_quantity > 0) {
                $update_stmt = $connections->prepare("UPDATE cart SET quantity = ? WHERE account_id = ? AND product_id = ?");
                $update_stmt->bind_param("iii", $new_quantity, $account_id, $product_id);
                $update_stmt->execute();
            } else {
                // Remove the item if quantity is 0 or less
                $delete_stmt = $connections->prepare("DELETE FROM cart WHERE account_id = ? AND product_id = ?");
                $delete_stmt->bind_param("ii", $account_id, $product_id);
                $delete_stmt->execute();
            }
        } else {
            // Adding a new product to the cart
            if ($quantity > $stock) {
                $_SESSION['error_message'] = "Cannot add more than available stock!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                // Insert new item into the cart
                $insert_stmt = $connections->prepare("INSERT INTO cart (account_id, product_id, product_name, product_img, quantity, price) VALUES (?, ?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("iissii", $account_id, $product_id, $name, $image, $quantity, $price);
                $insert_stmt->execute();
            }
        }
    }

    // Redirect to the same page to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
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
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lexend+Deca&family=Orbitron:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
</head>

<body class="bg-gray-100 h-full [font-family:'Kanit'] flex flex-col justify-center items-center">
    
    <?php include('header.php'); ?>
    <div class="container mx-auto p-4 mt-[100px] flex flex-col justify-center items-center">
        <h1 class="text-2xl font-bold mb-4">Welcome to Expedition Shop</h1>

        <!-- Category Filter Radio Buttons -->
        <div class="mb-4 flex flex-col md:flex-row justify-center items-center">
            <!-- All Category (Pre-selected) -->
            <input type="radio" id="all" name="category" value="All" class="hidden" onclick="filterCategory('All'); highlightSelectedLabel(this);" />
            <label for="all" class="py-2 px-4 cursor-pointer text-xl rounded-full transition-all" id="label-all">All</label>

            <!-- Dynamically Generated Categories -->
            <?php foreach ($categories as $category): ?>
                
                <input type="radio" id="<?= $category ?>" name="category" value="<?= $category ?>" class="hidden" onclick="filterCategory('<?= $category ?>'); highlightSelectedLabel(this);" />
                <label for="<?= $category ?>" class="py-2 px-4 cursor-pointer text-xl rounded-full transition-all" id="label-<?= $category ?>"><?= $category ?></label>
            <?php endforeach; ?>
        </div>
    
        <!--  -->
        <!-- Products by Category -->
        <?php foreach ($products_by_category as $category => $products): ?>
            <?php 
                    // Define the icons for each category (selected and unselected)
                    $icons = [
                        "Mountain" => '<img class="w-[50px] h-[50px]" src="https://img.icons8.com/fluency-systems-regular/96/mountain.png" alt="mountain"/>',
                        "Road" => '<img class="w-[50px] h-[50px]" src="https://img.icons8.com/fluency-systems-regular/96/road.png" alt="road"/>',
                        "Gravel" => '<img class="w-[50px] h-[50px]" src="https://img.icons8.com/fluency-systems-regular/96/stones.png" alt="stones"/>',
                        "Accessory" => '<img class="w-[50px] h-[50px]" src="https://img.icons8.com/fluency-systems-regular/96/settings-3.png" alt="settings-3"/>'
                    ];
                    $iconSvg = $icons[$category] ?? '';
                ?>
                
            <div class="category-section flex flex-col justify-center items-center mt-[30px]" data-category="<?= $category ?>">
                <div class="flex flex-col justify-center items-center">
                    <?= $iconSvg ?>
                    <h2 class="text-2xl font-semibold my-2"><?= $category ?> Bikes</h2>
                </div>
                <div class="flex flex-wrap gap-4 justify-center items-center">
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-lg shadow-md p-4 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 hover:shadow-xl transition">
                            <a href="product_details.php?product_id=<?= $product['product_id'] ?>">
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="min-w-full h-48 object-cover rounded mb-2 cursor-pointer">
                                <h2 class="text-lg font-semibold"> <?= htmlspecialchars($product['name']) ?> </h2>
                                <p class="text-gray-700">Stock: <?= htmlspecialchars($product['stock']) ?></p>
                                <p class="text-gray-900 font-bold">₱<?= htmlspecialchars($product['price']) ?></p>
                            </a>
                            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="w-full flex justify-center items-center">
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
<script>
    // Function to highlight the label of the selected radio button
    function highlightSelectedLabel(radioButton) {
        // Get all labels
        const labels = document.querySelectorAll('label');

        // Remove the 'bg-blue-500' class from all labels
        labels.forEach(label => {
            label.classList.remove('bg-[#0d0f0f]', 'text-white');
        });

        // Add the 'bg-blue-500' and 'text-white' classes to the clicked label
        const selectedLabel = document.querySelector(`label[for="${radioButton.id}"]`);
        selectedLabel.classList.add('bg-[#0d0f0f]', 'text-white');
    }
</script>
</html>
