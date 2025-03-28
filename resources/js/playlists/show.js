import $ from "jquery";
import { initDropdowns } from "../ui/dropdown";
import createAlert from "../ui/alert";

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
            url: `/api/videos`,
            method: "GET",
            data: {
                playlist_id: playlistId,
                page: currentPage,
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

            // Trigger fetch if scrolled near the bottom (buffer of 100px)
            if (scrollHeight - scrollTop <= elementHeight + 100) {
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
                redirectToPlaylistIndexPage(response.message);
            } else {
                showDefaultErrorAlert();
            }
        },
        error: function (response) {
            showDefaultErrorAlert();
        },
    });
}

function redirectToPlaylistIndexPage(message) {
    const url = new URL(`${window.location.origin}/playlists`);
    url.searchParams.set("message", encodeURIComponent(message));

    window.location.href = url.toString();
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
