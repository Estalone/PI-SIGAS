// Add language toolbar
document.write(`
	<fieldset style="position:fixed;bottom:32px;left:8px">
		<legend>aαあ</legend>
		<img src="./img/br.svg" width="40px" onclick="setLocale('ptbr')"/><br/>
		<img src="./img/es.svg" width="40px" onclick="setLocale('es')"/><br/>
		<img src="./img/gb.svg" width="40px" onclick="setLocale('en')"/><br/>
		<img src="./img/jp.svg" width="40px" onclick="setLocale('ja')"/><br/>
		<img src="./img/cn.svg" width="40px" onclick="setLocale('zh')"/>
	</fieldset>
	<div id="Status" style="position:fixed;bottom:8px;left:8px">Ready!</div>
`);

// The locale our app first shows
const LOC_DEFAULT="ptbr";

function saveCookie(name,value,days){
	var expires="";

	if (days){
		var date=new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		expires=";expires="+date.toGMTString();
	}

	document.cookie=name+"="+value+expires+";path=/";
}

function readCookie(name){
	var nameEQ=name+"=";
	var ca=document.cookie.split(";");

	for (var i=0;i<ca.length;i++){
		var c=ca[i];
		while (c.charAt(0)==" ")
			c=c.substring(1,c.length);
		if (c.indexOf(nameEQ)==0)
			return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name){
	createCookie(name,"",-1);
}

let locale=readCookie("locale");

if (null===locale)
	locale=LOC_DEFAULT;

let translations={};

document.addEventListener("DOMContentLoaded",()=>{
	setLocale();
});

async function setLocale(newLocale){
	if (undefined!=newLocale){
		if (newLocale===locale)
			return;
    }else{
		newLocale=locale;
	}

	const newTranslations=await fetchTranslationsFor(newLocale);
	locale=newLocale;
	translations=newTranslations;
	translatePage();
    saveCookie("locale",locale,999);
}

async function fetchTranslationsFor(newLocale){
	var pageName=location.pathname.match(/[^\.\/]+/);

	if (null===pageName)
		pageName="index";

	var locName=pageName+"."+newLocale+".json";

	try {
		const response=await fetch(locName);
		document.getElementById("Status").innerHTML="";
		return await response.json();
	} catch (error) {
		console.error("Error loading translation file \"",locName,"\":",error);
		document.getElementById("Status").innerHTML="Tron: error loading "+locName+" = "+error+".<br/>";
		locName=pageName+"."+LOC_DEFAULT+".json";
		try {
			const response=await fetch(locName);
			return await response.json();
		} catch (error) {
			console.error("Error loading default translation file \"",locName,"\":",error);
            document.getElementById("Status").innerHTML="Tron: error loading "+locName+" = "+error+".<br/>";
            return null;
		}
	}
}

function  translatePage(){
	// Find and translate textContent.
	document.querySelectorAll("[data-lx]").forEach(element=>{
		const key=element.getAttribute("data-lx");
		element.textContent=translations[key]||element.textContent;
	});
	// Find and translate title attributes.
	document.querySelectorAll("[data-lt]").forEach(element=>{
		const key=element.getAttribute("data-lt");
		element.title=translations[key]||element.title;
	});
	// Find and translate placeholder attributes.
	document.querySelectorAll("[data-lp]").forEach(element=>{
		const key=element.getAttribute("data-lp");
		element.placeholder=translations[key]||element.placeholder;
	});
	// Find and translate value attributes.
	document.querySelectorAll("[data-lv]").forEach(element=>{
		const key=element.getAttribute("data-lv");
		element.value=translations[key]||element.value;
	});
}
