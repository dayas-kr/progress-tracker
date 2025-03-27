import { disableScrolling, enableScrolling } from "./utils/scroll.js";

let isMenuOpen = false;
const hamburgerBtn = document.getElementById("hamburger-btn");
const responsiveMenu = document.getElementById("responsive-menu");
const iconHamburger = document.getElementById("icon-hamburger");
const iconClose = document.getElementById("icon-close");

if (hamburgerBtn) {
    // Update icon display based on menu state
    function updateIcons() {
        if (isMenuOpen) {
            iconHamburger.style.display = "none";
            iconClose.style.display = "inline-flex";
        } else {
            iconHamburger.style.display = "inline-flex";
            iconClose.style.display = "none";
        }
    }

    function toggleMenu() {
        isMenuOpen = !isMenuOpen;
        if (isMenuOpen) {
            responsiveMenu.classList.remove("hidden"); // Show menu
            disableScrolling();
        } else {
            responsiveMenu.classList.add("hidden"); // Hide menu
            enableScrolling();
        }
        updateIcons();
    }

    // Toggle menu on hamburger button click
    hamburgerBtn.addEventListener("click", toggleMenu);

    // Close menu when Escape key is pressed
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && isMenuOpen) {
            isMenuOpen = false;
            responsiveMenu.classList.add("hidden"); // Hide menu
            updateIcons();
        }
    });

    // Set initial state of icons
    updateIcons();
}
