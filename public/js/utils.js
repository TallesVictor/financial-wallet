function maskMoney(money, currencySymbol = 'R$ ') {
  return currencySymbol + money.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function convertNumeric(value) {

  value = value.replaceAll('.', '');
  value = value.replaceAll('R$', '');
  value = value.replaceAll(',', '.');
  value = value.trim();

  if (!value || isNaN(value)) return '';

  return parseFloat(value);

}

function convertToMoney(input) {
  if (!input || !input.value) return;

  let value = input.value.replace(/\D/g, "");

  value = (parseInt(value, 10) / 100).toFixed(2) + "";
  value = value.replace(".", ",");
  value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  input.value = value;
}

function maskDate(date) {
  let [datePart, timePart] = date.split(" "); // Split date and time
  let [year, month, day] = datePart.split("-"); // Split year, month and day
  return `${day}/${month}/${year} ${timePart}`; // Format the output
}

//logout
async function logout() {
  try {
    const response = await fetch("/api/logout", {
      method: "POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // CSRF token
        'Authorization': `Bearer ${localStorage.getItem("token_financial_wallet")}` // Bearer token (if available)
      },
      credentials: 'same-origin' // Send the session cookie, if necessary for session-based authentication
    });

    let data = await response.json();

    if (response.status === 200) {
      localStorage.removeItem('token_financial_wallet');  // Clear the token from localStorage
      window.location.href = "/login"; // Redirect to the home page or login page
    } else {
      console.log(data);
    }

  } catch (e) {
    console.error(e);
  }
}

function getStatus(status) {
  switch (status) {
    case 'success':
      return "Success";
    case 'pending':
      return "Pending";
    case 'failed':
      return "Failed";
    case 'reversed':
      return "Reversed";
  }
}


function hideSpinner(idDiv) {
  $("#" + idDiv).removeClass("d-none");
  $("#loadingSpinner").addClass("d-none");
}

function showSpinner(idDiv) {
  $("#" + idDiv).addClass("d-none");
  $("#loadingSpinner").removeClass("d-none");
}