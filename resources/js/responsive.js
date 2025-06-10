// Responsive Utilities for Skillearn Platform
// Handles mobile/desktop specific functionality and optimizations

class SkillLearnResponsive {
    constructor() {
        this.isMobile = window.innerWidth <= 768;
        this.isTablet = window.innerWidth <= 1024 && window.innerWidth > 768;
        this.isDesktop = window.innerWidth > 1024;
        this.setupEventListeners();
    }

    setupEventListeners() {
        window.addEventListener('resize', () => {
            this.updateDeviceInfo();
            this.handleResponsiveLayout();
        });

        // Touch support for mobile
        if (this.isMobile) {
            this.enableTouchOptimizations();
        }
    }

    updateDeviceInfo() {
        this.isMobile = window.innerWidth <= 768;
        this.isTablet = window.innerWidth <= 1024 && window.innerWidth > 768;
        this.isDesktop = window.innerWidth > 1024;
    }

    handleResponsiveLayout() {
        // Adjust grid layouts based on screen size
        const videoGrids = document.querySelectorAll('#videos-grid, .video-grid');
        videoGrids.forEach(grid => {
            if (this.isMobile) {
                grid.className = grid.className.replace(/lg:grid-cols-\d+|md:grid-cols-\d+/, '');
                grid.classList.add('grid-cols-1');
            } else if (this.isTablet) {
                grid.className = grid.className.replace(/lg:grid-cols-\d+/, '');
                if (!grid.classList.contains('md:grid-cols-2')) {
                    grid.classList.add('md:grid-cols-2');
                }
            }
        });

        // Adjust navigation for mobile
        this.handleMobileNavigation();
    }

    enableTouchOptimizations() {
        // Add touch-friendly button sizes
        const buttons = document.querySelectorAll('button, .btn, a[role="button"]');
        buttons.forEach(button => {
            if (!button.classList.contains('touch-optimized')) {
                button.style.minHeight = '44px';
                button.style.minWidth = '44px';
                button.classList.add('touch-optimized');
            }
        });

        // Optimize card touch interactions
        const cards = document.querySelectorAll('.card-hover, .hover\\:shadow-lg');
        cards.forEach(card => {
            card.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            card.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    }

    handleMobileNavigation() {
        const nav = document.querySelector('nav');
        if (!nav) return;

        if (this.isMobile) {
            // Add mobile menu toggle if not exists
            if (!nav.querySelector('.mobile-menu-toggle')) {
                this.addMobileMenuToggle(nav);
            }
        }
    }

    addMobileMenuToggle(nav) {
        const menuToggle = document.createElement('button');
        menuToggle.className = 'mobile-menu-toggle lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100';
        menuToggle.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        `;

        const mobileMenu = document.createElement('div');
        mobileMenu.className = 'mobile-menu hidden lg:hidden absolute top-full left-0 right-0 bg-white shadow-lg border-t';
        
        // Clone navigation items to mobile menu
        const navItems = nav.querySelectorAll('a:not(.mobile-menu-toggle)');
        navItems.forEach(item => {
            const mobileItem = item.cloneNode(true);
            mobileItem.className = 'block px-4 py-3 text-gray-700 hover:bg-gray-50 border-b border-gray-100';
            mobileMenu.appendChild(mobileItem);
        });

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        nav.style.position = 'relative';
        nav.appendChild(menuToggle);
        nav.appendChild(mobileMenu);
    }

    // Optimize video player for mobile
    optimizeVideoPlayer() {
        const videoContainers = document.querySelectorAll('.aspect-video, [class*="aspect-"]');
        videoContainers.forEach(container => {
            if (this.isMobile) {
                // Make video player more touch-friendly
                container.style.borderRadius = '8px';
                container.style.overflow = 'hidden';
                
                // Add fullscreen button for mobile
                if (!container.querySelector('.mobile-fullscreen-btn')) {
                    this.addMobileFullscreenButton(container);
                }
            }
        });
    }

    addMobileFullscreenButton(container) {
        const fullscreenBtn = document.createElement('button');
        fullscreenBtn.className = 'mobile-fullscreen-btn absolute top-2 right-2 bg-black bg-opacity-60 text-white p-2 rounded-full z-10';
        fullscreenBtn.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
            </svg>
        `;

        fullscreenBtn.addEventListener('click', () => {
            if (container.requestFullscreen) {
                container.requestFullscreen();
            }
        });

        container.style.position = 'relative';
        container.appendChild(fullscreenBtn);
    }

    // Optimize form inputs for mobile
    optimizeForms() {
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (this.isMobile) {
                // Prevent zoom on input focus
                input.style.fontSize = '16px';
                
                // Add better touch targets
                if (input.type === 'text' || input.type === 'email' || input.type === 'password') {
                    input.style.minHeight = '44px';
                    input.style.padding = '12px 16px';
                }
            }
        });
    }

    // Lazy loading optimization for images
    setupLazyLoading() {
        const images = document.querySelectorAll('img[src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.classList.add('fade-in');
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => {
                imageObserver.observe(img);
                img.style.transition = 'opacity 0.3s ease';
            });
        }
    }

    // Performance monitoring
    monitorPerformance() {
        if ('performance' in window) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
                    
                    // Log slow loading times
                    if (loadTime > 3000) {
                        console.warn('Slow page load detected:', loadTime + 'ms');
                        
                        // Show optimization suggestion for mobile users
                        if (this.isMobile && loadTime > 5000) {
                            notify.info('📱 Tips Kinerja', 'Untuk pengalaman lebih baik, pastikan koneksi internet stabil');
                        }
                    }
                }, 1000);
            });
        }
    }

    // Initialize all optimizations
    init() {
        this.handleResponsiveLayout();
        this.optimizeVideoPlayer();
        this.optimizeForms();
        this.setupLazyLoading();
        this.monitorPerformance();
        
        // Add responsive utilities CSS
        this.addResponsiveCSS();
    }

    addResponsiveCSS() {
        const style = document.createElement('style');
        style.textContent = `
            .touch-optimized {
                -webkit-tap-highlight-color: rgba(0,0,0,0.1);
                touch-action: manipulation;
            }

            .fade-in {
                opacity: 1;
            }

            .mobile-menu {
                animation: slideDown 0.3s ease-out;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 768px) {
                .card-hover:hover {
                    transform: none;
                }
                
                .text-responsive {
                    font-size: 0.875rem;
                }
                
                .btn-responsive {
                    padding: 0.75rem 1rem;
                    font-size: 0.875rem;
                }
            }

            @media (max-width: 480px) {
                .grid-cols-responsive {
                    grid-template-columns: 1fr;
                }
                
                .text-responsive {
                    font-size: 0.8rem;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// Initialize responsive utilities
window.responsive = new SkillLearnResponsive();

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.responsive.init();
});

export default SkillLearnResponsive;
