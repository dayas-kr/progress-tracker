import $ from "jquery";
import createAlert from "../ui/alert";
import { setItemWithExpiration } from "../utils/session";

const button = $("[data-submit-button]");
const submitBtnSpinner = button.find($("[data-loading-spinner]"));
const playlistLinkError = $("#playlist-link-error");
const playlistLinkInput = $("#playlist-link");
const resetFormBtn = $("#reset-form-btn");
const alertContainer = $("#alert-container");

// Playlist Info Card
const playlistInfoCard = $("#playlist-info-card");
const playlistAlreadyExistsMsg = $("#playlist-already-exists-msg");
const addPlaylistBtn = $("#add-playlist-btn");
const addPlaylistBtnSpinner = addPlaylistBtn.find($("[data-loading-spinner]"));

// Skeleton
const playlistInfoSkeleton = $("#playlist-info-skeleton");

document.addEventListener("DOMContentLoaded", () => {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const fetchPlaylistForm = $("#fetch-playlist-form");

    // Handle form submission
    fetchPlaylistForm.on("submit", (e) => {
        e.preventDefault();
        const playlistID = extractPlaylistId(playlistLinkInput.val().trim());

        if (playlistID) {
            toggleDisabled(button, true);
            toggleDisabled(playlistLinkInput, true);
            submitBtnSpinner.removeClass("hidden");
            playlistLinkError.addClass("hidden");
            fetchPlaylist(playlistID, csrfToken);
        } else {
            showPlaylistLinkError("Playlist link or ID is required.");
        }
    });

    resetFormBtn.on("click", resetForm);
});

// Fetch playlist
function fetchPlaylist(playlistID, csrfToken) {
    $.ajax({
        url: `/api/playlists/info`,
        type: "GET",
        data: { _token: csrfToken, list: playlistID },
        beforeSend: () => {
            playlistInfoSkeleton.removeClass("hidden");
            resetFormBtn.removeClass("hidden");
        },
        success: (response) => {
            playlistInfoSkeleton.addClass("hidden");
            submitBtnSpinner.addClass("hidden");
            playlistInfoCard.removeClass("hidden");

            if (response.exists) {
                playlistAlreadyExistsMsg.removeClass("hidden");
                toggleDisabled(addPlaylistBtn, true);
            } else {
                addPlaylistBtn.on("click", () => {
                    addToDatabase(response.data, csrfToken);
                });
            }

            updatePlaylistInfo(response.data);
        },
        error: (xhr) => {
            console.error("Error:", xhr.status, xhr.responseText);
            let errorResponse = {};
            try {
                errorResponse = JSON.parse(xhr.responseText);
            } catch (e) {
                // Fallback if response is not JSON
                showDefaultErrorAlert();
            }

            resetForm();

            if (errorResponse.status === 404) {
                alertContainer.append(
                    createAlert({
                        type: "error",
                        title: "Playlist not found",
                        message: "Playlist with given ID does not exist.",
                        timeout: 5000,
                    })
                );
            } else {
                showDefaultErrorAlert();
            }
        },
    });
}

// Add playlist to database
function addToDatabase(playlistData, csrfToken) {
    $.ajax({
        type: "POST",
        url: "/api/playlists",
        data: {
            _token: csrfToken,
            playlist_data: JSON.stringify(playlistData),
        },
        beforeSend: () => {
            toggleDisabled(addPlaylistBtn, true);
            addPlaylistBtn.find("span").text("Adding to Database...");
            addPlaylistBtnSpinner.removeClass("hidden");
        },
        success: (response) => {
            if (response.success) {
                redirectToPlaylistPage(response.playlistId, response.message);
            }
        },
        error: (xhr, status, error) => {
            console.log(error);
            console.log(xhr);
            console.log(status);
            setTimeout(() => {
                toggleDisabled(addPlaylistBtn, false);
                addPlaylistBtn.find("span").text("Add to Database");
                addPlaylistBtnSpinner.addClass("hidden");
            }, 250);
            if (alertContainer.children().length === 0) {
                alertContainer.append(
                    createAlert({
                        type: "error",
                        title: "Could not add playlist to database",
                        message:
                            "An error occurred while processing your request.",
                        timeout: 5000,
                    })
                );
            }
        },
    });
}

// --- Helper Functions ---

// Extract playlist ID from playlist link
function extractPlaylistId(input) {
    try {
        let url = new URL(input);
        return url.searchParams.get("list") || input;
    } catch (e) {
        return input;
    }
}

// Disable or Enable element
function toggleDisabled(element, disabled) {
    element.prop("disabled", disabled ?? false);
}

// Single function to update playlist and channel info
function updatePlaylistInfo(response) {
    $("#playlist-id").text(response.playlistId);
    $("#playlist-title").text(response.playlistTitle);
    $("#playlist-info-title").text(response.playlistTitle);

    // Update description with fallback if empty
    const descriptionText =
        response.playlistDescription !== ""
            ? response.playlistDescription
            : '<span class="opacity-50">No Description</span>';
    $("#playlist-description").html(descriptionText);

    // Update playlist thumbnail
    $("#playlist-thumbnail").attr("src", response.playlistImages.medium.url);

    // Update channel title and URL
    $("#channel-title").text(response.channelTitle);
    const channelLink = `<a href="${response.channelUrl}" target="_blank" class="text-blue-500 dark:text-blue-400 hover:underline font-medium">${response.channelTitle}</a>`;
    $("#channel-url").html(channelLink);

    // Update subscriber and video count
    $("#subscriber-count").text(response.subscriberCount);
    $("#video-count").text(response.videoCount);
    $("#playlist-video-count").text(response.videoCount);

    // Update channel thumbnail
    $("#channel-thumbnail").attr("src", response.channelImages.medium.url);
}

// Reset form
function resetForm() {
    toggleDisabled(playlistLinkInput, false);
    toggleDisabled(button, false);
    submitBtnSpinner.addClass("hidden");
    playlistInfoSkeleton.addClass("hidden");
    playlistInfoCard.addClass("hidden");
    resetFormBtn.addClass("hidden");
    playlistLinkInput.trigger("focus");
    playlistAlreadyExistsMsg.addClass("hidden");
    toggleDisabled(addPlaylistBtn, false);
    playlistLinkInput.val("");
    toggleDisabled(addPlaylistBtn, false);
    addPlaylistBtn.find("span").text("Add to Database");
    addPlaylistBtnSpinner.addClass("hidden");
}

function showPlaylistLinkError(message) {
    playlistLinkError.removeClass("hidden");
    playlistLinkError.text(message);
}

function showDefaultErrorAlert() {
    alertContainer.append(
        createAlert({
            type: "error",
            title: "Error",
            message: "Something went wrong. Please try again.",
            timeout: 5000,
        })
    );
}

function redirectToPlaylistPage(playlistId, message) {
    setItemWithExpiration("playlistMessage_show", message, 15);
    window.location.href = `${window.location.origin}/playlist?list=${playlistId}`;
}
