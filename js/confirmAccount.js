const btnConfirm=document.querySelector("#submitConfirm"); // pega o botão de registro
const btnConfirmText=btnConfirm.textContent; // pega o texto do botão de registro
const statusDiv=document.querySelector("#statusConfirm"); // pega a div de status

// exibe o estado de carregamento
function addLoader(){
	btnConfirm.innerHTML=`<div class="loader"></div>`;
}

function removeLoader(){
	btnConfirm.textContent=btnConfirmText;
}

// Função de exibir a mensagem do login
function exibirMensagem(texto,tipo){
	statusDiv.innerHTML=texto;
	statusDiv.className=`alert-${tipo}`;
}

// Função de remover a mensagem do login
function removeMensagem(){
	statusDiv.innerHTML="";
	statusDiv.className="";
}

// Realiza o login do usuário
const formConfirm=document.querySelector("#formConfirm");

formConfirm.addEventListener("submit",async(e)=>{
	e.preventDefault(); // Cancela o envio do formulário
	removeMensagem(); // Remove a mensagem de erro se existir

	const formData=new FormData(formConfirm); // Pega os dados vindos do form
	const data=Object.fromEntries(formData.entries()); // Converte o FormData em um objeto
	const errors={}; // Objeto para adicionar os erros de validação

	// Validação básica dos campos
	const emailRegEx=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	const tokenRegEx=/^[0-9]+[A-Z]+$/;

	if(!data.email||data.email.trim()===""){
		errors.email="E-Mail é obrigatório.";
	} else if(!emailRegEx.test(data.email.trim())){
		errors.email="Informe um E-Mail válido.";
	}

	if(!data.token||data.token.trim()===""){
		errors.token="token é obrigatório.";
	} else if(!tokenRegEx.test(data.token.trim())){
		errors.token="Informe um e-mail válido.";
	}

	if(Object.keys(errors).length>0){
		exibirMensagem(
			`
				Verifique os dados informados:
				${errors.email?"<br>"+errors.email:""}
				${errors.token?"<br>"+errors.token:""}
			`,
			"erro"
		);

		return false;
	}

	addLoader(); // Exibe o loader

	// Envia os dados para a api
	try{
		const response=await fetch("./api/confirmAccount.php",{
			method:	"POST",
			body:	formData
		});

		// Verifica se a resposta HTTP é diferente da faixa 200-299
		if(!response.ok){
			if(response.status===404){
				throw new Error("O recurso solicitado não foi encontrado (404).");
			}else if(response.status===500){
				throw new Error("Erro interno no servidor (500). Tente novamente mais tarde.");
			}else if(response.status===400){
				throw new Error(`Token ou E-Mail inválido: ${response.status}.`);
			}else if(response.status===406){
				throw new Error(`Token já utilizado ou E-Mail não encontrado: ${response.status}.`);
			}else{
				throw new Error(`Erro na requisição: código ${response.status}.`);
			}
		}

		const data=await response.json();
		exibirMensagem(
			`Confirmação realizada com sucesso: ${data.title}.`,
			"sucesso"
		);
	}catch(err){
		// Captura tanto os erros lançados no 'throw' quanto falhas de rede
		if(err.name==="TypeError"){
			exibirMensagem("Falha de conexão. Verifique sua internet.","erro");
		}else{
			exibirMensagem(err.message,"erro");
		}
	}finally{
		removeLoader();
	}
});
