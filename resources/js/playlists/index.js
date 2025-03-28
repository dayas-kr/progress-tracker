import $ from "jquery";
import createAlert from "../ui/alert";
import { getItemWithExpiration, deleteSessionItem } from "../utils/session";

const alertContainer = $("#alert-container");

document.addEventListener("DOMContentLoaded", () => {
    handleRedirectMessage();
});

function handleRedirectMessage() {
    const message = getItemWithExpiration("playlistMessage_index");
    if (message) {
        alertContainer.append(
            createAlert({
                type: "success",
                message: message,
                timeout: 5000,
            })
        );
        deleteSessionItem("playlistMessage_index");
    }
}
