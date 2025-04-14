const TOKEN = localStorage.getItem("token_financial_wallet");

listTransactions();

document.querySelector("#reverseForm").addEventListener("submit", (event) => {
    event.preventDefault();
    reverseForm();
});

async function listTransactions() {

    getBalance();
    
    try {
        const response = await fetch(`/api/transaction/list`, {
            method: "GET",
            headers: { Authorization: `Bearer ${TOKEN}` }
        });
        let data = await response.json();

        if (response.status === 200) {
            constructTransations(data);
        } else {
            // showNotification(data.message, true);
            console.error(data);
        }

    } catch (e) {
        console.error(e);
    }
}

function constructTransations(data) {
    let rows = "";
    data.forEach(transaction => {
        let action = "";
        if (transaction.status == 'success') {
            action = `<i class="fas fa-history pointer" onclick="reverseModal('${transaction.transaction_id}')"></i>`;
        }
        rows += `
            <tr>
                <td>${transaction.transaction_id}</td>
                <td>${transaction.sender.name}</td>
                <td>${transaction.recipient.name}</td>
                <td>${transaction.amount}</td>
                <td>${transaction.description}</td>]
                <td>${getStatus(transaction.status)}</td>
                <td>${maskDate(transaction.transaction_date)}</td>
                <td>${action}</td>
                `;
    });

    $("#transactionsTable").html(rows);
}

function reverseModal(transactionId) {
    $("#reverseModal").modal("show");
    $("#transactionId").val(transactionId);
}

async function reverseForm() {
    try {
        $("#transactionsTable").html(`<tr> <td colspan="8" class="text-center">Carregando transferências...</td> </tr>`);

        $("#reverseModal").modal("hide");

        const form = document.querySelector("#reverseForm");
        const formData = new FormData(form);
        let transactionId = $("#transactionId").val();

        const response = await fetch(`/api/transaction/${transactionId}/reverse`, {
            method: "POST",
            body: formData,
            headers: { Authorization: `Bearer ${TOKEN}` }
        });

        let data = await response.json();

        if (response.status === 200) {
            form.reset();
            listTransactions();
        } else {
            $("#transactionsTable").html(`<tr> <td colspan="8" class="text-center">Error</td> </tr>`);
            $("#reverseModal").modal("show");
            console.error(data);
        }

    } catch (e) {
        console.error(e);
    }
}

async function getBalance() {

    try {
        const response = await fetch(`/api/user/balance`, {
            method: "GET",
            headers: { Authorization: `Bearer ${TOKEN}` }
        });
        let data = await response.json();

        if (response.status === 200) {
            $("#totalBalance").html(`Saldo: ${maskMoney(data.balance)}`);
        } else {
            // showNotification(data.message, true);
            console.error(data);
        }

    } catch (e) {
        console.error(e);
    }
}
