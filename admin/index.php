<?php 
    session_start();
    include('../connections.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="p-6 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-semibold mb-4 text-center">Admin Dashboard</h2>
        <div class="space-y-4">
            <button onclick="window.location.href='records.php?table_id=accounts'" class="w-full py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">Manage Accounts</button>
            <button onclick="window.location.href='records.php?table_id=products'" class="w-full py-2 px-4 bg-green-500 text-white rounded hover:bg-green-600">Manage Products</button>
            <button onclick="window.location.href='records.php?table_id=orders'" class="w-full py-2 px-4 bg-yellow-500 text-white rounded hover:bg-yellow-600">Manage Orders</button>
        </div>
    </div>
</div>

</body>
</html>
