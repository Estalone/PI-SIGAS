<?php /* coding: utf-8 */
include_once "./connection.php";

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