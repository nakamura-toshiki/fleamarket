document.addEventListener("DOMContentLoaded", () => {
    const selectedOption = document.getElementById("selectedOptionSell");
    const optionsList = document.getElementById("optionsListSell");
    const hiddenInput = document.getElementById("condition");

    if (hiddenInput.value) {
        selectedOptionSell.textContent = hiddenInput.value;
    }

    selectedOption.addEventListener("click", () => {
        const isHidden = optionsList.style.display === "none";
        optionsList.style.display = isHidden ? "block" : "none";
    });

    document.querySelectorAll(".option-item").forEach(option => {
        option.addEventListener("click", () => {
            const selectedValue = option.dataset.value;
            selectedOption.textContent = selectedValue;
            hiddenInput.value = selectedValue;
            optionsList.style.display = "none";
        });
    });

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".content-item__select")) {
            optionsList.style.display = "none";
        }
    });
});