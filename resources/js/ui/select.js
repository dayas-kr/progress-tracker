import { disableScrolling, enableScrolling } from "./utils/scroll";

export function initSelects(container = document) {
    container.querySelectorAll(".custom-select").forEach((customSelect) => {
        // Skip if already initialized (optional safeguard).
        if (customSelect.dataset.selectInitialized === "true") return;
        customSelect.dataset.selectInitialized = "true";

        let selectOpen = false;
        let selectedItem = null;
        let selectableItemActive = null;
        const selectKeydownTimeout = 1000;
        let selectKeydownValue = "";
        let selectKeydownClearTimeout = null;
        let selectionChanged = false;

        // Element references
        const selectButton = customSelect.querySelector(".selectButton");
        const selectedText = customSelect.querySelector(".selectedText");
        const dropdownList = customSelect.querySelector(".dropdownList");
        const input = customSelect.querySelector(".selectInput");

        // Helper function to remove placeholder style.
        function removePlaceholderStyle() {
            selectionChanged = true;
            const placeholderSpan = selectButton.querySelector(
                "span[data-placeholder]"
            );
            if (placeholderSpan) {
                placeholderSpan.classList.remove("text-zinc-500");
            }
        }

        // Gather list items and build selectable items array.
        const liElements = Array.from(
            customSelect.querySelectorAll(".selectItem")
        );
        const selectableItems = liElements.map((li) => ({
            title: li.textContent.trim(),
            value: li.getAttribute("data-value"),
            disabled: li.getAttribute("data-disabled") === "true",
        }));

        // Add click and mousemove events on each list item.
        liElements.forEach((li) => {
            li.addEventListener("click", function () {
                if (li.getAttribute("data-disabled") === "true") return;
                const value = li.getAttribute("data-value");
                const title = li.textContent.trim();
                selectedItem = { title, value, disabled: false };
                selectableItemActive = selectedItem;
                updateSelectedText();
                closeSelectDropdown();
                selectButton.focus(); // Focus trigger button after selection

                if (!selectionChanged) {
                    removePlaceholderStyle();
                }
            });
            li.addEventListener("mousemove", function () {
                if (li.getAttribute("data-disabled") === "true") return;
                const value = li.getAttribute("data-value");
                const title = li.textContent.trim();
                selectableItemActive = { title, value, disabled: false };
                updateDropdownActive();
            });
        });

        // Update active styling on list items.
        function updateDropdownActive() {
            liElements.forEach((li) => {
                const itemValue = li.getAttribute("data-value");
                li.setAttribute(
                    "data-active",
                    itemValue === selectableItemActive?.value
                );
            });
        }

        // Update the displayed text and hidden input.
        function updateSelectedText() {
            selectedText.textContent = selectedItem
                ? selectedItem.title
                : "Select an option";
            if (input) {
                input.value = selectedItem ? selectedItem.value : "";
            }
        }

        // Scroll the active item into view based on navigation direction.
        // When navigating down, align active item’s bottom with the dropdown’s bottom.
        // When navigating up, align active item’s top with the dropdown’s top.
        function scrollActiveIntoView(direction) {
            if (!selectableItemActive) return;
            const activeElement = customSelect.querySelector(
                `.selectItem[data-value="${selectableItemActive.value}"]`
            );
            if (activeElement) {
                if (direction === "down") {
                    const offset =
                        activeElement.offsetTop +
                        activeElement.offsetHeight -
                        dropdownList.clientHeight;
                    dropdownList.scrollTop = offset;
                } else if (direction === "up") {
                    dropdownList.scrollTop = activeElement.offsetTop;
                }
            }
        }

        // Wheel event handler for the dropdown.
        function stopPropagationWheel(e) {
            const delta = e.deltaY;
            const atTop = dropdownList.scrollTop === 0;
            const atBottom =
                dropdownList.scrollTop + dropdownList.clientHeight >=
                dropdownList.scrollHeight - 1;
            if ((delta < 0 && atTop) || (delta > 0 && atBottom)) {
                e.preventDefault();
            }
            e.stopPropagation();
        }

        // Close select dropdown when any other dropdown is opened.
        document.addEventListener("dropdown:open", () => closeSelectDropdown());

        // Open the dropdown list.
        function openSelectDropdown() {
            if (selectOpen) return;
            selectOpen = true;
            disableScrolling();
            dropdownList.classList.remove("hidden");
            dropdownList.addEventListener("wheel", stopPropagationWheel, {
                passive: false,
            });
            // Set active item to current selection or first item.
            selectableItemActive = selectedItem
                ? selectedItem
                : selectableItems[0];
            updateDropdownActive();
            selectPositionUpdate();
            dropdownList.focus();
        }

        // Close the dropdown list.
        function closeSelectDropdown() {
            if (!selectOpen) return;
            enableScrolling();
            selectOpen = false;
            dropdownList.classList.add("hidden");
            dropdownList.removeEventListener("wheel", stopPropagationWheel, {
                passive: false,
            });
        }

        // Navigate to the next selectable item.
        function selectableItemActiveNext() {
            let index = selectableItems.findIndex(
                (item) =>
                    selectableItemActive &&
                    item.value === selectableItemActive.value
            );
            if (index < selectableItems.length - 1) {
                selectableItemActive = selectableItems[index + 1];
                updateDropdownActive();
                scrollActiveIntoView("down");
            }
        }

        // Navigate to the previous selectable item.
        function selectableItemActivePrevious() {
            let index = selectableItems.findIndex(
                (item) =>
                    selectableItemActive &&
                    item.value === selectableItemActive.value
            );
            if (index > 0) {
                selectableItemActive = selectableItems[index - 1];
                updateDropdownActive();
                scrollActiveIntoView("up");
            }
        }

        // Type-ahead search functionality.
        function selectKeydown(e) {
            if (e.keyCode >= 65 && e.keyCode <= 90) {
                selectKeydownValue += e.key.toLowerCase();
                const bestMatch = selectItemsFindBestMatch();
                if (bestMatch) {
                    if (selectOpen) {
                        selectableItemActive = bestMatch;
                        updateDropdownActive();
                    } else {
                        selectedItem = bestMatch;
                        selectableItemActive = bestMatch;
                        updateSelectedText();
                    }
                }
                clearTimeout(selectKeydownClearTimeout);
                selectKeydownClearTimeout = setTimeout(() => {
                    selectKeydownValue = "";
                }, selectKeydownTimeout);
            }
        }

        // Find best matching item for the typed value.
        function selectItemsFindBestMatch() {
            const typedValue = selectKeydownValue.toLowerCase();
            let bestMatch = null;
            let bestMatchIndex = -1;
            for (let i = 0; i < selectableItems.length; i++) {
                const title = selectableItems[i].title.toLowerCase();
                const index = title.indexOf(typedValue);
                if (
                    index > -1 &&
                    (bestMatchIndex === -1 || index < bestMatchIndex) &&
                    !selectableItems[i].disabled
                ) {
                    bestMatch = selectableItems[i];
                    bestMatchIndex = index;
                }
            }
            return bestMatch;
        }

        // Update dropdown position based on available space.
        function selectPositionUpdate() {
            const buttonRect = selectButton.getBoundingClientRect();
            const listMaxHeight =
                parseInt(window.getComputedStyle(dropdownList).maxHeight) ||
                224;
            const selectDropdownBottomPos =
                buttonRect.top + selectButton.offsetHeight + listMaxHeight;
            dropdownList.setAttribute(
                "data-dropdown-position",
                window.innerHeight < selectDropdownBottomPos ? "top" : "bottom"
            );
        }

        // --- Keyboard event listeners ---

        selectButton.addEventListener("keydown", (e) => {
            if (e.key === "ArrowDown" || e.key === "ArrowUp") {
                openSelectDropdown();
                e.preventDefault();
            } else {
                selectKeydown(e);
            }
        });

        dropdownList.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                closeSelectDropdown();
                selectButton.focus();
            } else if (e.key === "ArrowDown") {
                selectableItemActiveNext();
                e.preventDefault();
            } else if (e.key === "ArrowUp") {
                selectableItemActivePrevious();
                e.preventDefault();
            } else if (e.key === "Enter") {
                if (selectableItemActive) {
                    selectedItem = selectableItemActive;
                    updateSelectedText();
                    closeSelectDropdown();
                    e.preventDefault();
                    if (!selectionChanged) {
                        removePlaceholderStyle();
                    }
                }
            } else {
                selectKeydown(e);
            }
        });

        // --- Mouse event listeners ---

        selectButton.addEventListener("click", () => {
            if (selectOpen) closeSelectDropdown();
            else openSelectDropdown();
        });

        // --- Outside click listener ---
        document.addEventListener("click", (e) => {
            if (!customSelect.contains(e.target)) closeSelectDropdown();
        });

        window.addEventListener("resize", selectPositionUpdate);

        updateSelectedText();
    });
}
