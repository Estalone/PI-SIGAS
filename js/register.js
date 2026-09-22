const btnRegister = document.querySelector("#submitRegister"); // pega o botão de registro
const btnRegisterText = document.querySelector("#submitRegister").textContent; // pega o texto do botão de registro
const statusDiv = document.querySelector("#statusRegister"); // pega a div de status

// exibe o estado de carregamento
function addLoader() {
  btnRegister.innerHTML = `<div class="loader"></div>`;
}

function removeLoader() {
  btnRegister.textContent = btnRegisterText;
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
const formRegister = document.querySelector("#formRegister");

formRegister.addEventListener("submit", async (e) => {
  e.preventDefault(); // Cancela o envio do formulário
  removeMensagem(); // Remove a mensagem de erro se existir

  const formData = new FormData(formRegister); // Pega os dados vindos do form
  const data = Object.fromEntries(formData.entries()); // Converte o FormData em um objeto
  const errors = {}; // Objeto para adicionar os erros de validação

  // Validação básica dos campos
  if (!data.user_name || data.user_name.trim() === "") {
    errors.user_name = "Nome é obrigatório.";
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!data.email || data.email.trim() === "") {
    errors.email = "Email é obrigatório.";
  } else if (!emailRegex.test(data.email.trim())) {
    errors.email = "Informe um e-mail válido.";
  }

  if (!data.user_type || data.user_type.trim() === "") {
    errors.user_type = "Selecione o tipo de usuário.";
  }

  if (!data.user_pwd || data.user_pwd.trim() === "") {
    errors.user_pwd = "A senha é obrigatória.";
  }

  if (!data.user_repwd || data.user_repwd.trim() === "") {
    errors.user_repwd = "A confirmação de senha é obrigatória.";
  }

  if (data.user_repwd != data.user_pwd) {
    errors.user_repwd = "As senhas não coincidem.";
  }

  if (Object.keys(errors).length > 0) {
    exibirMensagem(
      `
      Verifique os dados informados:
      ${errors.user_name ? "<br>" + errors.user_name : ""}
      ${errors.user_type ? "<br>" + errors.user_type : ""}
      ${errors.email ? "<br>" + errors.email : ""}
      ${errors.user_pwd ? "<br>" + errors.user_pwd : ""}
      ${errors.user_repwd ? "<br>" + errors.user_repwd : ""}
      `,
      "erro",
    );

    return false;
  }

  addLoader(); // Exibe o loader

  // Envia os dados para a api
  try {
    const response = await fetch("./api/registerUser.php", {
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
      } else if (response.status === 400) {
        throw new Error("O usuário e/ou o E-Mail informado já está cadastrado no sistema.");
      } else {
        throw new Error(`Erro na requisição: Code ${response.status}: `);
      }
    }

    const data = await response.json();
    exibirMensagem(
      `Registro realizado com sucesso! Título: ${data.title}`,
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
