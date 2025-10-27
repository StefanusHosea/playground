/**
 * Dynamic Invert Cursor JavaScript
 * Version: 1.0.0
 */

(function() {
    'use strict';
    
    // Configuration
    const config = {
        cursorSize: 30,
        linkScale: 0.75,      // 25% smaller
        buttonScale: 0.65,    // 35% smaller
        inputScale: 0.5,      // 50% smaller
        easing: 0.15,         // Smooth follow effect
        trailEnabled: false   // Set to true for trail effect
    };
    
    // Cursor element
    let cursor = null;
    let cursorTrail = null;
    
    // Position tracking
    let mouseX = 0;
    let mouseY = 0;
    let cursorX = 0;
    let cursorY = 0;
    let trailX = 0;
    let trailY = 0;
    
    // Current hover state
    let currentHoverClass = '';
    
    /**
     * Initialize the custom cursor
     */
    function init() {
        // Create cursor element
        cursor = document.createElement('div');
        cursor.className = 'custom-cursor';
        document.body.appendChild(cursor);
        
        // Create trail element (optional)
        if (config.trailEnabled) {
            cursorTrail = document.createElement('div');
            cursorTrail.className = 'custom-cursor-trail';
            document.body.appendChild(cursorTrail);
        }
        
        // Event listeners
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseenter', showCursor);
        document.addEventListener('mouseleave', hideCursor);
        document.addEventListener('mousedown', handleMouseDown);
        document.addEventListener('mouseup', handleMouseUp);
        
        // Attach hover listeners to interactive elements
        attachHoverListeners();
        
        // Start animation loop
        requestAnimationFrame(animate);
    }
    
    /**
     * Handle mouse movement
     */
    function handleMouseMove(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
    }
    
    /**
     * Show cursor
     */
    function showCursor() {
        if (cursor) {
            cursor.classList.remove('hidden');
        }
    }
    
    /**
     * Hide cursor
     */
    function hideCursor() {
        if (cursor) {
            cursor.classList.add('hidden');
        }
    }
    
    /**
     * Handle mouse down
     */
    function handleMouseDown() {
        if (cursor) {
            cursor.classList.add('active');
        }
    }
    
    /**
     * Handle mouse up
     */
    function handleMouseUp() {
        if (cursor) {
            cursor.classList.remove('active');
        }
    }
    
    /**
     * Attach hover listeners to interactive elements
     */
    function attachHoverListeners() {
        // Links
        const links = document.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('mouseenter', () => setCursorState('hover-link'));
            link.addEventListener('mouseleave', () => setCursorState(''));
        });
        
        // Buttons
        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => setCursorState('hover-button'));
            button.addEventListener('mouseleave', () => setCursorState(''));
        });
        
        // Input fields
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('mouseenter', () => setCursorState('hover-input'));
            input.addEventListener('mouseleave', () => setCursorState(''));
        });
        
        // Observer for dynamically added elements
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) { // Element node
                        attachListenersToElement(node);
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    /**
     * Attach listeners to a single element
     */
    function attachListenersToElement(element) {
        if (element.matches('a')) {
            element.addEventListener('mouseenter', () => setCursorState('hover-link'));
            element.addEventListener('mouseleave', () => setCursorState(''));
        } else if (element.matches('button')) {
            element.addEventListener('mouseenter', () => setCursorState('hover-button'));
            element.addEventListener('mouseleave', () => setCursorState(''));
        } else if (element.matches('input, textarea, select')) {
            element.addEventListener('mouseenter', () => setCursorState('hover-input'));
            element.addEventListener('mouseleave', () => setCursorState(''));
        }
        
        // Check children
        const links = element.querySelectorAll('a');
        const buttons = element.querySelectorAll('button');
        const inputs = element.querySelectorAll('input, textarea, select');
        
        links.forEach(link => {
            link.addEventListener('mouseenter', () => setCursorState('hover-link'));
            link.addEventListener('mouseleave', () => setCursorState(''));
        });
        
        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => setCursorState('hover-button'));
            button.addEventListener('mouseleave', () => setCursorState(''));
        });
        
        inputs.forEach(input => {
            input.addEventListener('mouseenter', () => setCursorState('hover-input'));
            input.addEventListener('mouseleave', () => setCursorState(''));
        });
    }
    
    /**
     * Set cursor state/class
     */
    function setCursorState(state) {
        if (cursor && currentHoverClass !== state) {
            cursor.classList.remove('hover-link', 'hover-button', 'hover-input');
            if (state) {
                cursor.classList.add(state);
            }
            currentHoverClass = state;
        }
    }
    
    /**
     * Animation loop
     */
    function animate() {
        // Smooth following effect
        cursorX += (mouseX - cursorX) * config.easing;
        cursorY += (mouseY - cursorY) * config.easing;
        
        // Update cursor position
        if (cursor) {
            cursor.style.transform = `translate(${cursorX}px, ${cursorY}px)`;
        }
        
        // Update trail position (if enabled)
        if (config.trailEnabled && cursorTrail) {
            trailX += (mouseX - trailX) * (config.easing * 0.5);
            trailY += (mouseY - trailY) * (config.easing * 0.5);
            cursorTrail.style.transform = `translate(${trailX}px, ${trailY}px)`;
        }
        
        requestAnimationFrame(animate);
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();
