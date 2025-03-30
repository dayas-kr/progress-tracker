import $ from "jquery";
import { setItemWithExpiration } from "../utils/session";
import createAlert from "../ui/alert";

const csrfToken = $('meta[name="csrf-token"]').attr("content");
const videoId = $("#player").data("video-id");
const markCompletedBtn = $("#mark-completed");
const resetProgressBtn = $("#reset-progress");
const alertContainer = $("#alert-container");

document.addEventListener("DOMContentLoaded", () => {
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

    // Variable to hold the player object
    let player;
    let lastSentTime = -1;

    // Function to initialize the YouTube Player with the start time and always auto play.
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
                autoplay: 1,
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
        } else if (event.data === YT.PlayerState.ENDED) {
            VideoCompletionLogic(playlistId);
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
        // Only update the time if the video is not marked as completed
        if (!markCompletedBtn.data("video-completed")) {
            // Save the current time in local storage with a 1-hour expiration.
            const storageKey = `videoTime_${videoId}`;
            setItemWithExpiration(storageKey, currentTime, 3600);

            // Only update the database every 10 seconds.
            if (currentTime % 10 === 0) {
                sendUpdateRequest(currentTime);
            }
        }
    }

    // Send an update when the user pauses the video
    function sendPauseTimeToAPI() {
        if (player && typeof player.getCurrentTime === "function") {
            const pausedTime = Math.floor(player.getCurrentTime());
            if (!markCompletedBtn.data("video-completed")) {
                sendUpdateRequest(pausedTime);
            }
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
    markCompletedBtn.on("click", function () {
        if (!$(this).data("video-completed")) {
            const data = { _token: csrfToken, v: videoId };
            markVideoAsCompleted(data, $(this));
        }
    });

    // Mark video as uncompleted
    resetProgressBtn.on("click", function () {
        if (markCompletedBtn.data("video-completed")) {
            const data = { _token: csrfToken, v: videoId };
            markVideoAsUncompleted(data, $(this), markCompletedBtn);
        }
    });
});

// helper functions
function markVideoAsCompleted(data, button) {
    const checkIcon = button.find(".check-icon");
    const checkedIcon = button.find(".checked-icon");
    const spinnerIcon = button.find(".spinner-icon");

    $.ajax({
        url: "/api/videos/complete",
        method: "POST",
        data: { _token: csrfToken, v: videoId },
        beforeSend: () => {
            if (!$(this).data("video-completed")) {
                checkIcon.addClass("display-none");
                spinnerIcon.removeClass("hidden");
                checkedIcon.addClass("display-none");
            }
        },
        success: (response) => {
            console.log(response);
            spinnerIcon.addClass("hidden");
            if (response.status === "success") {
                checkedIcon.removeClass("display-none");
                button.data("video-completed", true);
            } else {
                checkIcon.removeClass("display-none");
                alertContainer.append(
                    createAlert({
                        type: "error",
                        message: "Playlist with given ID does not exist.",
                        timeout: 5000,
                    })
                );
            }
        },
        error: () => {
            spinnerIcon.addClass("hidden");
            checkIcon.removeClass("display-none");
        },
    });
}

function markVideoAsUncompleted(data, button, markCompletedBtn) {
    $.ajax({
        url: "/api/videos/reset",
        method: "POST",
        data: { _token: csrfToken, v: videoId },
        success: (response) => {
            console.log(response);
            if (response.status === "success") {
                markCompletedBtn
                    .find(".check-icon")
                    .removeClass("display-none");
                markCompletedBtn.find(".checked-icon").addClass("display-none");
                markCompletedBtn.data("video-completed", false);
            }
        },
        error: () => {
            //
        },
    });
}

function VideoCompletionLogic(playlistId) {
    const currentVideoEl = $(`[data-video-card][data-video-id="${videoId}"]`);

    const nextVideo = currentVideoEl.next("[data-video-card]");

    const nextVideoId = nextVideo.data("video-id");

    // mark current video as completed
    markVideoAsCompleted({ _token: csrfToken, v: videoId }, markCompletedBtn);

    if (nextVideo.length > 0) {
        window.location.href = `/watch?v=${nextVideoId}&list=${playlistId}`;
    }
}
