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
  statusDiv.innerHTML = texto;
  statusDiv.className = `alert-${tipo}`;
}

// Função de remover a mensagem do login
function removeMensagem() {
  statusDiv.innerHTML = "";
  statusDiv.className = ``;
}

// Realiza o login do usuário
const formLogin = document.querySelector("#formLogin");

formLogin.addEventListener("submit", async (e) => {
  e.preventDefault(); // Cancela o envio do formulário
  removeMensagem(); // Remove a mensagem de erro se existir

  const formData = new FormData(formLogin); // Pega os dados vindos do form
  const data = Object.fromEntries(formData.entries()); // Converte o FormData em um objeto
  const errors = {}; // Objeto para adicionar os erros de validação

  // Validação básica dos campos
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!data.email || data.email.trim() === "") {
    errors.email = "Email é obrigatório.";
  } else if (!emailRegex.test(data.email.trim())) {
    errors.email = "Informe um e-mail válido.";
  }

  if (!data.user_pwd || data.user_pwd.trim() === "") {
    errors.user_pwd = "Senha é obrigatória.";
  }

  if (Object.keys(errors).length > 0) {
    exibirMensagem(
      `
      Verifique os dados informados: 
      ${errors.email ? "<br>" + errors.email : ""}
      ${errors.user_pwd ? "<br>" + errors.user_pwd : ""}
      `,
      "erro",
    );

    return false;
  }

  addLoader(); // Exibe o loader

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
          `Erro ${response.status}: Usuário ou senha incorretos.`,
        );
      } else {
        throw new Error(`Erro na requisição: Code ${response.status}: `);
      }
    }
    // Redireciona o usuário para a home
    window.location.href = "./home.php";
    exibirMensagem(`Login realizado com sucesso!`, "sucesso");
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
