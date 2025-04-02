<?php
// Include database connection
include('../connections.php');

// Get the table_id from the URL
$table_id = $_GET['table_id'] ?? '';
$search = $_GET['search'] ?? '';

if ($table_id == 'products') {
    $query = "SELECT * FROM products";
    if ($search) {
        $query .= " WHERE product_id LIKE '%$search%' OR name LIKE '%$search%' OR description LIKE '%$search%' OR price LIKE '%$search%' OR category LIKE '%$search%'";
    }
} elseif ($table_id == 'accounts') {
    $query = "SELECT * FROM accounts";
    if ($search) {
        $query .= " WHERE account_id LIKE '%$search%' OR name LIKE '%$search%' OR email LIKE '%$search%' OR address LIKE '%$search%' OR password LIKE '%$search%' OR account_type LIKE '%$search%'";
    }
} elseif ($table_id == 'orders') {
    $query = "SELECT * FROM orders";
    if ($search) {
        $query .= " WHERE order_date LIKE '%$search%' OR total_amount LIKE '%$search%' OR status LIKE '%$search%'";
    }
} else {
    $query = ""; // Invalid table_id
}


// Execute the query
$result = $connections->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage <?= ucfirst($table_id) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <h2 class="text-2xl font-semibold mb-4 text-center">Manage <?= ucfirst($table_id) ?></h2>
    <div class="mb-4">
        <form method="GET" action="records.php">
            <input type="hidden" name="table_id" value="<?= $table_id ?>">
            <input type="text" name="search" placeholder="Search..." class="p-2 border rounded" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Search</button>
        </form>
    </div>
    <h3>Add a Record</h3>
    <button class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600" onclick="document.getElementById('addModal').classList.remove('hidden')">Add New Record</button>
    <?php if ($result && $result->num_rows > 0): ?>
    <table class="min-w-full table-auto bg-white border border-gray-300 shadow-md rounded">
        <thead>
            <tr class="bg-gray-200">
                <?php if ($table_id === 'products'): ?>
                    <th class="px-4 py-2 text-left border">Product I.D</th>
                    <th class="px-4 py-2 text-left border">Name</th>
                    <th class="px-4 py-2 text-left border">Stock</th>
                    <th class="px-4 py-2 text-left border">Description</th>
                    <th class="px-4 py-2 text-left border">Price</th>
                    <th class="px-4 py-2 text-left border">Category</th>
                    <th class="px-4 py-2 text-left border">Image Link</th>
                    <th class="px-4 py-2 text-left border">Options</th>
                <?php elseif ($table_id === 'accounts'): ?>
                    <th class="px-4 py-2 text-left border">Account I.D</th>
                    <th class="px-4 py-2 text-left border">Name</th>
                    <th class="px-4 py-2 text-left border">Email</th>
                    <th class="px-4 py-2 text-left border">Address</th>
                    <th class="px-4 py-2 text-left border">Password</th>
                    <th class="px-4 py-2 text-left border">Account Type</th>
                    <th class="px-4 py-2 text-left border">Options</th>
                <?php elseif ($table_id === 'orders'): ?>
                    <th class="px-4 py-2 text-left border">Order Date</th>
                    <th class="px-4 py-2 text-left border">Total Amount</th>
                    <th class="px-4 py-2 text-left border">Status</th>
                    <th class="px-4 py-2 text-left border">Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="hover:bg-gray-100">
                    <?php if ($table_id === 'products'): ?>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['product_id']) ?></td>
                        <td class="px-4 py-2 border [max-width:150px]" ><?= htmlspecialchars($row['name']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['stock']) ?></td>
                        <td class="px-4 py-2 border [max-width:200px]"><?= htmlspecialchars($row['description']) ?></td>
                        <td class="px-4 py-2 border">₱<?= htmlspecialchars($row['price']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['category']) ?></td>
                        <td class="px-4 py-2 border [max-width:300px]">
                            <img src="<?= htmlspecialchars($row['image']) ?>" alt="Product Image" class="w-[100%] object-cover">
                        </td>
                        <td class="px-4 py-2 border">
                            <button class="bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600" onclick="document.getElementById('editForm<?= $row['product_id'] ?>').classList.remove('hidden')">Edit</button>
                            <button class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600" onclick="showDeleteModal(<?= $row['product_id'] ?>, '<?= $table_id ?>')">Delete</button>
                        </td>
                    <?php elseif ($table_id === 'accounts'): ?>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['account_id']) ?></td>
                        <td class="px-4 py-2 border [max-width:150px]" ><?= htmlspecialchars($row['name']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['email']) ?></td>
                        <td class="px-4 py-2 border [max-width:200px]"><?= htmlspecialchars($row['address']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['password']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['account_type']) ?></td>
                        <td class="px-4 py-2 border">
                            <button class="bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600" onclick="document.getElementById('editForm<?= $row['account_id'] ?>').classList.remove('hidden')">Edit</button>
                            <button class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600" onclick="showDeleteModal(<?= $row['account_id'] ?>, '<?= $table_id ?>')">Delete</button>
                        </td>
                    <?php elseif ($table_id === 'orders'): ?>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['order_date']) ?></td>
                        <td class="px-4 py-2 border">₱<?= htmlspecialchars($row['total_amount']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($row['status']) ?></td>
                        <td class="px-4 py-2 border">
                            <button class="bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600">View</button>
                            <button class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600">Delete</button>
                        </td>
                    <?php endif; ?>
                </tr>

                <!-- Edit Form (Hidden by default) -->
                <?php if ($table_id === 'products'): ?>
                    <tr id="editForm<?= $row['product_id'] ?>" class="hidden">
                        <td colspan="12" class="px-4 py-4 border bg-gray-50">
                            <form action="update_records.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name= "table_id" value= "<?php echo $table_id?>">
                                <input type="hidden" name="id" value="<?= $row['product_id'] ?>">
                                <input type="hidden" name="current_img" value="<?= $row['image'] ?>">

                                <label for="name">Product Name:</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="p-2 border rounded mb-4" required><br>

                                <label for="category">Category:</label>
                                <input type="text" name="category" value="<?= htmlspecialchars($row['category']) ?>" class="p-2 border rounded mb-4" required><br>

                                <label for="description">Description:</label>
                                <textarea name="description" class="w-full"><?php echo htmlspecialchars(($row['description']))?></textarea><br>

                                <label for="price">Price:</label>
                                <input type="text" name="price" value="<?= htmlspecialchars($row['price']) ?>" class="p-2 border rounded mb-4" required><br>

                                <label for="product_img">Product Image:</label><br>
                                <input type="file" name="product_img" accept="image/*" class="p-2 border rounded mb-4"><br>

                                <button type="submit" class="bg-green-500 text-white py-1 px-4 rounded hover:bg-green-600">Update</button>
                                <button type="button" class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600" onclick="document.getElementById('editForm<?= $row['product_id'] ?>').classList.add('hidden')">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php elseif ($table_id === 'accounts'): ?>
                    <tr id="editForm<?= $row['account_id'] ?>" class="hidden">
                        <td colspan="12" class="px-4 py-4 border bg-gray-50">
                            <form action="update_records.php" method="POST">
                                <input type="hidden" name= "table_id" value= "<?php echo $table_id?>">
                                <input type="hidden" name="id" value="<?= $row['account_id'] ?>">

                                <label for="name">Name:</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="p-2 border rounded mb-4" required><br>

                                <label for="address">Address:</label>
                                <input type="text" name="address" value="<?= htmlspecialchars($row['address']) ?>" class="p-2 border rounded mb-4" required><br>

                                <label for="email">Email:</label>
                                <textarea name="email" class="w-full"><?php echo htmlspecialchars(($row['email']))?></textarea><br>

                                <label for="password">Price:</label>
                                <input type="text" name="password" value="<?= htmlspecialchars($row['password']) ?>" class="p-2 border rounded mb-4" required><br>

                                <label for="account_type">Account Type:</label><br>
                                <input type="text" name="account_type" value="<?= htmlspecialchars($row['account_type']) ?>" class="p-2 border rounded mb-4" required><br>

                                <button type="submit" class="bg-green-500 text-white py-1 px-4 rounded hover:bg-green-600">Update</button>
                                <button type="button" class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600" onclick="document.getElementById('editForm<?= $row['account_id'] ?>').classList.add('hidden')">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div id="deleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded shadow-md text-center">
            <p class="mb-4" id="messageHolder"></p>
            <button id="confirmDelete" class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600">Delete</button>
            <button onclick="hideDeleteModal()" class="bg-gray-500 text-white py-1 px-4 rounded hover:bg-gray-600">Cancel</button>
        </div>
    </div>
    <!-- Modal -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-lg w-[400px]">
            <h3 class="text-xl font-semibold mb-4">Add New <?= ucfirst($table_id) ?></h3>
            <form action="add_record.php" method="POST" enctype="<?php if($table_id == "products"){echo "multipart/form-data";}else{"application/x-www-form-urlencoded";}?>">
                <input type="hidden" name="table_id" value="<?= $table_id ?>">
                
                <!-- Product-specific fields (for example) -->
                <?php if ($table_id === 'products'): ?>
                    <label for="name">Product Name:</label>
                    <input type="text" name="name" class="p-2 border rounded mb-4" required><br>

                    <label for="category">Category:</label>
                    <input type="text" name="category" class="p-2 border rounded mb-4" required><br>

                    <label for="price">Price:</label>
                    <input type="text" name="price" class="p-2 border rounded mb-4" required><br>
                    
                    <label for="stock">Stock:</label>
                    <input type="text" name="stock" class="p-2 border rounded mb-4" required><br>

                    <label for="description">Description:</label>
                    <textarea name="description" class= "w-full h-1/4"></textarea>

                    <label for="product_img">Product Image:</label><br>
                    <input type="file" name="product_img" accept="image/*" class="p-2 border rounded mb-4"><br>
                <?php elseif ($table_id === 'accounts'): ?>
                    <label for="name">Name:</label>
                    <input type="text" name="name" class="p-2 border rounded mb-4" required><br>

                    <label for="email">Email:</label>
                    <input type="email" name="email" class="p-2 border rounded mb-4" required><br>

                    <label for="address">Address:</label>
                    <input type="text" name="address" class="p-2 border rounded mb-4" required><br>

                    <label for="password">Password:</label>
                    <input type="password" name="password" class="p-2 border rounded mb-4" required><br>

                    <label for="account_type">Account Type:</label>
                    <input type="text" name="account_type" class="p-2 border rounded mb-4" required><br>
                <?php endif; ?>

                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Add Record</button>
                <button type="button" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600" onclick="document.getElementById('addModal').classList.add('hidden')">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function showDeleteModal(id,table_id) {
            const message = `Are you sure you want to delete Account I.D #${id}?`
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('confirmDelete').onclick = function() {
                window.location.href = `delete_record.php?table_id=${table_id}&id=${id}`;
            };
            document.getElementById("messageHolder").textContent = message;
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
<?php else: ?>
    <p>No records found.</p>
<?php endif; ?>


</body>
</html>
