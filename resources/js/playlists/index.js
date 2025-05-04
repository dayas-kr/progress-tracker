import $ from "jquery";
import createAlert from "../ui/alert";
import { getItemWithExpiration, deleteSessionItem } from "../utils/session";

const csrfToken = $('meta[name="csrf-token"]').attr("content");
const playlistGrid = $("#playlists-grid");
const alertContainer = $("#alert-container");
const searchInput = $("#search-input");

let currentPage = 1;
let isLoading = false;

document.addEventListener("DOMContentLoaded", () => {
    handleRedirectMessage();
    listenForScrollEnd();

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

function listenForScrollEnd() {
    let scrollElement = $(window);

    function handleScrollEnd() {
        if (isLoading) return;
        isLoading = true;
        currentPage++;

        $.ajax({
            url: `/api/playlists`,
            method: "GET",
            data: {
                page: currentPage,
                _token: csrfToken,
            },
            beforeSend: function () {
                // Show the loading spinner
                $("#playlist-loading-spinner").show();
                console.log("Loading more playlists...");
            },
        })
            .done(function (response) {
                if (response.data.html) {
                    // playlistVideoContainer.append(response.data.html);
                    playlistGrid.append(response.data.html);
                }
                // If there's no next page, detach the scroll event listener
                if (!response.next_page_url) {
                    scrollElement.off("scroll", listenForScrollEnd);
                    console.log("No more playlists.");
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.error("Error loading playlists:", textStatus);
            })
            .always(function () {
                $("#playlist-loading-spinner").hide();
                isLoading = false;
            });
    }

    function listenForScrollEnd() {
        // Only proceed if more playlists are expected
        if (playlistGrid.attr("data-playlist-count") >= 10) {
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
