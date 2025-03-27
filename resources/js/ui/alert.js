export default function createAlert({
    type = "default",
    title,
    message,
    closeable,
    timeout,
}) {
    const alertEl = document.createElement("div");

    const typeClasses = {
        default: "alert-default",
        success: "alert-success",
        error: "alert-error",
        warning: "alert-warning",
        info: "alert-info",
        noIcon: "alert-no-icon",
    };

    const typeClass = typeClasses[type] || "alert-default";

    const icons = {
        success: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
        default:
            "m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z",
        error: "M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z",
        warning:
            "M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z",
        info: "m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z",
    };

    alertEl.className = `alert-base ${typeClass} relative`;
    alertEl.innerHTML = `
        <div class="flex">
            ${
                type !== "noIcon"
                    ? `
                <div class="mt-1 flex-shrink-0">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                            icons[type] || icons.default
                        }" />
                    </svg>
                </div>
            `
                    : ""
            }
            <div class="${type !== "noIcon" ? "ml-3" : ""} flex-1">
                ${
                    title
                        ? `<h3 class="text-sm font-medium text-gray-800 dark:text-gray-200">${title}</h3>`
                        : ""
                }
                <p class="mt-1 text-sm">${message}</p>
            </div>
            ${
                closeable
                    ? `
                <button class="absolute top-3 right-3 flex-shrink-0 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            `
                    : ""
            }
        </div>
    `;

    if (closeable) {
        const closeBtn = alertEl.querySelector("button");
        if (closeBtn) {
            closeBtn.addEventListener("click", () => alertEl.remove());
        }
    }

    if (typeof timeout === "number") {
        setTimeout(() => alertEl.remove(), timeout);
    }

    return alertEl;
}
