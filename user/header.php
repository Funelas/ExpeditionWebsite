<?php
    // Include database connection
    include('../connections.php');

    // Get the user's account ID from the session
    $account_id = $_SESSION['account_id'] ?? null;

    // Fetch the total number of items in the cart for the logged-in user
    $total_items_query = $connections->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE account_id = ?");
    $total_items_query->bind_param("i", $account_id);
    $total_items_query->execute();
    $total_items_result = $total_items_query->get_result();
    $total_items = $total_items_result->fetch_assoc()['total_items'] ?? 0;

    // Fetch cart items for the sidebar
    $cart_query = $connections->prepare("SELECT product_name, price, quantity, product_img FROM cart WHERE account_id = ?");
    $cart_query->bind_param("i", $account_id);
    $cart_query->execute();
    $cart_result = $cart_query->get_result();
?>

<div class="fixed top-0 left-0 right-0 bg-blue-500 text-white p-4 flex justify-between items-center shadow-md z-50">
    <h1 class="text-xl font-semibold">Expedition Shop</h1>
    <!-- Search Bar -->
    <form action="search_results.php" method="GET" class="flex items-center space-x-2">
        <input type="text" name="query" placeholder="Search..." class="p-2 rounded text-black">
        <button type="submit" class="bg-green-500 p-2 rounded hover:bg-green-600">Search</button>
    </form>
    <!-- View Cart Button with Total Item Count -->
    <button id="viewCartBtn" class="flex items-center space-x-2 bg-blue-700 p-2 rounded hover:bg-blue-800">
        <span>View Cart (<?= $total_items ?>)</span>
    </button>
    <button id="logoutBtn" class="bg-red-500 p-2 rounded hover:bg-red-600">Logout</button>
</div>

<!-- Sidebar -->
<div id="cartSidebar" class="fixed inset-0 bg-black bg-opacity-50 z-40 flex justify-end translate-x-full hidden">
    <div class="bg-white w-1/3 h-full p-4 overflow-y-auto">
        <h2 class="text-lg font-semibold mb-4">Your Cart</h2>
        <!-- Cart Items -->
        <?php if ($cart_result->num_rows > 0): ?>
            <ul>
                <?php while ($cart_item = $cart_result->fetch_assoc()): ?>
                    <li class="flex items-center space-x-2 mb-4">
                        <img src="<?= htmlspecialchars($cart_item['product_img']) ?>" alt="Product Image" class="w-16 h-16 object-cover">
                        <div>
                            <p class="font-semibold"><?= htmlspecialchars($cart_item['product_name']) ?></p>
                            <p>₱<?= htmlspecialchars($cart_item['price']) ?> x <?= htmlspecialchars($cart_item['quantity']) ?></p>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
        <button class="mt-4 bg-blue-500 text-white p-2 rounded hover:bg-blue-600" onclick="window.location.href='checkout.php'">Checkout</button>
        <button id="closeCartBtn" class="mt-2 p-2 bg-red-500 text-white rounded hover:bg-red-600">Close</button>
    </div>
</div>

<script src="cart.js"></script>
<script>
    const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn.addEventListener('click', () => {
        const event = new CustomEvent('showLogoutModal');
        window.dispatchEvent(event);
    });
</script>
