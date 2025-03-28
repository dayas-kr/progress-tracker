import { disableScrolling, enableScrolling } from "./utils/scroll";

export function initAlertDialogs(root = document) {
    let currentDialog = null;
    let currentOpener = null;
    let currentDialogContent = null;

    const trapFocus = () => {
        const focusableElements = currentDialogContent.querySelectorAll(
            'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"])'
        );
        if (focusableElements.length) {
            focusableElements[0].focus();
        } else {
            currentDialogContent.focus();
        }
    };

    const handleBackdropClick = (event) => {
        if (event.target === currentDialog) {
            closeDialog();
        }
    };

    const trapTabKey = (event) => {
        if (!currentDialogContent) return;
        if (event.key === "Tab") {
            const focusableElements = currentDialogContent.querySelectorAll(
                'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );
            const focusable = Array.from(focusableElements);
            if (focusable.length === 0) {
                event.preventDefault();
                return;
            }
            const firstElement = focusable[0];
            const lastElement = focusable[focusable.length - 1];
            if (event.shiftKey) {
                if (document.activeElement === firstElement) {
                    event.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement) {
                    event.preventDefault();
                    firstElement.focus();
                }
            }
        } else if (event.key === "Escape") {
            closeDialog();
        }
    };

    const keepFocusInModal = (event) => {
        if (
            currentDialog &&
            currentDialog.style.display === "flex" &&
            !currentDialogContent.contains(event.target)
        ) {
            event.stopPropagation();
            trapFocus();
        }
    };

    const openDialog = (dialog, opener) => {
        disableScrolling();
        currentDialog = dialog;
        currentOpener = opener;
        currentDialog.style.display = "flex";
        currentDialogContent = currentDialog.querySelector(
            "[data-dialog-content]"
        );

        // Set initial state using Tailwind classes:
        // Start with a slightly reduced scale and transparent state.
        currentDialogContent.classList.add(
            "opacity-0",
            "scale-95",
            "transition-transform",
            "ease-out"
        );

        // Force reflow so that the initial state is applied.
        void currentDialogContent.offsetWidth;

        // Transition to the final state.
        currentDialogContent.classList.remove("opacity-0", "scale-95");
        currentDialogContent.classList.add("opacity-100", "scale-100");

        trapFocus();
        document.addEventListener("keydown", trapTabKey);
        document.addEventListener("focusin", keepFocusInModal);
        currentDialog.addEventListener("click", handleBackdropClick);
    };

    const closeDialog = () => {
        if (currentDialog) {
            // Reverse the animation by transitioning back to a reduced scale and transparency.
            currentDialogContent.classList.remove("opacity-100", "scale-100");
            currentDialogContent.classList.add("opacity-0", "scale-95");

            // Wait for the transition to finish before hiding the dialog.
            currentDialogContent.addEventListener(
                "transitionend",
                function handler() {
                    currentDialogContent.removeEventListener(
                        "transitionend",
                        handler
                    );
                    currentDialog.style.display = "none";

                    currentDialog.removeEventListener(
                        "click",
                        handleBackdropClick
                    );
                    document.removeEventListener("keydown", trapTabKey);
                    document.removeEventListener("focusin", keepFocusInModal);
                    if (currentOpener) {
                        currentOpener.focus();
                    }
                    currentDialog = null;
                    currentOpener = null;
                    currentDialogContent = null;
                    enableScrolling();
                }
            );
        }
    };

    const attachListeners = () => {
        root.querySelectorAll("[data-dialog-target]").forEach((button) => {
            button.addEventListener("click", () => {
                const dialogId = button.getAttribute("data-dialog-target");
                const dialog = document.getElementById(dialogId);
                if (dialog) {
                    openDialog(dialog, button);
                }
            });
        });

        root.querySelectorAll(
            "[data-dialog-cancel], [data-dialog-confirm]"
        ).forEach((button) => {
            button.addEventListener("click", closeDialog);
        });
    };

    if (root.querySelector("[data-dialog-target]")) {
        attachListeners();
    }
}
