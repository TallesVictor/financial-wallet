document.querySelector("#transferForm").addEventListener("submit", (event) => {
    event.preventDefault();
    formTransfer();
});

async function formTransfer() {
    const form = document.querySelector("#transferForm");
    const formData = new FormData(form);

    formData.set("amount", convertNumeric(formData.get("amount")));
    showSpinner();
    try {
        const response = await fetch("/api/transaction/transfer", {
            method: "POST",
            body: formData,
            headers: { Authorization: `Bearer ${TOKEN}` },
        });

        let data = await response.json();
        hideSpinner();

        if (response.status === 201) {
            $("#responseMessage").text("Transferencia realizada com sucesso!");
            form.reset();
            getBalance();
            setTimeout(() => {
                $("#responseMessage").text("");
            }, 5000);
        } else {
            $("#responseMessage").text(data.message);
        }

    } catch (e) {
        console.error(e);
    }
}