import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

// UI Components
import { initCustomInputs } from "./ui/customInput";

// Initialize all UI components on page load.
document.addEventListener("DOMContentLoaded", () => {
    initCustomInputs();
});
