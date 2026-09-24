<?php /* coding: utf-8 */
require_once __DIR__.'/../inc/connection.php';

session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.9">
	<link rel="stylesheet" href="<?= __DIR__ ?>/../css/main.css">
	<title data-lx="title">SIGAS</title>
  <!-- Material icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=menu" />
  <!-- Fonte da página -->
  <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Cria uma folha de estilo utilizando o framework tailwindCSS -->
	<script src="https://cdn.tailwindcss.com"></script>
	<style type="text/tailwindcss">
		@layer components {
			.input{
				@apply w-full px-3 py-2 border border-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#293F14] focus:border-transparent transition bg-gray-100 mb-3
			}

			.btn-primary{
				@apply w-full bg-[#293F14] hover:bg-[#1f300f] text-white font-medium rounded-lg py-2.5 transition duration-150 shadow-sm mt-2 cursor-pointer
			}

			label{
				@apply text-xs font-semibold text-gray-600 uppercase tracking-wider
			}

			.alert-erro{
				@apply text-xs font-semibold text-red-600 tracking-wider border border-red-600 rounded p-3 bg-red-50 my-5
			}

			.alert-sucesso{
				@apply text-xs font-semibold text-green-600 tracking-wider border border-green-600 rounded p-3 bg-green-50 my-5
			}
      
		}
	</style>
</head>
<body class="bg-gray-100">
