import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

// UI Components
import { initCustomInputs } from "./ui/customInput";
import { initSelects } from "./ui/select";

// Initialize all UI components on page load.
document.addEventListener("DOMContentLoaded", () => {
    initCustomInputs();
    initSelects(document);
});
