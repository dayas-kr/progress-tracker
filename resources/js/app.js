import "./bootstrap";
import Alpine from "alpinejs";
import $ from "jquery";

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
    // Set the CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    initCustomInputs();
    initDropdowns(document);
    initSelects(document);
    initAlertDialogs(document);
});
