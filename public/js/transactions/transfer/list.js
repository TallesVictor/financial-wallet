const TOKEN = localStorage.getItem("token_financial_wallet");
let MYBALANCE = 0;
getBalance();
listUsers();
// $('.money').mask("#.##0,00", {reverse: true});

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


async function listUsers() {
    try {
        const params = {
            not_me: 1
        };
        const queryString = new URLSearchParams(params).toString();

        const response = await fetch(`/api/users?${queryString}`, {
            method: "GET",
            headers: { Authorization: `Bearer ${TOKEN}` }
        });
        let data = await response.json();

        hideSpinner();

        if (response.status === 200) {
            constructUsers(data);
        } else {
            // showNotification(data.message, true);
            console.error(data);
        }

    } catch (e) {
        console.error(e);
    }
}

function constructUsers(data) {
    let options = "<option value=''>Selecione um usuário</option>";
    data.forEach(user => {
        options += `<option value="${user.id}">${user.name} (${user.email})</option>`;
    });

    $("#receipientUser").html(options);
}

$("#amount").on("input", function () {
    convertToMoney(this);

    const value = convertNumeric($(this).val());

    if (value > MYBALANCE) {
        $("#amount").addClass("is-invalid");
        $("#responseMessage").text("Valor maior que o seu saldo atual!");
        $("#transferButton").prop("disabled", true);
    } else {
        $("#amount").removeClass("is-invalid");
        $("#responseMessage").text("");
        $("#transferButton").prop("disabled", false);
    }
});

function hideSpinner() {
    $("#divTransfer").removeClass("d-none");
    $("#loadingSpinner").addClass("d-none");
}

function showSpinner() {
    $("#divTransfer").addClass("d-none");
    $("#loadingSpinner").removeClass("d-none");
}