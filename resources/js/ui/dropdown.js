import { disableScrolling, enableScrolling } from "./utils/scroll";

// Global variable to keep track of the currently open dropdown.
let currentOpenDropdown = null;

// This function will initialize dropdowns in the given root element (or the whole document).
export function initDropdowns(root = document) {
    // Select only dropdowns that are not yet initialized.
    root.querySelectorAll(
        "[data-dropdown]:not([data-dropdown-initialized])"
    ).forEach(function (dropdown) {
        // Mark this dropdown as initialized.
        dropdown.setAttribute("data-dropdown-initialized", "true");

        const trigger = dropdown.querySelector(
            "[data-dropdown-trigger] button"
        );
        const menu = dropdown.querySelector("[data-dropdown-menu]");
        const content = dropdown.querySelector("[data-dropdown-content]");
        let isOpen = false;
        let activeElement = null;
        let usingKeyboard = false;
        const allItems = Array.from(menu.querySelectorAll(".dropdown-item"));

        // Type-ahead search variables.
        let dropdownKeydownValue = "";
        let dropdownKeydownClearTimeout = null;
        const dropdownKeydownTimeout = 1000; // 1 second

        // Create an instance object to store reference for this dropdown.
        const instance = {};

        // Returns only enabled (non-disabled) items.
        function getEnabledItems() {
            return allItems.filter((item) => item.dataset.disabled !== "true");
        }

        // Update active styling for items.
        function updateActiveItem() {
            allItems.forEach((item) => {
                if (item === activeElement) {
                    item.setAttribute("data-active", "true");
                    item.scrollIntoView({ block: "nearest" });
                } else {
                    item.setAttribute("data-active", "false");
                }
            });
        }

        // Helper: Find the best matching enabled item based on the typed value.
        function dropdownFindBestMatch() {
            const typedValue = dropdownKeydownValue.toLowerCase();
            let bestMatch = null;
            let bestMatchIndex = Infinity;
            const enabledItems = getEnabledItems();
            enabledItems.forEach((item) => {
                const text = item.textContent.trim().toLowerCase();
                const index = text.indexOf(typedValue);
                if (index !== -1 && index < bestMatchIndex) {
                    bestMatch = item;
                    bestMatchIndex = index;
                }
            });
            return bestMatch;
        }

        // Function to handle type-ahead search.
        function handleTypeahead(e) {
            if (e.key.length === 1 && /[a-zA-Z]/.test(e.key)) {
                dropdownKeydownValue += e.key.toLowerCase();
                // If the dropdown isn’t open, open it.
                if (!isOpen) {
                    openDropdown();
                }
                const bestMatch = dropdownFindBestMatch();
                if (bestMatch) {
                    activeElement = bestMatch;
                    updateActiveItem();
                }
                clearTimeout(dropdownKeydownClearTimeout);
                dropdownKeydownClearTimeout = setTimeout(() => {
                    dropdownKeydownValue = "";
                }, dropdownKeydownTimeout);
            }
        }

        // Open the dropdown.
        function openDropdown() {
            // Dispatch event for dropdown open (dropdown:open)
            document.dispatchEvent(new CustomEvent("dropdown:open"));

            // Close any other open dropdown.
            if (currentOpenDropdown && currentOpenDropdown !== instance) {
                currentOpenDropdown.close();
            }
            if (isOpen) return;
            isOpen = true;
            menu.classList.remove("hidden");
            updateDropdownPosition();
            if (!activeElement) {
                const enabledItems = getEnabledItems();
                if (enabledItems.length > 0) {
                    activeElement = enabledItems[0];
                }
            }
            menu.focus();
            disableScrolling();
            currentOpenDropdown = instance;
        }

        // Close the dropdown.
        function closeDropdown() {
            if (!isOpen) return;
            isOpen = false;
            menu.classList.add("hidden");
            menu.style.maxHeight = "";
            content.classList.remove("overflow-y-auto");
            activeElement = null;
            enableScrolling();
            if (currentOpenDropdown === instance) {
                currentOpenDropdown = null;
            }
        }

        // Store the close function in the instance so it can be called globally.
        instance.close = closeDropdown;

        // Dynamically update the dropdown’s position based on available space.
        function updateDropdownPosition() {
            const triggerRect = trigger.getBoundingClientRect();
            const menuHeight = menu.offsetHeight;
            const spaceBottom = window.innerHeight - triggerRect.bottom;
            const spaceTop = triggerRect.top;

            if (menuHeight <= spaceBottom) {
                menu.setAttribute("data-dropdown-position", "bottom");
                content.style.maxHeight = "";
                content.classList.remove("overflow-y-auto");
            } else if (menuHeight <= spaceTop) {
                menu.setAttribute("data-dropdown-position", "top");
                content.style.maxHeight = "";
                content.classList.remove("overflow-y-auto");
            } else {
                menu.setAttribute("data-dropdown-position", "bottom");
                const availableHeight = spaceBottom;
                content.style.maxHeight = `${availableHeight - 18}px`;
                content.classList.add("overflow-y-auto");
            }
        }

        // --- Mouse Events on Individual Items ---
        allItems.forEach((item) => {
            if (item.dataset.disabled === "true") return;
            item.addEventListener("mouseenter", function () {
                usingKeyboard = false;
                activeElement = item;
                updateActiveItem();
            });
            item.addEventListener("mouseleave", function () {
                if (!usingKeyboard && activeElement === item) {
                    activeElement = null;
                    updateActiveItem();
                }
            });
            item.addEventListener("click", function () {
                if (item.dataset.disabled === "true") return;
                dropdown.dispatchEvent(
                    new CustomEvent("dropdown.select", {
                        detail: { value: item.dataset.value },
                    })
                );
                // Close the dropdown after clicking an item
                closeDropdown();
            });
        });

        // --- Keyboard Events on Trigger ---
        trigger.addEventListener("keydown", function (e) {
            if (e.key === "ArrowDown" || e.key === "ArrowUp") {
                usingKeyboard = true;
                openDropdown();
                e.preventDefault();
            } else {
                handleTypeahead(e);
            }
        });

        // --- Keyboard Events on Menu ---
        menu.addEventListener("keydown", function (e) {
            if (e.key === "Escape") {
                closeDropdown();
                setTimeout(() => trigger.focus(), 0);
                e.preventDefault();
            } else if (e.key === "ArrowDown") {
                usingKeyboard = true;
                const enabledItems = getEnabledItems();
                if (!activeElement) {
                    activeElement = enabledItems[0];
                } else {
                    const idx = enabledItems.indexOf(activeElement);
                    if (idx < enabledItems.length - 1) {
                        activeElement = enabledItems[idx + 1];
                    }
                }
                updateActiveItem();
                e.preventDefault();
            } else if (e.key === "ArrowUp") {
                usingKeyboard = true;
                const enabledItems = getEnabledItems();
                if (!activeElement) {
                    activeElement = enabledItems[enabledItems.length - 1];
                } else {
                    const idx = enabledItems.indexOf(activeElement);
                    if (idx > 0) {
                        activeElement = enabledItems[idx - 1];
                    }
                }
                updateActiveItem();
                e.preventDefault();
            } else if (e.key === "Enter") {
                if (activeElement) {
                    activeElement.click();
                    closeDropdown(); // Close the dropdown after the selection
                }
                e.preventDefault();
            } else {
                handleTypeahead(e);
            }
        });

        // --- Clear active styling when the mouse leaves the entire menu.
        menu.addEventListener("mouseleave", function () {
            if (!usingKeyboard) {
                activeElement = null;
                updateActiveItem();
            }
        });

        // --- Toggle Dropdown on Trigger Click ---
        trigger.addEventListener("click", function (e) {
            e.stopPropagation();
            isOpen ? closeDropdown() : openDropdown();
        });

        // --- Close dropdown when clicking outside ---
        document.addEventListener("click", function (e) {
            if (!dropdown.contains(e.target)) closeDropdown();
        });

        // --- Recalculate dropdown position on window resize ---
        window.addEventListener("resize", updateDropdownPosition);
    });
}
