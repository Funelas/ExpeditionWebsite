
function filterCategory(category) {
    const sections = document.querySelectorAll('.category-section');
    sections.forEach(section => {
        if (category === 'All' || section.dataset.category === category) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });
}