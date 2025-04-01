import $ from "jquery";

const playerElement = $("#player");
const globalVideoId = playerElement.data("video-id");
const playlistId = playerElement.data("list");
const startTime = parseInt(playerElement.data("time") || "0", 10);
const VideoOptions = playerElement.data("video-options");
const autoplayCheckbx = $("#autoplay");
const autoCompleteCheckbx = $("#auto_complete");
const loopPlaylistCheckbox = $("#loop_playlist");
const videoContainer = $("#video-list-container");
const videoOtionsSubmitBtn = $("#video-options-submit");
const markCompletedBtn = $("#mark-completed");
const resetProgressBtn = $("#reset-progress");
const checkboxInput = $(".video-progress-checkbox");

let player;
let lastSentTime = -1;

// Initialize functions when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
    initializeYouTubePlayer(); // Setup the YouTube player

    // Set checkboxes based on VideoOptions values
    autoplayCheckbx.prop("checked", VideoOptions.autoplay);
    autoCompleteCheckbx.prop("checked", VideoOptions.auto_complete);

    videoOtionsSubmitBtn.on("click", updateVideoOptions);

    markCompletedBtn.on("click", () => {
        if (!isCurrentVideoMarked()) markAsCompleted();
    });

    resetProgressBtn.on("click", () => {
        if (isCurrentVideoMarked()) resetProgress();
    });

    checkboxInput.on("change", (event) => {
        handleCheckboxChange($(event.target));
    });
});

// Initialize the YouTube player and set event callbacks
function initializeYouTubePlayer() {
    if (!globalVideoId) return console.error("No video ID found.");

    window.onYouTubeIframeAPIReady = () => {
        player = new YT.Player("player", {
            videoId: globalVideoId,
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

// Send time updates every 10 seconds
function handleTimeUpdate(currentTime) {
    if (currentTime % 10 === 0) sendTimeUpdate(currentTime);
}

// Send current video time to the server if the video isn't complete
function sendTimeUpdate(time = Math.floor(player?.getCurrentTime() || 0)) {
    const data = {
        list: playlistId,
        v: globalVideoId,
        t: time,
    };

    $.post("/api/update-time", data)
        .done((response) => console.log(response))
        .fail((error) => console.error("Error updating time:", error));
}

// Handle video completion
function onVideoComplete() {
    // Send a final update using the full video duration to ensure 100% progress
    if (player && player.getDuration) {
        sendTimeUpdate(Math.floor(player.getDuration()));
    }

    const NextVideo = videoContainer
        .find(`[data-video-id="${globalVideoId}"]`)
        .next("[data-video-card]");

    if (NextVideo.length > 0) {
        const videoId = NextVideo.data("video-id");
        const index = NextVideo.data("index");
        redirectToNextVideo(videoId, index, playlistId);
    } else {
        if (VideoOptions.loop_playlist) {
            const firstVideoCard = videoContainer
                .find("[data-video-card]")
                .first();
            const firstVideoId = firstVideoCard.data("video-id");
            redirectToNextVideo(firstVideoId, 1, playlistId);
        }
    }
}

// --- HELPER FUNCTIONS ---
function redirectToNextVideo(videoId, index, playlistId) {
    window.location.href = `/watch?v=${videoId}&list=${playlistId}&index=${index}`;
}

function updateVideoOptions() {
    $.ajax({
        url: "/api/video-playback-options",
        method: "POST",
        data: {
            autoplay: autoplayCheckbx.prop("checked"),
            auto_complete: autoCompleteCheckbx.prop("checked"),
            loop_playlist: loopPlaylistCheckbox.prop("checked"),
        },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                VideoOptions.autoplay = response.autoplay;
                VideoOptions.auto_complete = response.auto_complete;
                VideoOptions.loop_playlist = response.loop_playlist;
            }
        },
        error: function (xhr, status, error) {
            console.log("Error:", error);
        },
    });
}

function markAsCompleted(videoId = globalVideoId) {
    $.ajax({
        url: "/api/videos/complete",
        method: "POST",
        data: {
            v: videoId,
        },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                updateUI(videoId, true); // Mark video as completed
            }
        },
        error: function (xhr, status, error) {
            console.log("Error:", error);
        },
    });
}

function resetProgress(videoId = globalVideoId) {
    $.ajax({
        url: "/api/videos/reset",
        method: "POST",
        data: {
            v: videoId,
        },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                updateUI(videoId, false); // Reset progress
            }
        },
        error: function (xhr, status, error) {
            console.log("Error:", error);
        },
    });
}

// handle checkbox change
function handleCheckboxChange(checkbox) {
    const videoId = checkbox.data("video-id");
    const isCompleted = checkbox.prop("checked");
    if (isCompleted) markAsCompleted(videoId);
    else resetProgress(videoId);
}

// Common function to update UI for both markAsCompleted and resetProgress
function updateUI(videoId, isCompleted) {
    const isCurrentVideo = videoId === globalVideoId;

    if (isCurrentVideo) {
        // Update markCompletedBtn state
        markCompletedBtn.data("completed", isCompleted);
        markCompletedBtn
            .find(".check-icon")
            .toggleClass("display-none", isCompleted);
        markCompletedBtn
            .find(".checked-icon")
            .toggleClass("display-none", !isCompleted);
        markCompletedBtn
            .find("span")
            .text(isCompleted ? "Marked as completed" : "Mark as completed");
    }

    // Update the video element checkbox in sidebar
    videoContainer
        .find(`[data-video-id="${videoId}"]`)
        .attr("checked", isCompleted);
}

function isCurrentVideoMarked() {
    return markCompletedBtn.data("completed");
}
