import $ from "jquery";
import { setItemWithExpiration } from "../utils/session";

const playerElement = $("#player");
const videoId = playerElement.data("video-id");
const playlistId = playerElement.data("list");
const startTime = parseInt(playerElement.data("time") || "0", 10);
const VideoOptions = playerElement.data("video-options");
const autoplayCheckbx = $("#autoplay");
const autoCompleteCheckbx = $("#auto_complete");
let isComplete = playerElement.data("completed");

let player;
let lastSentTime = -1;

// Initialize functions when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
    initializeYouTubePlayer(); // Setup the YouTube player
    // Set checkboxes based on VideoOptions values
    autoplayCheckbx.prop("checked", VideoOptions.autoplay);
    autoCompleteCheckbx.prop("checked", VideoOptions.auto_complete);

    $("#video-options-submit").on("click", function (e) {
        $.ajax({
            url: "/api/video-playback-options", // Ensure the URL is correct
            method: "POST",
            data: {
                autoplay: autoplayCheckbx.prop("checked"),
                auto_complete: autoCompleteCheckbx.prop("checked"),
            },
            success: function (response) {
                console.log("Success:", response);
            },
            error: function (xhr, status, error) {
                console.log("Error:", error);
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    // Log validation errors, if any
                    console.log("Validation errors:", errors);
                }
            },
        });
    });
});

// Initialize the YouTube player and set event callbacks
function initializeYouTubePlayer() {
    if (!videoId) return console.error("No video ID found.");

    window.onYouTubeIframeAPIReady = () => {
        player = new YT.Player("player", {
            videoId,
            playerVars: { start: startTime, autoplay: VideoOptions.autoplay },
            events: {
                onReady: startTimeLogger,
                onStateChange: handleStateChange,
            },
        });
    };

    if (window.YT?.Player) window.onYouTubeIframeAPIReady();
}

// Log the video time every second and update if there's a change
function startTimeLogger() {
    setInterval(() => {
        if (!player?.getCurrentTime) return;
        const currentTime = Math.floor(player.getCurrentTime());
        if (currentTime !== lastSentTime) {
            lastSentTime = currentTime;
            handleTimeUpdate(currentTime);
        }
    }, 1000);
}

// On pause, send an immediate time update
function handleStateChange(event) {
    if (event.data === YT.PlayerState.PAUSED) sendTimeUpdate();
    else if (event.data === YT.PlayerState.ENDED) onVideoComplete();
}

// Save current time in session storage and send update every 10 seconds
function handleTimeUpdate(currentTime) {
    setItemWithExpiration(`videoTime_${videoId}`, currentTime, 3600);
    if (currentTime % 10 === 0) sendTimeUpdate(currentTime);
}

// Send current video time to the server if the video isn't complete
function sendTimeUpdate(time = Math.floor(player?.getCurrentTime() || 0)) {
    if (!isComplete) {
        $.post("/api/update-time", {
            list: playlistId,
            v: videoId,
            t: time,
        })
            .done((response) => console.log(response))
            .fail((error) => console.error("Error updating time:", error));
    }
}

function onVideoComplete() {
    // TODO
}
