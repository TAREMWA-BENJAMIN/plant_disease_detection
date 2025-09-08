// Feather Icons initialization with proper error handling
(function() {
    function initFeatherIcons() {
        // Check if feather library is available
        if (typeof window.feather === 'undefined' || typeof window.feather.replace !== 'function') {
            console.warn('Feather Icons library not loaded or replace method not available');
            return;
        }

        try {
            // Find all elements with data-feather attribute
            const featherElements = document.querySelectorAll('[data-feather]');
            
            if (featherElements.length === 0) {
                console.log('No Feather Icons elements found');
                return;
            }

            console.log(`Found ${featherElements.length} Feather Icons elements`);
            
            // Replace icons one by one with error handling
            featherElements.forEach(function(element, index) {
                try {
                    if (element && element.tagName) {
                        // Create a new SVG element
                        const iconName = element.getAttribute('data-feather');
                        if (iconName) {
                            const svg = window.feather.icons[iconName];
                            if (svg) {
                                element.innerHTML = svg.toSvg();
                            } else {
                                console.warn(`Icon '${iconName}' not found in Feather Icons`);
                            }
                        }
                    }
                } catch (iconError) {
                    console.warn(`Failed to replace icon at index ${index}:`, iconError);
                }
            });
            
            console.log('Feather Icons initialization completed');
        } catch (error) {
            console.error('Feather Icons initialization failed:', error);
        }
    }

    // Try to initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit more for all resources to load
            setTimeout(initFeatherIcons, 200);
        });
    } else {
        // DOM is already ready
        setTimeout(initFeatherIcons, 200);
    }

    // Also try to initialize when window loads (for any late-loaded content)
    window.addEventListener('load', function() {
        setTimeout(initFeatherIcons, 100);
    });
})(); 