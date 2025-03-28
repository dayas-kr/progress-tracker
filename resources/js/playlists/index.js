import $ from "jquery";
import createAlert from "../ui/alert";

const alertContainer = $("#alert-container");

document.addEventListener("DOMContentLoaded", () => {
    handleRedirectMessage();
});

function handleRedirectMessage() {
    const message = new URLSearchParams(window.location.search).get("message");
    if (message) {
        alertContainer.append(
            createAlert({
                type: "success",
                message: decodeURIComponent(message),
                timeout: 5000,
            })
        );
        history.replaceState({}, "", window.location.pathname);
    }
}
