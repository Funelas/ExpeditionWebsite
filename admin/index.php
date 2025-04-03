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
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lexend+Deca&family=Orbitron:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 [font-family:'Kanit']">
    <div class="flex justify-between items-center w-full bg-[#0d0f0f] p-4">
        <div class="flex justify-start items-center ml-2 rounded-lg bg-[#dfe0dc]">
            <a href="index.php" class="mb-1 text-[#0d0f0f] text-lg md:text-lg lg:text-lg px-2 py-1 flex flex-col justify-center items-center [font-family:'Orbitron']">
                <img class="w-[20px] h-[20px] md:w-[20px] md:h-[20px] lg:w-[20px] lg:h-[20px]" src="https://img.icons8.com/ios-filled/100/mission-of-a-company.png" alt="mission-of-a-company"/>
                Expedition
            </a>
        </div>
        <div class="flex items-center justify-end">
                <?php include('header.php');?>
        </div>
    </div>
    
    <div class="p-6 min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-semibold mb-4 text-center">Admin Dashboard</h2>
            <div class="space-y-4">
                <button onclick="window.location.href='records.php?table_id=accounts'" class="w-full py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600 flex flex-col justify-center items-center border-4 border-black"><img class='w-[96px] h-[96px]' src="https://img.icons8.com/fluency-systems-regular/96/edit-administrator.png" alt="edit-administrator"/>Manage Accounts</button>
                <button onclick="window.location.href='records.php?table_id=products'" class="w-full py-2 px-4 bg-green-500 text-white rounded hover:bg-green-600 flex flex-col justify-center items-center border-4 border-black"><img class='w-[96px] h-[96px]' src="https://img.icons8.com/fluency-systems-regular/96/update-tag.png" alt="update-tag"/>Manage Products</button>
            </div>
        </div>
    </div>



</body>

</html>
