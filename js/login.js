const btnLogin = document.querySelector("#submitLogin"); // pega o botão de login
const btnLoginText = document.querySelector("#submitLogin").textContent; // pega o texto do botão de login

// exibe o estado de carregamento
function addLoader() {
  btnLogin.innerHTML = `<div class="loader"></div>`;
}

function removeLoader() {
  btnLogin.textContent = btnLoginText;
}

// Realiza o login do usuário
const formLogin = document.querySelector("#formLogin");

formLogin.addEventListener("submit", async (e) => {
  e.preventDefault(); // Cancela o envio do formulário
  addLoader();

  const formData = new FormData(formLogin); // Pega os dados vindos do form

  console.log(formData);

  // Envia os dados para a api
  await fetch("./api/loginUser.php", {
    method: "POST",
    body: formData,
  })
    .then((data) => console.log(data))
    .catch((err) => console.log(err))
    .finally(() => removeLoader());
});
