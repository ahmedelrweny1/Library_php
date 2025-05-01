document.addEventListener('DOMContentLoaded', function() {
    // Handle mobile sidebar toggle
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;
    
    // Create mobile backdrop
    let mobileBackdrop = document.querySelector('.mobile-menu-backdrop');
    if (!mobileBackdrop) {
        mobileBackdrop = document.createElement('div');
        mobileBackdrop.className = 'mobile-menu-backdrop';
        body.appendChild(mobileBackdrop);
    }
    
    // Clean up any existing click handlers
    if (menuToggle) {
        // Remove inline handler
        menuToggle.onclick = null;
        
        // Add clean click handler
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Menu toggle clicked');
            sidebar.classList.toggle('active');
            mobileBackdrop.classList.toggle('active');
            
            // Prevent body scroll when sidebar is open
            if (sidebar.classList.contains('active')) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
            }
            
            return false;
        });
    }
    
    // Handle backdrop click
    mobileBackdrop.addEventListener('click', function() {
        console.log('Backdrop clicked');
        sidebar.classList.remove('active');
        mobileBackdrop.classList.remove('active');
        body.style.overflow = '';
    });
    
    // Handle mobile menu close button
    const closeMenu = document.getElementById('close-menu');
    if (closeMenu) {
        closeMenu.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Close button clicked');
            sidebar.classList.remove('active');
            mobileBackdrop.classList.remove('active');
            body.style.overflow = '';
        });
    }

    // Fix mobile display issues on load and resize
    function adjustForMobile() {
        if (window.innerWidth <= 576) {
            body.classList.add('mobile');
        } else {
            body.classList.remove('mobile');
            sidebar.classList.remove('active');
            mobileBackdrop.classList.remove('active');
            body.style.overflow = '';
        }
    }
    
    // Run on page load
    adjustForMobile();
    
    // Run on window resize
    window.addEventListener('resize', adjustForMobile);
    
    console.log('Responsive.js loaded successfully');
});