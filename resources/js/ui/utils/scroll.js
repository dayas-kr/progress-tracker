let scrollingDisabledCount = 0;
let storedScrollPos = { x: window.scrollX, y: window.scrollY };

function preventScroll(e) {
    // Allow scrolling inside dropdown lists
    if (e.target.closest(".dropdownList")) return;
    e.preventDefault();
}

function preventScrollKeys(e) {
    // Allow key-based scrolling if focus is within a dropdownList
    if (e.target.closest(".dropdownList")) return;

    const scrollKeys = [32, 33, 34, 35, 36, 37, 38, 39, 40];
    if (scrollKeys.includes(e.keyCode)) {
        e.preventDefault();
    }
}

/**
 * Enforces the stored scroll position for the window.
 * When scrolling is disabled and the user drags the scrollbar,
 * this function resets the window scroll to the stored value.
 */
function enforceScrollPosition() {
    if (scrollingDisabledCount > 0) {
        // Only update if the current position is different
        if (
            window.scrollX !== storedScrollPos.x ||
            window.scrollY !== storedScrollPos.y
        ) {
            window.scrollTo(storedScrollPos.x, storedScrollPos.y);
        }
    }
}

/**
 * Disables page scrolling but allows scrolling within dropdown lists.
 * Also records the current scroll position and enforces it in case the user drags the scrollbar.
 */
export function disableScrolling() {
    if (scrollingDisabledCount === 0) {
        storedScrollPos = { x: window.scrollX, y: window.scrollY };
        window.addEventListener("wheel", preventScroll, { passive: false });
        window.addEventListener("touchmove", preventScroll, { passive: false });
        window.addEventListener("keydown", preventScrollKeys, {
            passive: false,
        });
        // Listen to the window scroll event to force it back
        window.addEventListener("scroll", enforceScrollPosition);
    }
    scrollingDisabledCount++;
}

/**
 * Enables page scrolling when no components require it to be disabled.
 */
export function enableScrolling() {
    if (scrollingDisabledCount > 0) {
        scrollingDisabledCount--;
    }
    if (scrollingDisabledCount === 0) {
        window.removeEventListener("wheel", preventScroll, { passive: false });
        window.removeEventListener("touchmove", preventScroll, {
            passive: false,
        });
        window.removeEventListener("keydown", preventScrollKeys, {
            passive: false,
        });
        window.removeEventListener("scroll", enforceScrollPosition);
    }
}
