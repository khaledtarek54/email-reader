const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

// Toggle sidebar and overlay visibility
menuToggle.addEventListener('click', function() {
    sidebar.classList.toggle('sidebar-active');
    overlay.classList.toggle('overlay-active');
});

// Close sidebar when clicking the overlay
overlay.addEventListener('click', function() {
    sidebar.classList.remove('sidebar-active');
    overlay.classList.remove('overlay-active');
});
