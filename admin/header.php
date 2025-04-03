
    <?php if (basename($_SERVER['PHP_SELF']) != 'index.php'): ?>
    <div class="flex justify-center items-center mx-4">
        <form method="GET" action="records.php">
            <input type="hidden" name="table_id" value="<?= $table_id ?>">
            <input type="text" name="search" placeholder="Search..." class="p-2 border rounded" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Search</button>
            <a href="index.php" class="btn bg-[#dfe0dc] p-2 rounded hover:bg-[#808080] mx-4 my-2 text-[#0d0f0f]">Home</a>
        </form>
    </div>
        <button class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600" onclick="document.getElementById('addModal').classList.remove('hidden')">Add New Record</button>
        <?php endif; ?>
        <button id="logoutBtn" class="bg-[#dfe0dc] p-2 rounded text-[#0d0f0f] hover:bg-[#808080] mx-4 my-2 text-[#0d0f0f]">Logout</button>
<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <p class="text-lg">Are you sure you want to logout?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button id="confirmLogout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Yes</button>
                <button id="cancelLogout" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">No</button>
            </div>
        </div>
    </div>
<script>
    const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn.addEventListener('click', () => {
        const event = new CustomEvent('showLogoutModal');
        window.dispatchEvent(event);
    });
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