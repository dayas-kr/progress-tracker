import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

// UI Components
import "./ui/navbar";
import { initCustomInputs } from "./ui/customInput";
import { initDropdowns } from "./ui/dropdown";
import { initSelects } from "./ui/select";
import { initAlertDialogs } from "./ui/alertDialog";

// Initialize all UI components on page load.
document.addEventListener("DOMContentLoaded", () => {
    initCustomInputs();
    initDropdowns(document);
    initSelects(document);
    initAlertDialogs(document);
});
