import $ from "jquery";
import { setItemWithExpiration } from "../utils/session";

const csrfToken = $('meta[name="csrf-token"]').attr("content");
const autoplaySwitch = $("#auto-play-switch");

// Set default value as string "true" for localStorage and ensure the switch is updated as a boolean.
if (localStorage.getItem("auto-play") === null) {
    localStorage.setItem("auto-play", "true");
}
autoplaySwitch.prop("checked", localStorage.getItem("auto-play") === "true");

document.addEventListener("DOMContentLoaded", () => {
    // Update localStorage when the switch changes.
    autoplaySwitch.on("change", () => {
        const isChecked = autoplaySwitch.prop("checked");
        localStorage.setItem("auto-play", isChecked ? "true" : "false");
    });

    // Retrieve the video ID, playlist ID, and start time from the player container
    const playerElement = document.getElementById("player");
    const videoId = playerElement?.dataset.videoId;
    const playlistId = playerElement?.dataset.list; // Ensure this is set in your Blade view
    const startTime = parseInt(playerElement?.dataset.time || "0", 10);

    // Delay to ensure the iframe has been inserted by the YouTube API
    setTimeout(() => {
        const $iframe = $("#player iframe");
        // Remove inline attributes and set CSS to ensure full coverage of parent
        $iframe
            .removeAttr("width")
            .removeAttr("height")
            .css({ width: "100%", height: "100%" });
    }, 100);

    // Function to adjust the height based on 16:9 aspect ratio
    adjustPlayerSize(document.getElementById("player"));

    // Adjust player size on load and on window resize
    adjustPlayerSize();
    window.addEventListener("resize", adjustPlayerSize);

    // Variable to hold the player object
    let player;
    let lastSentTime = -1;

    // Function to initialize the YouTube Player with the start time and autoplay enabled based on localStorage.
    function initializePlayer() {
        if (!videoId) {
            console.error("No video ID found.");
            return;
        }
        player = new YT.Player("player", {
            height: "360",
            width: "640",
            videoId: videoId,
            playerVars: {
                start: startTime, // Start the video at the provided time
                autoplay: localStorage.getItem("auto-play") === "true" ? 1 : 0,
            },
            events: {
                onReady: onPlayerReady,
                onStateChange: onPlayerStateChange,
            },
        });
    }

    // Global API callback – assign to window so the API can call it
    window.onYouTubeIframeAPIReady = () => {
        initializePlayer();
    };

    // If YT.Player is already defined, initialize manually
    if (window.YT && window.YT.Player) {
        window.onYouTubeIframeAPIReady();
    }

    // When the player is ready
    function onPlayerReady(event) {
        startTimeLogger();
    }

    // Handle player state changes
    function onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.PAUSED) {
            sendPauseTimeToAPI(); // Send update when user pauses the video
        }
    }

    // Log current time and send it to your API
    function startTimeLogger() {
        setInterval(() => {
            if (player && typeof player.getCurrentTime === "function") {
                const currentTime = Math.floor(player.getCurrentTime());
                if (currentTime !== lastSentTime) {
                    lastSentTime = currentTime;
                    sendTimeToAPI(currentTime);
                }
            }
        }, 1000);
    }

    // Send the current time to your API endpoint and store it in localStorage
    function sendTimeToAPI(currentTime) {
        // Save the current time in local storage with a 1-hour expiration.
        const storageKey = `videoTime_${videoId}`;
        setItemWithExpiration(storageKey, currentTime, 3600);

        // Only update the database every 10 seconds.
        if (currentTime % 10 === 0) {
            sendUpdateRequest(currentTime);
        }
    }

    // Send an update when the user pauses the video
    function sendPauseTimeToAPI() {
        if (player && typeof player.getCurrentTime === "function") {
            const pausedTime = Math.floor(player.getCurrentTime());
            sendUpdateRequest(pausedTime);
        }
    }

    // Generic function to send an API update request
    function sendUpdateRequest(time) {
        const payload = {
            list: playlistId,
            v: videoId,
            t: time,
        };

        $.ajax({
            url: "/api/update-time",
            method: "POST",
            data: {
                _token: csrfToken,
                ...payload,
            },
            success: function (data) {
                console.log("API update success:", data);
            },
            error: function (error) {
                console.error("Error updating time:", error);
            },
        });
    }

    // Mark video as completed
    $("#mark-video-as-completed").on("click", function () {
        const data = { _token: csrfToken, v: videoId, completed: 1 };
        markVideoAsCompleted(data, $(this));
    });

    // Mark video as uncompleted
    $("#reset-video-progress").on("click", function () {
        const data = { _token: csrfToken, v: videoId, completed: 0 };
        markVideoAsUncompleted(data, $(this));
    });
});

// helper functions
function adjustPlayerSize(player) {
    if (player) {
        const width = player.clientWidth;
        const height = (width * 9) / 16;
        player.style.height = `${height}px`;
    }
}

function markVideoAsCompleted(data, button) {
    const faCircleCheckIcon = button.find(".fa-regular.fa-circle-check");
    const faSpinner = button.find(".fa-spinner");

    $.ajax({
        url: "/api/toggle-video-completion",
        method: "POST",
        data: data,
        beforeSend: () => {
            faCircleCheckIcon.hide();
            faSpinner.show();
        },
        success: (response) => {
            faSpinner.hide();
            if (response.success) {
                button.find(".fa-solid.fa-circle-check").show();
                button.prop("disabled", true);
                button.attr("data-completed", "true");
                $("#reset-video-progress").removeClass("hidden");
            } else {
                faCircleCheckIcon.show();
            }
        },
        error: (error) => {
            faSpinner.hide();
            faCircleCheckIcon.show();
        },
    });
}

function markVideoAsUncompleted(data, button) {
    const faXmarkIcon = button.find(".fa-circle-xmark");
    const faSpinner = button.find(".fa-spinner");
    const markCompletedButton = $("#mark-video-as-completed");
    const faSolidCheck = markCompletedButton.find(".fa-solid.fa-circle-check");
    const faRegularCheck = markCompletedButton.find(
        ".fa-regular.fa-circle-check"
    );

    $.ajax({
        url: "/api/toggle-video-completion",
        method: "POST",
        data: data,
        beforeSend: () => {
            faXmarkIcon.hide();
            faSpinner.show();
        },
        success: (response) => {
            faSpinner.hide();
            if (response.success) {
                button.addClass("hidden");
                faXmarkIcon.show();
                markCompletedButton
                    .prop("disabled", false)
                    .prop("data-completed", false);
                faSolidCheck.hide();
                faRegularCheck.show();
            } else {
                faXmarkIcon.show();
            }
        },
        error: () => {
            faSpinner.hide();
            faXmarkIcon.show();
        },
    });
}
