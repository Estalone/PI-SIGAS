const btnLogin = document.querySelector("#submitLogin"); // pega o botão de login
const btnLoginText = document.querySelector("#submitLogin").textContent; // pega o texto do botão de login
const statusDiv = document.querySelector("#statusLogin"); // pega a div de status

// exibe o estado de carregamento
function addLoader() {
  btnLogin.innerHTML = `<div class="loader"></div>`;
}

function removeLoader() {
  btnLogin.textContent = btnLoginText;
}

// Função de exibir a mensagem do login
function exibirMensagem(texto, tipo) {
  statusDiv.textContent = texto;
  statusDiv.className = `alert-${tipo}`;
}

// Função de remover a mensagem do login
function removeMensagem() {
  statusDiv.textContent = "";
  statusDiv.className = ``;
}

// Realiza o login do usuário
const formLogin = document.querySelector("#formLogin");

formLogin.addEventListener("submit", async (e) => {
  e.preventDefault(); // Cancela o envio do formulário
  addLoader();
  removeMensagem();

  const formData = new FormData(formLogin); // Pega os dados vindos do form

  // Envia os dados para a api
  try {
    const response = await fetch("./api/loginUser.php", {
      method: "POST",
      body: formData,
    });

    // Verifica se a resposta HTTP é diferente da faixa 200-299
    if (!response.ok) {
      if (response.status === 404) {
        throw new Error("O recurso solicitado não foi encontrado (404).");
      } else if (response.status === 500) {
        throw new Error(
          "Erro interno no servidor (500). Tente novamente mais tarde.",
        );
      } else if (response.status === 401) {
        throw new Error(
          `Erro ${response.status}: Usuário ou senha incorretos `,
        );
      } else {
        throw new Error(`Erro na requisição: Code ${response.status}: `);
      }
    }

    const data = await response.json();
    exibirMensagem(
      `Login realizado com sucesso! Título: ${data.title}`,
      "sucesso",
    );
  } catch (err) {
    // Captura tanto os erros lançados no 'throw' quanto falhas de rede
    if (err.name === "TypeError") {
      exibirMensagem("Falha de conexão. Verifique sua internet.", "erro");
    } else {
      exibirMensagem(err.message, "erro");
    }
  } finally {
    removeLoader();
  }
});
