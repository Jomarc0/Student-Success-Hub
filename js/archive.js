document.querySelector(".search-input").addEventListener("input", function() {
    const searchTerm = this.value.toLowerCase();
    const items = document.querySelectorAll(".info-item:not(.header)");

    let noResults = true;

    items.forEach(item => {
        const name = item.getAttribute("data-name").toLowerCase();
        const date = item.getAttribute("data-date").toLowerCase();
        const srCode = item.getAttribute("data-sr-code").toLowerCase();

        if (name.includes(searchTerm) || date.includes(searchTerm) || srCode.includes(searchTerm)) {
            item.classList.remove("hidden");
            noResults = false;
        } else {
            item.classList.add("hidden");
        }
    });

    const container = document.querySelector(".info-container");
    const noResultsMessage = document.querySelector(".no-results-message");

    if (noResults) {
        if (!noResultsMessage) {
            const message = document.createElement("div");
            message.classList.add("no-results-message");
            message.textContent = "No student records found.";
            container.appendChild(message);
        }
    } else {
        if (noResultsMessage) noResultsMessage.remove();
    }
});