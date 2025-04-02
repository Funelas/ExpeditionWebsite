<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<?php
    // Include database connection
    include('../connections.php');

    // Get the search query from the GET request
    $search_query = $_GET['query'] ?? '';

    // Fetch matching products from the database
    $search_sql = "SELECT product_id, name, description, category, image FROM products WHERE name LIKE ? OR description LIKE ? OR category LIKE ?";
    $stmt = $connections->prepare($search_sql);
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<div class="p-6 bg-gray-100 min-h-screen">
    <h2 class="text-2xl font-semibold mb-4">Search Results for "<?= htmlspecialchars($search_query) ?>"</h2>

    <?php if ($result->num_rows > 0): ?>
        <p class="mb-4">Found <?= $result->num_rows ?> result(s).</p>
        <ul class="space-y-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="p-4 bg-white rounded shadow hover:bg-gray-200 flex items-center space-x-4">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Product Image" class="w-16 h-16 object-cover">
                    <a href="product_details.php?product_id=<?= $row['product_id'] ?>">
                        <p class="font-semibold text-lg"><?= htmlspecialchars($row['name']) ?></p>
                        <p class="text-gray-600">Category: <?= htmlspecialchars($row['category']) ?></p>
                        <p class="text-gray-600">Description: <?= htmlspecialchars($row['description']) ?></p>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</div>
</body>
</html>
