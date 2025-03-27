import $ from "jquery";

function ensureContainerExists() {
    if ($("#alert-container-left").length === 0) {
        $("body").append(
            '<div id="alert-container-left" class="fixed bottom-4 left-4 space-y-2"></div>'
        );
    }
}

function createToast(message, options) {
    ensureContainerExists();

    const { className, duration, dataAttr } = options;
    const alertBox = $("<div>", {
        class: `yt-alert-popup bg-[#0F0F0F] dark:bg-[#F1F1F1] h-11 rounded-lg text-sm flex items-center justify-between gap-6 dark:text-zinc-900 text-white font-medium py-2 px-4.5 transform transition-all ${className}`,
        text: message,
    })
        .attr(dataAttr, "")
        .appendTo("#alert-container-left");

    setTimeout(
        () =>
            alertBox.removeClass(options.hideClass).addClass(options.showClass),
        10
    );

    setTimeout(() => {
        alertBox.removeClass(options.showClass).addClass(options.hideClass);
        setTimeout(() => alertBox.remove(), options.fadeOutTime);
    }, duration);
}

export function showToast(message) {
    createToast(message, {
        className: "duration-500 ease-in-out translate-y-16 opacity-0",
        showClass: "translate-y-0 opacity-100",
        hideClass: "translate-y-16 opacity-0",
        dataAttr: "data-toast",
        duration: 4000,
        fadeOutTime: 500,
    });
}

export function showSimpleToast(message) {
    ensureContainerExists();

    $(".yt-alert-popup[data-simple-toast]")
        .removeClass("translate-y-0 opacity-100")
        .addClass("translate-y-4 opacity-0")
        .delay(300)
        .queue(function () {
            $(this).remove();
        });

    createToast(message, {
        className: "duration-300 ease-in-out translate-y-4 opacity-0",
        showClass: "translate-y-0 opacity-100",
        hideClass: "translate-y-4 opacity-0",
        dataAttr: "data-simple-toast",
        duration: 3000,
        fadeOutTime: 300,
    });
}
