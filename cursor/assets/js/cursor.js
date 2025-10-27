// Define a variable to track if the cursor is over an iframe
let isOverIframe = false;

// Function to check if the cursor is over an iframe
function checkIframeHover(event) {
    const element = document.elementFromPoint(event.clientX, event.clientY);
    isOverIframe = element && element.tagName === 'IFRAME';
}

// Function to handle iframe mouse events
function handleIframes() {
    const iframes = document.querySelectorAll('iframe');
    iframes.forEach(iframe => {
        iframe.addEventListener('mouseenter', hideCursor);
        iframe.addEventListener('mouseleave', showCursor);
    });
    
    // Use MutationObserver to observe dynamically added iframes
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.type === 'childList') {
                handleIframes(); // Re-initialize listeners for new iframes
            }
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });
}

// Update handleMouseMove to call checkIframeHover
function handleMouseMove(event) {
    checkIframeHover(event);
    // Other existing functionality...
}

// Update cursor handling functions to respect isOverIframe state
function showCursor() {
    if (!isOverIframe) {
        // Show custom cursor logic...
    }
}

function hideCursor() {
    if (!isOverIframe) {
        // Hide custom cursor logic...
    }
}

function handleMouseDown() {
    if (!isOverIframe) {
        // Handle mouse down logic...
    }
}

function setCursorState() {
    if (!isOverIframe) {
        // Set cursor state logic...
    }
}

// Update animate function to only update cursor position when not over iframe
function animate() {
    if (!isOverIframe) {
        // Animation logic...
    }
}

// Update config to use dicSettings from PHP if available
const cursorSize = dicSettings.cursorSize || defaultCursorSize;
const smoothSpeed = dicSettings.smoothSpeed || defaultSmoothSpeed;
const smoothEnabled = dicSettings.smoothEnabled || defaultSmoothEnabled;

// Call handleIframes() in the init function
function init() {
    handleIframes();
    // Other initialization logic...
}

// Existing functionality for links, buttons, and inputs remains unchanged...