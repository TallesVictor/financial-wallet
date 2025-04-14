function maskMoney(money, cifrao = 'R$ ') {
  return cifrao + money.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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

  let valor = input.value.replace(/\D/g, ""); 

  valor = (parseInt(valor, 10) / 100).toFixed(2) + ""; 
  valor = valor.replace(".", ","); 
  valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  input.value = valor;
}

function maskDate(date) {
  let [data, horas] = date.split(" "); // Separa a data das horas
  let [ano, mes, dia] = data.split("-"); // Separa ano, mês e dia
  return `${dia}/${mes}/${ano} ${horas}`; // Formata a saída
}

//logout
async function logout() {

  try {
      const response = await fetch("/api/logout", {
          method: "POST",
          body: {},
          headers: { Authorization: `Bearer ${localStorage.getItem("token_financial_wallet")}` },
      });

      let data = await response.json();

      if (response.status === 200) {
        clearAllCookies();
         window.location.href = "/";
      } else {
          console.log(data);
      }

  } catch (e) {
      console.error(e);
  }
}

function clearAllCookies() {
  const cookies = document.cookie.split(";");

  for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i];
      const eqPos = cookie.indexOf("=");
      const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
      document.cookie = name.trim() + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
  }
}

function getStatus(status){
  switch(status){
    case 'success':
      return "Sucesso";
    case 'pending':
      return "Pendente";
    case 'failed':
      return "Falha";
    case 'reversed':
      return "Reversado";
  }
}