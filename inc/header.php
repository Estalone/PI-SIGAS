<?php /* coding: utf-8 */
require_once __DIR__ . '/../../seguro/sigas/connection.php';

// Application constants
const MOD_VIEW		= 1;
const MOD_SEARCH	= 2;
const MOD_EDIT		= 4;
const MOD_AUTH		= 8;
const MOD_MANAGE	= 16;
const MOD_RULE		= 32;
const MOD_DEV		= 64;
const MOD_DBMAN		= 128;
const USER_ST_NEW	= 0;
const USER_ST_OK	= 10;
const USER_ST_AUTH	= 100;
const USER_ST_BLK	= 200;
const USER_ST_REM	= 201;
const USER_TP_UP	= "up"; // Person
const USER_TP_UC	= "uc"; // Corporate
const USER_TP_UG	= "ug"; // Government
const USER_TP_IF	= "if"; // Federal Inspector
const USER_TP_IS	= "is"; // State Inspector
const USER_TP_IM	= "im"; // Municipality Inspector
const USER_TP_OF	= "of"; // Federal Operator
const USER_TP_OS	= "os"; // State Operator
const USER_TP_OM	= "om"; // Municipality Operator
const USER_TP_AF	= "af"; // Federal Administrator
const USER_TP_AS	= "as"; // State Administrator
const USER_TP_AM	= "am"; // Municipality Administrator
const USER_TP_SU	= "su"; // Super User
const USER_TP_PD	= "pd"; // Programming Developer
const USER_TP_DM	= "dm"; // Database Manager
const EMAIL_NOREPLY	= "no-reply@sigas.sigas"; // No reply E-Mail address


session_start();

$_self=$_SERVER['PHP_SELF'];

// Check if application is at login or registration Page
if (preg_match_all("/login\.php/",$_self)||preg_match_all("/register\.php/",$_self)){
	// Check if the user is authenticated.
	if (isset($_SESSION['id']) && !empty($_SESSION['id'])){
		header('location:./home.php');
	}
}

function safeInput($inputStr){
	return preg_replace('/[^0-9a-zA-Z@_-]/','_',$inputStr);
}

function IOS($opt,$sel){
	return $opt==safeInput($sel)?" selected":"";
}

function GRT($length){ /* Generate Random Token */
	$TOKENSET="ABCDEHJKLMPSTUXZ";
	$date=new DateTimeImmutable("now",new DateTimeZone("UTC"));
	$s=$date->format("YmdHis");
	$i=$length;
	while ($i--){
		$s.=$TOKENSET[rand(0,15)];
	}
	return $s;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.9">
	<link rel="stylesheet" href="./css/main.css">
	<title data-lx="title">SIGAS</title>
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
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">