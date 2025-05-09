import $ from "jquery";
import { initDropdowns } from "../ui/dropdown";
import createAlert from "../ui/alert";
import {
    setItemWithExpiration,
    getItemWithExpiration,
    deleteSessionItem,
} from "../utils/session";

const csrfToken = $('meta[name="csrf-token"]').attr("content");
const playlistVideosWrapper = $("#playlist-videos-container");
const totalVideoCount = playlistVideosWrapper.attr("data-video-count");
const playlistVideoContainer = $("#playlist-videos");
const playlistId = playlistVideoContainer.attr("data-playlist-id");
const deletePlaylistBtn = $("#delete-confirm");
const markCompleteBtn = $("#mark-completed");
const resetProgressBtn = $("#reset-progress-confirm");
const alertContainer = $("#alert-container");
const searchInput = $("#search-input");

let currentPage = 1;
let isLoading = false;

document.addEventListener("DOMContentLoaded", () => {
    handleRedirectMessage();

    listenForScrollEnd();

    deletePlaylistBtn.on("click", () => {
        deletePlaylist(playlistId);
    });

    markCompleteBtn.on("click", () => {
        if (!isPlaylistCompleted()) {
            markPlaylistComplete(playlistId);
        }
    });

    resetProgressBtn.on("click", () => {
        if (!isPlaylistReseted()) {
            resetPlaylistProgress(playlistId);
        }
    });

    playlistVideoContainer.on("click", "[data-mark-completed]", function () {
        if (!isVideoCompleted($(this))) {
            markVideoAsCompleted($(this).data("video-id"), $(this));
        }
    });

    playlistVideoContainer.on("click", "[data-reset-progress]", function () {
        if (!isVideoReseted($(this))) {
            resetVideoProgress($(this).data("video-id"), $(this));
        }
    });

    // Search Playlists
    document.addEventListener("keydown", (e) => {
        // CMD+K to focus the input
        if (e.key === "k" && e.metaKey) {
            searchInput.trigger("focus");
        }

        // Escape to clear the input if it's focused
        if (e.key === "Escape" && document.activeElement === searchInput[0]) {
            searchInput.val("");
        }

        // Enter key to search (only if input is focused)
        if (e.key === "Enter" && document.activeElement === searchInput[0]) {
            const val = searchInput.val();
            if (val && val.length >= 3) {
                // TODO: Search for playlist
            }
        }
    });
});

// functioon for infinite scroll
function listenForScrollEnd() {
    // Use window scroll for smaller viewports, otherwise use the container's scroll event
    let scrollElement = $(window);
    if ($(window).width() >= 1080) {
        scrollElement = playlistVideosWrapper;
    }

    function handleScrollEnd() {
        if (isLoading) return;
        isLoading = true;
        currentPage++;

        $.ajax({
            url: `/api/playlist/${playlistId}/videos`,
            method: "GET",
            data: {
                page: currentPage,
                _token: csrfToken,
            },
            beforeSend: function () {
                // Show the loading spinner
                $("#playlist-loading-spinner").show();
                console.log("Loading more videos...");
            },
        })
            .done(function (response) {
                if (response.data.html) {
                    playlistVideoContainer.append(response.data.html);
                }
                // Reinitialize dropdowns within the container holding new content.
                initDropdowns(
                    document.getElementById("playlist-videos-container")
                );
                // If there's no next page, detach the scroll event listener
                if (!response.next_page_url) {
                    scrollElement.off("scroll", listenForScrollEnd);
                    console.log("No more videos.");
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.error("Error loading videos:", textStatus);
            })
            .always(function () {
                $("#playlist-loading-spinner").hide();
                isLoading = false;
            });
    }

    function listenForScrollEnd() {
        // Only proceed if more videos are expected
        if (totalVideoCount >= 10) {
            let scrollHeight, scrollTop, elementHeight;
            if (scrollElement.is($(window))) {
                scrollHeight = $(document).height();
                scrollTop = $(window).scrollTop();
                elementHeight = $(window).height();
            } else {
                scrollHeight = scrollElement[0].scrollHeight;
                scrollTop = scrollElement.scrollTop();
                elementHeight = scrollElement.outerHeight();
            }

            // Trigger fetch if scrolled near the bottom (buffer of 24px)
            if (scrollHeight - scrollTop <= elementHeight + 24) {
                handleScrollEnd();
            }
        }
    }

    // Attach the scroll event listener to the chosen scroll element
    scrollElement.on("scroll", listenForScrollEnd);
}

// delete playlist
function deletePlaylist(playlistId) {
    $.ajax({
        url: "/playlist/" + playlistId, // Send the request to the correct URL
        type: "DELETE",
        data: {
            _token: csrfToken,
        },
        success: function (response) {
            if (response.success) {
                redirectToIndexPage(response.message);
            } else {
                showDefaultErrorAlert();
            }
        },
        error: function (response) {
            showDefaultErrorAlert();
        },
    });
}

function showDefaultErrorAlert(title, message) {
    alertContainer.append(
        createAlert({
            type: "error",
            title: title || "Error",
            message: message || "Something went wrong. Please try again.",
            timeout: 5000,
        })
    );
}

function handleRedirectMessage() {
    const message = getItemWithExpiration("playlistMessage_show");
    if (message) {
        alertContainer.append(
            createAlert({
                type: "success",
                message: message,
                timeout: 5000,
            })
        );
        deleteSessionItem("playlistMessage_show");
    }
}

function redirectToIndexPage(message) {
    setItemWithExpiration("playlistMessage_index", message, 15);
    window.location.href = `${window.location.origin}/playlists`;
}

function markPlaylistComplete(playlistId) {
    $.ajax({
        url: "/api/playlists/complete",
        type: "POST",
        data: { list: playlistId },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                markCompleteBtn.data("completed", true);
                resetProgressBtn.data("reseted", false);
                $("#completed-count").text(totalVideoCount);
                $("#progress").text("100%");
                $("#remaing-duration").text("0:00");
                playlistVideoContainer.find(".video-info").each(function () {
                    if ($(this).find(".completed-badge").length === 0) {
                        $(this).append(createCompletedBadge());
                    }
                });

                markCompleteBtn
                    .find(".flex.justify-between")
                    .append(createCompletedIcon());
            } else {
                //
            }
        },
        error: function (response) {
            console.log(response);
        },
    });
}

function resetPlaylistProgress(playlistId) {
    $.ajax({
        url: "/api/playlists/reset",
        type: "POST",
        data: { list: playlistId },
        success: function (response) {
            if (response.status === "success") {
                console.log(response);
                markCompleteBtn.data("completed", false);
                resetProgressBtn.data("reseted", true);
                $("#completed-count").text("0");
                $("#progress").text("0%");
                $("#remaing-duration").text($("#total-duration").text());
                setTimeout(function () {
                    playlistVideoContainer
                        .find(".video-info")
                        .each(function () {
                            $(this).find(".completed-badge").remove();
                        });
                }, 100); // Adjust delay as needed
                markCompleteBtn.find("i").remove();
            } else {
                //
            }
        },
        error: function (response) {
            console.log(response);
        },
    });
}

function createCompletedBadge() {
    return $("<div>", {
        class: "badge px-2 h-6 text-xs gap-1.5 green-subtle completed-badge",
    })
        .append($("<i>", { class: "fa-regular fa-circle-check" }))
        .append($("<span>", { class: "hidden sm:inline", text: "Completed" }));
}

function isPlaylistCompleted() {
    return markCompleteBtn.data("completed");
}

function isPlaylistReseted() {
    return resetProgressBtn.data("reseted");
}

function markVideoAsCompleted(videoId, SelectItem) {
    $.ajax({
        url: "/api/videos/complete",
        method: "POST",
        data: { v: videoId, advancedInfo: true },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                SelectItem.data("marked", true);
                SelectItem.next("[data-reset-progress]").data("reseted", false);
                const videCard = $(
                    `[data-video-card][data-video-id="${videoId}"]`
                );
                videCard.find(".video-info").append(createCompletedBadge());
                $("#completed-count").text(response.data.completedVideoCount);
                $("#progress").text(response.data.progress + "%");
                $("#remaing-duration").text(response.data.remainingDuration);

                if (response.data.progress === 100) {
                    markCompleteBtn.data("completed", true);
                    resetProgressBtn.data("reseted", false);
                    markCompleteBtn
                        .find(".flex.justify-between")
                        .append(createCompletedIcon());
                }
            }
        },
        error: function (xhr, status, error) {
            console.log("Error:", error);
        },
    });
}

function resetVideoProgress(videoId, SelectItem) {
    $.ajax({
        url: "/api/videos/reset",
        method: "POST",
        data: { v: videoId, advancedInfo: true },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                SelectItem.data("reseted", true);
                SelectItem.prev("[data-mark-completed]").data("marked", false);
                const videCard = $(
                    `[data-video-card][data-video-id="${videoId}"]`
                );
                videCard.find(".video-info").find(".completed-badge").remove();
                $("#completed-count").text(response.data.completedVideoCount);
                $("#progress").text(response.data.progress + "%");
                $("#remaing-duration").text(response.data.remainingDuration);
                markCompleteBtn.find("i").remove();
                markCompleteBtn.data("completed", false);
            }
        },
        error: function (xhr, status, error) {
            console.log("Error:", error);
        },
    });
}

function isVideoCompleted(element) {
    return element.data("marked");
}

function isVideoReseted(element) {
    return element.data("reseted");
}

function createCompletedIcon() {
    return $("<i>", {
        class: "fa-solid fa-circle-check text-[1.025rem]",
    });
}
