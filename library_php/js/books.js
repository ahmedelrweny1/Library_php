document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const booksContainer = document.querySelector('.book-list');
    const books = document.querySelectorAll('.book-card');
    const bookPopup = document.getElementById('bookPopup');
    const popupCover = document.getElementById('popupCover');
    const popupTitle = document.getElementById('popupTitle');
    const popupAuthor = document.getElementById('popupAuthor');
    const popupCategory = document.getElementById('popupCategory');
    const popupYear = document.getElementById('popupYear');
    const popupPages = document.getElementById('popupPages');
    const popupRating = document.getElementById('popupRating');
    const popupDescription = document.getElementById('popupDescription');
    const rentBtn = document.getElementById('rentBtn');
    const closePopupBtn = document.getElementById('closePopupBtn');
    
    // Create "no results" message element
    let noResultsEl = document.createElement('div');
    noResultsEl.className = 'search-no-results';
    noResultsEl.innerHTML = 'No books found matching your search. Try different keywords.';
    
    // Add it after the books container
    if (booksContainer) {
        booksContainer.parentNode.insertBefore(noResultsEl, booksContainer.nextSibling);
    }

    // Store original display styles
    const originalStyles = {};
    books.forEach(book => {
        const computedStyle = window.getComputedStyle(book);
        originalStyles[book.getAttribute('data-id')] = computedStyle.display;
    });

    // SEARCH FUNCTIONALITY - NEW IMPLEMENTATION
    if (searchInput) {
        // Clear search field on page load
        searchInput.value = '';
        
        // Create "no results found" message
        const noResultsMsg = document.createElement('div');
        noResultsMsg.className = 'search-no-results';
        noResultsMsg.textContent = 'No books found matching your search. Try different keywords.';
        
        // Add to page
        const mainContent = document.querySelector('.main-content');
        if (mainContent) {
            const header = mainContent.querySelector('header');
            if (header) {
                mainContent.insertBefore(noResultsMsg, header.nextSibling);
            }
        }
        
        // Listen for search input
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            console.log('Search for:', searchTerm);
            
            // Get book container
            const bookSection = document.querySelector('.section');
            const bookHeading = bookSection ? bookSection.querySelector('.heading') : null;
            
            // If search is empty, show all books
            if (searchTerm === '') {
                books.forEach(book => {
                    book.style.display = 'flex';
                    book.classList.remove('search-highlight');
                });
                
                if (bookHeading) bookHeading.style.display = 'flex';
                noResultsMsg.classList.remove('visible');
                return;
            }
            
            // Process search
            let matchCount = 0;
            
            books.forEach(book => {
                const title = book.querySelector('.book-title')?.textContent.toLowerCase() || '';
                const author = book.querySelector('.book-author')?.textContent.toLowerCase() || '';
                const category = book.querySelector('.book-category')?.textContent.toLowerCase() || '';
                
                // Check if book matches search
                const matches = title.includes(searchTerm) || 
                                author.includes(searchTerm) || 
                                category.includes(searchTerm);
                
                // Show or hide based on match
                book.style.display = matches ? 'flex' : 'none';
                
                if (matches) {
                    matchCount++;
                    book.classList.add('search-highlight');
                } else {
                    book.classList.remove('search-highlight');
                }
            });
            
            // Update UI based on results
            if (matchCount === 0) {
                noResultsMsg.classList.add('visible');
                if (bookHeading) bookHeading.style.display = 'none';
            } else {
                noResultsMsg.classList.remove('visible');
                if (bookHeading) bookHeading.style.display = 'flex';
            }
            
            console.log(`Found ${matchCount} books matching "${searchTerm}"`);
        });
    }

    document.addEventListener("click", (e) => {

        const bookCard = e.target.classList.contains("book-card") ?
                         e.target :
                         (e.target.classList.contains("book-image") ? e.target.parentElement : null);

        if (bookCard) {
            const bookId = bookCard.getAttribute('data-id');
            if (!bookId) return;

            const bookImg = bookCard.querySelector('.book-image').src;
            const bookTitle = bookCard.querySelector('.book-title').textContent;
            const bookAuthor = bookCard.querySelector('.book-author').textContent;
            const bookCategory = bookCard.querySelector('.book-category').textContent;

            bookPopup.classList.add('active');

            popupCover.src = bookImg;
            popupTitle.textContent = bookTitle;
            popupAuthor.textContent = bookAuthor;
            popupCategory.textContent = bookCategory;

            popupYear.innerHTML = '<i class="fa-solid fa-calendar-days"></i> <i class="fa-solid fa-spinner fa-spin"></i>';
            popupPages.innerHTML = '<i class="fa-solid fa-file-lines"></i> <i class="fa-solid fa-spinner fa-spin"></i>';
            popupRating.innerHTML = '<i class="fa-solid fa-star"></i> <i class="fa-solid fa-spinner fa-spin"></i>';
            popupDescription.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading book description...';
            rentBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';

            fetch(`get_book_details.php?id=${bookId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {

                    if (data.description) {
                        popupDescription.textContent = data.description;
                    } else {
                        popupDescription.textContent = "No description available for this book.";
                    }

                    if (data.published_year) {
                        popupYear.innerHTML = `<i class="fa-solid fa-calendar-days"></i> ${data.published_year}`;
                    } else {
                        popupYear.innerHTML = `<i class="fa-solid fa-calendar-days"></i> Unknown`;
                    }

                    popupPages.innerHTML = `<i class="fa-solid fa-file-lines"></i> ${Math.floor(Math.random() * (500 - 100)) + 100} pages`;

                    popupRating.innerHTML = `<i class="fa-solid fa-star"></i> ${Math.floor(Math.random() * 5) + 1}/5`;

                    fetch('check_login_status.php')
                        .then(response => response.json())
                        .then(loginData => {
                            if (loginData.isLoggedIn) {
                                rentBtn.innerHTML = '<i class="fa-solid fa-bookmark"></i> Rent This Book';
                                rentBtn.onclick = function() {
                                    window.location.href = `rent_book.php?id=${bookId}`;
                                };
                            } else {
                                rentBtn.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Login to Rent';
                                rentBtn.onclick = function() {
                                    window.location.href = 'login.php';
                                };
                            }
                        })
                        .catch(error => {
                            console.error('Error checking login status:', error);
                            rentBtn.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Login to Rent';
                            rentBtn.onclick = function() {
                                window.location.href = 'login.php';
                            };
                        });
                })
                .catch(error => {
                    console.error('Error fetching book details:', error);
                    popupDescription.textContent = "Could not load book details. Please try again.";
                    popupYear.innerHTML = '<i class="fa-solid fa-calendar-days"></i> Error';
                    popupPages.innerHTML = '<i class="fa-solid fa-file-lines"></i> Error';
                    popupRating.innerHTML = '<i class="fa-solid fa-star"></i> Error';
                    rentBtn.innerHTML = 'Try Again';
                    rentBtn.onclick = closeBookPopup;
                });
        }
    });

    function closeBookPopup() {
        bookPopup.classList.remove('active');
    }

    if (closePopupBtn) {
        closePopupBtn.addEventListener('click', closeBookPopup);
    }

    bookPopup.addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookPopup();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && bookPopup.classList.contains('active')) {
            closeBookPopup();
        }
    });
});