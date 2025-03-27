export function initCustomInputs(root = document) {
    // For custom input labels (existing functionality)
    root.querySelectorAll("[data-checkbox-label]").forEach((label) => {
        label.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                label.click();
            }
        });
    });

    // New functionality: Make all checkbox labels focusable and allow toggle via Enter/Space
    root.querySelectorAll(".switch-label").forEach((label) => {
        const input = label.querySelector("input[type='checkbox']");
        if (input) {
            // Make the label focusable if not already
            if (!label.hasAttribute("tabindex")) {
                label.setAttribute("tabindex", "0");
            }
            label.addEventListener("keydown", (e) => {
                if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    input.click();
                } else if (e.key === "Tab") {
                    // Check for a next element to focus within our custom inputs
                    const nextEl = label.nextElementSibling;
                    if (nextEl) {
                        e.preventDefault();
                        nextEl.focus();
                    }
                    // Otherwise, let the default Tab behavior occur (i.e. browser focus moves out)
                }
            });
        }
    });
}

// Alias the old function name for backwards compatibility
export const initCustomCheckboxes = initCustomInputs;
