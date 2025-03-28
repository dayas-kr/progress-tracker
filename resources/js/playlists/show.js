import $ from "jquery";
import { initDropdowns } from "../ui/dropdown";
import createAlert from "../ui/alert";
import {
    setItemWithExpiration,
    getItemWithExpiration,
    deleteSessionItem,
} from "../utils/session";

const csrfToken = $('meta[name="csrf-token"]').attr("content");
const playlistVideoContainer = $("#playlist-videos");
const playlistVideosWrapper = $("#playlist-videos-container");
const totalVideoCount = playlistVideosWrapper.attr("data-video-count");
const playlistId = playlistVideoContainer.attr("data-playlist-id");
const deletePlaylistBtn = $("#delete-playlist-confirm");
const alertContainer = $("#alert-container");

let currentPage = 1;
let isLoading = false;

document.addEventListener("DOMContentLoaded", () => {
    handleRedirectMessage();

    listenForScrollEnd();

    deletePlaylistBtn.on("click", () => {
        deletePlaylist(playlistId);
    });
});
// delete-playlist-confirm

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
