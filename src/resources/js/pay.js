document.addEventListener("DOMContentLoaded", () => {
    const selectedOption = document.getElementById("selectedOption");
    const optionsList = document.getElementById("optionsList");
    const hiddenInput = document.getElementById("pay");
    const paymentDetail = document.getElementById("selected-payment");

    selectedOption.addEventListener("click", () => {
        const isHidden = optionsList.style.display === "none";
        optionsList.style.display = isHidden ? "block" : "none";
    });

    document.querySelectorAll(".option-item").forEach(option => {
        option.addEventListener("click", () => {
            selectedOption.textContent = option.textContent;
            hiddenInput.value = option.dataset.value;
            paymentDetail.textContent = option.textContent;
            optionsList.style.display = "none";
        });
    });

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".content-pay")) {
            optionsList.style.display = "none";
        }
    });
});