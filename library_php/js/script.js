document.addEventListener('DOMContentLoaded', function() {

    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const closeMenu = document.getElementById('close-mobile-menu');

    // Add diagnostic for search functionality
    console.log('DOM loaded, checking search elements:');
    
    const searchInput = document.getElementById('searchInput');
    console.log('Search input found:', !!searchInput);
    
    if (searchInput) {
        console.log('Search input value:', searchInput.value);
        
        // Log book elements that should be searchable
        const indexBooks = document.querySelectorAll('.book');
        const bookCards = document.querySelectorAll('.book-card');
        
        console.log('Home page books found:', indexBooks.length);
        console.log('Books page cards found:', bookCards.length);
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            if (mobileMenu) {
                mobileMenu.classList.add('active');
            }
        });
    }

    if (closeMenu) {
        closeMenu.addEventListener('click', function() {
            if (mobileMenu) {
                mobileMenu.classList.remove('active');
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (mobileMenu && mobileMenu.classList.contains('active')) {
            if (!mobileMenu.contains(e.target) && e.target !== menuToggle) {
                mobileMenu.classList.remove('active');
            }
        }
    });

    const currentPage = window.location.pathname.split('/').pop();
    const sidebarLinks = document.querySelectorAll('.sidebar li');

    sidebarLinks.forEach(link => {
        const anchor = link.querySelector('a');
        if (anchor && anchor.getAttribute('href').includes(currentPage)) {
            link.classList.add('active');
        }
    });
});