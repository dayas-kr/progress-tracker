export function initCustomInputs(root = document) {
    root.querySelectorAll("[data-custom-input-label]").forEach((label) => {
        label.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                label.click();
            }
        });
    });
}

// Alias the old function name for backwards compatibility
export const initCustomCheckboxes = initCustomInputs;
