document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const booksContainer = document.querySelector('.books');
    const books = document.querySelectorAll('.book');
    const bookDetailsModal = document.getElementById('bookDetailsModal');
    const modalContent = bookDetailsModal ? bookDetailsModal.querySelector('.modal-content') : null;
    const closeModalBtn = bookDetailsModal ? bookDetailsModal.querySelector('.close-modal') : null;
    const modalBackdrop = bookDetailsModal ? bookDetailsModal.querySelector('.modal-backdrop') : null;

    const detailImg = document.getElementById('detail-img');
    const detailTitle = document.getElementById('detail-title');
    const detailAuthor = document.getElementById('detail-author');
    const detailDescription = document.getElementById('detail-description');
    const rentButton = document.getElementById('rent-button');
    
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

    // SEARCH FUNCTIONALITY - New implementation
    if (searchInput) {
        // Clear search on page load
        searchInput.value = '';
        
        // Handle search input
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            // Get the sections
            const recommendedSection = document.querySelector('.section.recommended');
            const latestSection = document.querySelector('.section.categories');
            
            // If search is empty, show everything and return
            if (searchTerm === '') {
                books.forEach(book => {
                    book.style.display = 'flex';
                    book.classList.remove('search-highlight');
                });
                
                if (recommendedSection) recommendedSection.style.display = 'block';
                if (latestSection) latestSection.style.display = 'block';
                noResultsEl.classList.remove('visible');
                return;
            }
            
            // Count visible books in each section
            let recommendedCount = 0;
            let latestCount = 0;
            
            // Process all books
            books.forEach(book => {
                const title = book.querySelector('h3')?.textContent.toLowerCase() || '';
                const author = book.querySelector('p')?.textContent.toLowerCase() || '';
                
                // Check if book matches search
                const matches = title.includes(searchTerm) || author.includes(searchTerm);
                
                // Set display styles
                book.style.display = matches ? 'flex' : 'none';
                
                // Track by section
                if (matches) {
                    book.classList.add('search-highlight');
                    
                    // Count by section
                    const parentSection = book.closest('.section');
                    if (parentSection) {
                        if (parentSection.classList.contains('recommended')) {
                            recommendedCount++;
                        } else if (parentSection.classList.contains('categories')) {
                            latestCount++;
                        }
                    }
                } else {
                    book.classList.remove('search-highlight');
                }
            });
            
            // Show/hide sections based on content
            if (recommendedSection) {
                recommendedSection.style.display = recommendedCount > 0 ? 'block' : 'none';
            }
            
            if (latestSection) {
                latestSection.style.display = latestCount > 0 ? 'block' : 'none';
            }
            
            // Show/hide no results message
            if (recommendedCount === 0 && latestCount === 0) {
                noResultsEl.classList.add('visible');
            } else {
                noResultsEl.classList.remove('visible');
            }
            
            console.log(`Search: "${searchTerm}" - Found ${recommendedCount + latestCount} books`);
        });
    }

    books.forEach(book => {
        book.addEventListener('click', function() {
            const bookId = this.getAttribute('data-id');
            if (!bookId) return; // Skip if no book ID found

            const bookImg = this.querySelector('img').src;
            const bookTitle = this.querySelector('h3').textContent;
            const bookAuthor = this.querySelector('p').textContent;

            if (bookDetailsModal) {
                showLoadingState();

                modalContent.setAttribute('data-book-id', bookId);

                detailImg.src = bookImg;
                detailTitle.textContent = bookTitle;

                if (detailAuthor.tagName === 'P') {

                    let iconElement = detailAuthor.querySelector('i');
                    if (iconElement) {

                        detailAuthor.innerHTML = '';
                        detailAuthor.appendChild(iconElement);
                        detailAuthor.appendChild(document.createTextNode(' ' + bookAuthor));
                    } else {
                        detailAuthor.textContent = bookAuthor;
                    }
                } else {

                    detailAuthor.textContent = bookAuthor;
                }

                fetch(`get_book_details.php?id=${bookId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {

                        if(data.description) {
                            detailDescription.textContent = data.description;
                        } else {
                            detailDescription.textContent = "No description available for this book.";
                        }

                        clearBookMeta();

                        updateOrCreateElement('detail-year', data.published_year, 'Published:');

                        updateOrCreateElement('detail-isbn', data.isbn, 'ISBN:');

                        if (rentButton) {
                            if (rentButton.hasAttribute('data-requires-login')) {
                                rentButton.innerHTML = 'Login to Rent <i class="fa-solid fa-bookmark"></i>';
                            } else {
                                rentButton.innerHTML = 'Rent This Book <i class="fa-solid fa-bookmark"></i>';
                            }

                            rentButton.setAttribute('data-book-id', bookId);
                        }

                        hideLoadingState();
                    })
                    .catch(error => {
                        console.error('Error fetching book details:', error);
                        detailDescription.textContent = "Could not load book description. Please try again.";
                        hideLoadingState();
                    });

                bookDetailsModal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
            }
        });
    });

    function clearBookMeta() {
        if (!bookDetailsModal) return;
        const bookMeta = modalContent.querySelector('.book-meta');
        if (bookMeta) {
            bookMeta.innerHTML = '';
        }
    }

    function showLoadingState() {
        if (!bookDetailsModal) return;

        modalContent.classList.add('loading');

        if (rentButton) {
            rentButton.disabled = true;
            rentButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';
        }
    }

    function hideLoadingState() {
        if (!bookDetailsModal) return;

        modalContent.classList.remove('loading');

        if (rentButton) {
            rentButton.disabled = false;
        }
    }

    function updateOrCreateElement(className, value, label) {
        if (!value || !bookDetailsModal) return;

        const bookMeta = modalContent.querySelector('.book-meta');
        if (!bookMeta) return;

        let element = bookMeta.querySelector(`.${className}`);

        if (element) {

            element.innerHTML = `<i class="${getIconClass(className)}"></i><strong>${label}</strong> ${value}`;
        } else {

            element = document.createElement('p');
            element.className = className;
            element.innerHTML = `<i class="${getIconClass(className)}"></i><strong>${label}</strong> ${value}`;

            bookMeta.appendChild(element);
        }
    }

    function getIconClass(className) {
        switch (className) {
            case 'detail-year':
                return 'fa-solid fa-calendar-days';
            case 'detail-isbn':
                return 'fa-solid fa-barcode';
            default:
                return 'fa-solid fa-info-circle';
        }
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }

    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', closeModal);
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && bookDetailsModal && bookDetailsModal.classList.contains('active')) {
            closeModal();
        }
    });

    function closeModal() {
        if (bookDetailsModal) {
            bookDetailsModal.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling

            setTimeout(() => {
                clearBookMeta();
                if (detailDescription) {
                    detailDescription.textContent = '';
                }
            }, 300); // Wait for animation to complete
        }
    }

    if (rentButton) {
        rentButton.addEventListener('click', function() {

            const bookId = this.getAttribute('data-book-id') ||
                           (modalContent ? modalContent.getAttribute('data-book-id') : null);

            if (!bookId) {
                alert('Please select a book first');
                return;
            }

            if (this.hasAttribute('data-requires-login')) {
                window.location.href = 'login.php';
                return;
            }

            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

            window.location.href = `rent_book.php?id=${bookId}`;
        });
    }
});