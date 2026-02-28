// Initialize Bootstrap tooltips and popovers
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize all Bootstrap popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Utility function to format ratings
function formatRating(rating) {
    return parseFloat(rating).toFixed(1);
}

// Utility function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Handle search form submissions
document.addEventListener('DOMContentLoaded', function() {
    const searchForms = document.querySelectorAll('form[data-search]');
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const searchInput = form.querySelector('input[name="q"]');
            if (searchInput && !searchInput.value.trim()) {
                e.preventDefault();
                alert('Please enter a search term');
            }
        });
    });
});

// Handle filter changes
document.addEventListener('DOMContentLoaded', function() {
    const filterForms = document.querySelectorAll('form[data-filter]');
    filterForms.forEach(form => {
        const selects = form.querySelectorAll('select, input');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                // Auto-submit form on filter change if desired
                // form.submit();
            });
        });
    });
});

// Load more functionality (for potential pagination)
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.querySelector('[data-load-more]');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newItems = doc.querySelectorAll('[data-item]');
                    const container = document.querySelector('[data-items-container]');
                    
                    newItems.forEach(item => {
                        container.appendChild(item.cloneNode(true));
                    });
                    
                    // Update load more button
                    const newLoadMoreBtn = doc.querySelector('[data-load-more]');
                    if (newLoadMoreBtn) {
                        loadMoreBtn.href = newLoadMoreBtn.href;
                    } else {
                        loadMoreBtn.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error loading more items:', error));
        });
    }
});

// Handle modal interactions
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('[data-modal]');
    modals.forEach(modal => {
        const closeBtn = modal.querySelector('[data-modal-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
    });
});