const TOKEN = localStorage.getItem("token_financial_wallet");

getBalance();

document.querySelector("#depositForm").addEventListener("submit", (event) => {
    event.preventDefault();
    formDeposit();
});

async function getBalance() {
    showSpinner();
    try {
        const response = await fetch(`/api/user/balance`, {
            method: "GET",
            headers: { Authorization: `Bearer ${TOKEN}` }
        });
        let data = await response.json();
        hideSpinner();
        if (response.status === 200) {
            MYBALANCE = data.balance;
            $("#myBalance").html(`${maskMoney(data.balance)}`);
        } else {
            // showNotification(data.message, true);
            console.error(data);
        }

    } catch (e) {
        console.error(e);
    }
}

async function formDeposit() {
    const form = document.querySelector("#depositForm");
    const formData = new FormData(form);

    formData.set("amount", convertNumeric(formData.get("amount")));
    showSpinner();

    try {
        const response = await fetch("/api/transaction/deposit", {
            method: "POST",
            body: formData,
            headers: { Authorization: `Bearer ${TOKEN}` },
        });

        let data = await response.json();
        hideSpinner();

        if (response.status === 201) {
            $("#responseMessage").text("Depósito realizado com sucesso!");

            form.reset();
            getBalance();
            setTimeout(() => {
                $("#responseMessage").text("");
            }, 3500);
        } else {
            $("#responseMessage").text(data.message);
        }

    } catch (e) {
        console.error(e);
    }
}

function hideSpinner() {
    $("#divDeposit").removeClass("d-none");
    $("#loadingSpinner").addClass("d-none");
}

function showSpinner() {
    $("#divDeposit").addClass("d-none");
    $("#loadingSpinner").removeClass("d-none");
}