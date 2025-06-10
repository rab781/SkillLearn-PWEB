// Enhanced Search System for Skillearn Platform
// Provides intelligent search suggestions and filters

class SkillLearnSearch {
    constructor() {
        this.searchCache = new Map();
        this.searchHistory = JSON.parse(localStorage.getItem('skilllearn_search_history') || '[]');
        this.popularSearches = ['HTML', 'CSS', 'JavaScript', 'React', 'Laravel', 'PHP', 'Python'];
        this.debounceTimer = null;
        this.minSearchLength = 2;
    }

    // Initialize search functionality
    initializeSearch(inputSelector = '#searchInput') {
        const searchInput = document.querySelector(inputSelector);
        if (!searchInput) return;

        this.createSearchDropdown(searchInput);
        this.attachSearchEvents(searchInput);
        this.addSearchIcon(searchInput);
    }

    // Create search dropdown for suggestions
    createSearchDropdown(searchInput) {
        const container = searchInput.parentElement;
        if (!container || container.querySelector('.search-dropdown')) return;

        container.style.position = 'relative';

        const dropdown = document.createElement('div');
        dropdown.className = 'search-dropdown absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden max-h-96 overflow-y-auto';
        dropdown.innerHTML = this.generateDropdownContent();

        container.appendChild(dropdown);
        searchInput.dropdown = dropdown;
    }

    // Generate dropdown content
    generateDropdownContent() {
        let content = '';

        // Search history
        if (this.searchHistory.length > 0) {
            content += `
                <div class="p-3 border-b border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Pencarian Terbaru
                    </h4>
                    <div class="space-y-1">
                        ${this.searchHistory.slice(0, 5).map(term => `
                            <button class="search-suggestion flex items-center w-full p-2 text-left text-sm text-gray-600 hover:bg-gray-50 rounded" data-search="${term}">
                                <svg class="w-3 h-3 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                                ${term}
                            </button>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        // Popular searches
        content += `
            <div class="p-3">
                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Pencarian Populer
                </h4>
                <div class="flex flex-wrap gap-2">
                    ${this.popularSearches.map(term => `
                        <button class="search-suggestion bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm hover:bg-blue-100 transition-colors" data-search="${term}">
                            ${term}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

        return content;
    }

    // Attach search events
    attachSearchEvents(searchInput) {
        const dropdown = searchInput.dropdown;

        // Input events
        searchInput.addEventListener('input', (e) => {
            this.handleSearchInput(e.target.value, dropdown);
        });

        searchInput.addEventListener('focus', () => {
            if (searchInput.value.length >= this.minSearchLength) {
                this.showSearchSuggestions(searchInput.value, dropdown);
            } else {
                dropdown.innerHTML = this.generateDropdownContent();
                this.attachDropdownEvents(dropdown, searchInput);
                dropdown.classList.remove('hidden');
            }
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Keyboard navigation
        searchInput.addEventListener('keydown', (e) => {
            this.handleKeyboardNavigation(e, dropdown);
        });

        this.attachDropdownEvents(dropdown, searchInput);
    }

    // Handle search input with debouncing
    handleSearchInput(query, dropdown) {
        clearTimeout(this.debounceTimer);
        
        this.debounceTimer = setTimeout(() => {
            if (query.length >= this.minSearchLength) {
                this.showSearchSuggestions(query, dropdown);
            } else if (query.length === 0) {
                dropdown.innerHTML = this.generateDropdownContent();
                this.attachDropdownEvents(dropdown, dropdown.previousElementSibling);
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }, 300);
    }

    // Show search suggestions
    async showSearchSuggestions(query, dropdown) {
        try {
            // Check cache first
            if (this.searchCache.has(query)) {
                this.displaySuggestions(this.searchCache.get(query), dropdown, query);
                return;
            }

            // Show loading state
            dropdown.innerHTML = `
                <div class="p-4 text-center">
                    <div class="loading-spinner mx-auto mb-2"></div>
                    <p class="text-sm text-gray-500">Mencari suggestions...</p>
                </div>
            `;
            dropdown.classList.remove('hidden');

            // Fetch suggestions from API
            const response = await fetch(`/api/search/suggestions?q=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.success) {
                this.searchCache.set(query, data.suggestions);
                this.displaySuggestions(data.suggestions, dropdown, query);
            } else {
                this.displayNoSuggestions(dropdown, query);
            }
        } catch (error) {
            console.error('Error fetching suggestions:', error);
            this.displayNoSuggestions(dropdown, query);
        }
    }

    // Display search suggestions
    displaySuggestions(suggestions, dropdown, query) {
        if (!suggestions || suggestions.length === 0) {
            this.displayNoSuggestions(dropdown, query);
            return;
        }

        const groupedSuggestions = this.groupSuggestions(suggestions);
        let content = '';

        // Videos
        if (groupedSuggestions.videos && groupedSuggestions.videos.length > 0) {
            content += `
                <div class="p-3 border-b border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                        Video
                    </h4>
                    <div class="space-y-1">
                        ${groupedSuggestions.videos.slice(0, 5).map(video => `
                            <a href="/videos/${video.vidio_id}" class="flex items-center p-2 text-sm text-gray-700 hover:bg-gray-50 rounded">
                                <img src="${video.gambar}" alt="${video.nama}" class="w-8 h-8 object-cover rounded mr-3">
                                <div class="flex-1 min-w-0">
                                    <p class="truncate font-medium">${this.highlightMatch(video.nama, query)}</p>
                                    <p class="text-xs text-gray-500">${video.kategori?.kategori || 'Umum'}</p>
                                </div>
                            </a>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        // Categories
        if (groupedSuggestions.categories && groupedSuggestions.categories.length > 0) {
            content += `
                <div class="p-3">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                        </svg>
                        Kategori
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        ${groupedSuggestions.categories.map(category => `
                            <a href="/videos?category=${category.kategori_id}" class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm hover:bg-green-100 transition-colors">
                                ${this.highlightMatch(category.kategori, query)}
                            </a>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        dropdown.innerHTML = content;
        dropdown.classList.remove('hidden');
    }

    // Display no suggestions message
    displayNoSuggestions(dropdown, query) {
        dropdown.innerHTML = `
            <div class="p-4 text-center">
                <div class="text-4xl mb-2">🔍</div>
                <p class="text-sm text-gray-600 mb-2">Tidak ada hasil untuk "${query}"</p>
                <p class="text-xs text-gray-500">Coba kata kunci lain atau lihat pencarian populer</p>
            </div>
        `;
        dropdown.classList.remove('hidden');
    }

    // Group suggestions by type
    groupSuggestions(suggestions) {
        return {
            videos: suggestions.filter(s => s.type === 'video'),
            categories: suggestions.filter(s => s.type === 'category')
        };
    }

    // Highlight matching text
    highlightMatch(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
    }

    // Attach dropdown events
    attachDropdownEvents(dropdown, searchInput) {
        const suggestions = dropdown.querySelectorAll('.search-suggestion');
        suggestions.forEach(suggestion => {
            suggestion.addEventListener('click', (e) => {
                e.preventDefault();
                const searchTerm = suggestion.dataset.search;
                searchInput.value = searchTerm;
                this.addToSearchHistory(searchTerm);
                dropdown.classList.add('hidden');
                
                // Trigger search
                const event = new Event('input', { bubbles: true });
                searchInput.dispatchEvent(event);
            });
        });
    }

    // Handle keyboard navigation
    handleKeyboardNavigation(e, dropdown) {
        const suggestions = dropdown.querySelectorAll('a, button');
        const currentFocus = dropdown.querySelector('.focused');
        let index = Array.from(suggestions).indexOf(currentFocus);

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                index = index < suggestions.length - 1 ? index + 1 : 0;
                this.focusSuggestion(suggestions[index]);
                break;
            case 'ArrowUp':
                e.preventDefault();
                index = index > 0 ? index - 1 : suggestions.length - 1;
                this.focusSuggestion(suggestions[index]);
                break;
            case 'Enter':
                e.preventDefault();
                if (currentFocus) {
                    currentFocus.click();
                }
                break;
            case 'Escape':
                dropdown.classList.add('hidden');
                break;
        }
    }

    // Focus suggestion
    focusSuggestion(suggestion) {
        const dropdown = suggestion.closest('.search-dropdown');
        const focused = dropdown.querySelector('.focused');
        if (focused) focused.classList.remove('focused');
        
        suggestion.classList.add('focused');
        suggestion.scrollIntoView({ block: 'nearest' });
    }

    // Add to search history
    addToSearchHistory(term) {
        if (!term || term.length < this.minSearchLength) return;
        
        // Remove if already exists
        this.searchHistory = this.searchHistory.filter(item => item !== term);
        
        // Add to beginning
        this.searchHistory.unshift(term);
        
        // Keep only last 10 searches
        this.searchHistory = this.searchHistory.slice(0, 10);
        
        // Save to localStorage
        localStorage.setItem('skilllearn_search_history', JSON.stringify(this.searchHistory));
    }

    // Add search icon with loading state
    addSearchIcon(searchInput) {
        const container = searchInput.parentElement;
        const existingIcon = container.querySelector('.search-icon');
        if (existingIcon) return;

        const icon = document.createElement('div');
        icon.className = 'search-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none';
        icon.innerHTML = `
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        `;

        container.appendChild(icon);
        searchInput.style.paddingLeft = '2.5rem';
    }

    // Clear search history
    clearSearchHistory() {
        this.searchHistory = [];
        localStorage.removeItem('skilllearn_search_history');
    }
}

// Initialize search system
window.skillSearch = new SkillLearnSearch();

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.skillSearch.initializeSearch();
});

export default SkillLearnSearch;
