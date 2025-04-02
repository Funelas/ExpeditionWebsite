const sidebar = document.getElementById('cartSidebar');
sidebar.classList.add("hidden"); 
sidebar.classList.add("transition-transform");
sidebar.classList.add("duration-500");
sidebar.classList.add("ease-in-out");
sidebar.classList.add("transform");
setTimeout(function(){
    sidebar.classList.remove("hidden");
},
500)
// Open sidebar
function openCartSidebar() {
    
    // Check if the sidebar is currently visible (translate-x-0)
    if (sidebar.classList.contains("translate-x-0")) {
        // If it's already visible, close it (move to the right and hide after animation)
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('translate-x-full');
    } else {
        // Open the sidebar (remove 'hidden' class, move it to the left)
        sidebar.classList.add('translate-x-0');
        sidebar.classList.remove('translate-x-full');
         // Slide the sidebar in
    }
}

// Connect "View Cart" button to open the sidebar
document.getElementById('viewCartBtn').addEventListener('click', function() {
    openCartSidebar();
});