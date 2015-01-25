//TIMESHEET
//Afficher le projet dynamiquement
function showProjet(str)
{
	if (str==1)
	{
		document.getElementById('ActiviteHint').style.display = 'none';
	}
	else
	{
		document.getElementById('ActiviteHint').style.display = 'block';
	}
	if (str=="")
	{
		document.getElementById("txtHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","getprojet.php?p="+str,true);
	xmlhttp.send();
}
 
//Afficher la mission dynamiquement
function showMission(strmis)
{
	var prjclt = document.getElementById('client');
	var strprj = prjclt.value;
	if (strmis=="")
	{
		document.getElementById("txtHint2").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
		}
	}
	var adres1 = "m="+strmis;
	var adres2 = "&p="+strprj;
	var adresfull = adres1.concat(adres2);
	xmlhttp.open("GET","getmission.php?"+adresfull,true);
	xmlhttp.send();
}

//Afficher la categorie dynamiquement
function showCategorie(strcat)
{
	var prjclt = document.getElementById('client');
	var strclt = prjclt.value;
	var misprj = document.getElementById('projet');
	var strprj = misprj.value;
	if (strcat=="")
	{
		document.getElementById("txtHint3").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("txtHint3").innerHTML=xmlhttp.responseText;
		}
	}
	var adres1 = "m="+strcat;
	var adres2 = "&c="+strclt;
	var adres3 = "&p="+strprj;
	var adresfull = adres1.concat(adres2).concat(adres3);
	xmlhttp.open("GET","getcategorie.php?"+adresfull,true);
	xmlhttp.send();
}


//FRAIS
//Afficher le type dynamiquement
function showType(strcomp)
{
	if (strcomp=="")
	{
		document.getElementById("txtHint4").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("txtHint4").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","gettype.php?p="+strcomp,true);
	xmlhttp.send();
}
 
//Afficher l'événement dynamiquement
function showEvnmt(strtyp)
{
	var comp = document.getElementById('competition');
	var strcomp = comp.value;
	if (strtyp=="")
	{
		document.getElementById("txtHint5").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("txtHint5").innerHTML=xmlhttp.responseText;
		}
	}
	var adres1 = "t="+strtyp;
	var adres2 = "&c="+strcomp;
	var adresfull = adres1.concat(adres2);
	xmlhttp.open("GET","getevnmt.php?"+adresfull,true);
	xmlhttp.send();
}

//Afficher la categorie dynamiquement
function showCatEve(streve)
{
	var prjcomp = document.getElementById('competition');
	var strcomp = prjcomp.value;
	var mistyp = document.getElementById('typecomp');
	var strtyp = mistyp.value;
	if (streve=="")
	{
		document.getElementById("txtHint6").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("txtHint6").innerHTML=xmlhttp.responseText;
		}
	}
	var adres1 = "e="+streve;
	var adres2 = "&t="+strtyp;
	var adres3 = "&c="+strcomp;
	var adresfull = adres1.concat(adres2).concat(adres3);
	xmlhttp.open("GET","getcateve.php?"+adresfull,true);
	xmlhttp.send();
}


//JOURNAL
//Afficher ana2 dynamiquement
function showAna2(strana2)
{
	if (strana2=="")
	{
		document.getElementById("selectHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("selectHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","getana2.php?a="+strana2,true);
	xmlhttp.send();
}
 
//Afficher profil dynamiquement
function showProfil(strpresta)
{
	if (strpresta=="")
	{
		document.getElementById("selectHint2").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("selectHint2").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","getprofil.php?p="+strpresta,true);
	xmlhttp.send();
}
 
//Afficher le type de fournisseur dynamiquement
function showCatfrs(str)
{
	if (str=="")
	{
		document.getElementById("txtHintfrs").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("txtHintfrs").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","getcatfrs.php?f="+str,true);
	xmlhttp.send();
}


//Cachecache et calcul
function cacher(phase)
{
	if (phase==2)
	{
		document.getElementById('f-actual1').style.display = 'none';
		document.getElementById('f-actual2').style.display = 'none';
		document.getElementById('f-frslb').style.display = 'none';
		document.getElementById('f-cltrb').style.display = 'none';
		document.getElementById('f-budlb').style.display = 'block';
	}
	else
	{
		document.getElementById('f-actual1').style.display = 'block';
		document.getElementById('f-actual2').style.display = 'block';
		document.getElementById('f-frslb').style.display = 'inline-block';
		document.getElementById('f-cltrb').style.display = 'inline-block';
		document.getElementById('f-budlb').style.display = 'none';
	}
}

function bcalc(){
	if (document.getElementById('bUnit').value!=0 && document.getElementById('bQty').value!=0)
	{
		var bUnit = parseFloat(document.getElementById('bUnit').value).toFixed(2);
		var bQty = parseFloat(document.getElementById('bQty').value).toFixed(2);
		document.getElementById('bTot').value = parseFloat(bUnit * bQty).toFixed(2);
	}
}

function frscalc(){
	if (document.getElementById('frsCtUnit').value!=0 && document.getElementById('frsQty').value!=0)
	{
		var frsCtUnit = parseFloat(document.getElementById('frsCtUnit').value).toFixed(2);
		var frsQty = parseFloat(document.getElementById('frsQty').value).toFixed(2);
		document.getElementById('frsCtTotHT').value = parseFloat(frsCtUnit * frsQty).toFixed(2);
		document.getElementById('frstot').value = parseFloat(frsCtUnit * frsQty).toFixed(2);
		if (document.getElementById('cltFact').value!=0 && document.getElementById('frsCtTotHT').value!=0)
		{
			var cltFact = parseFloat(document.getElementById('cltFact').value).toFixed(2);
			var frsCtTotHT = parseFloat(document.getElementById('frsCtTotHT').value).toFixed(2);
			document.getElementById('cltMarge').value = parseFloat(cltFact / frsCtTotHT).toFixed(2);
		}
		else
		{
			if (document.getElementById('cltMarge').value!=0 && document.getElementById('frsCtTotHT').value!=0)
			{
				var cltMarge = parseFloat(document.getElementById('cltMarge').value).toFixed(2);
				var frsCtTotHT = parseFloat(document.getElementById('frsCtTotHT').value).toFixed(2);
				document.getElementById('cltFact').value = parseFloat(cltMarge * frsCtTotHT).toFixed(2);
			}
		}
	}
}

function cltcalcMrg(){
	if (document.getElementById('cltFact').value!=0 && document.getElementById('frsCtTotHT').value!=0)
	{
		var cltFact = parseFloat(document.getElementById('cltFact').value).toFixed(2);
		var frsCtTotHT = parseFloat(document.getElementById('frsCtTotHT').value).toFixed(2);
		document.getElementById('cltMarge').value = parseFloat(cltFact / frsCtTotHT).toFixed(2);
	}
}

function cltcalcFact(){
	if (document.getElementById('cltMarge').value!=0 && document.getElementById('frsCtTotHT').value!=0)
	{
		var cltMarge = parseFloat(document.getElementById('cltMarge').value).toFixed(2);
		var frsCtTotHT = parseFloat(document.getElementById('frsCtTotHT').value).toFixed(2);
		document.getElementById('cltFact').value = parseFloat(cltMarge * frsCtTotHT).toFixed(2);
	}
}


//ADMIN REL CLIENT/...
//Afficher l'interface de création d'un nouveau projet
function showNewProj(newproj)
{
	if (newproj=="0")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","newprojet.php",true);
	xmlhttp.send();
}

//Afficher l'interface de saisie d'un ancien projet
function showCurrProj(currproj)
{
	if (currproj=="1")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","currprojet.php",true);
	xmlhttp.send();
}

//Afficher l'interface de création d'une nouvelle mission
function showNewMis(newmis)
{
	if (newmis=="0")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","newmission.php",true);
	xmlhttp.send();
}

//Afficher l'interface de saisie d'une mission existante
function showCurrMis(currmis)
{
	if (currmis=="1")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","currmission.php",true);
	xmlhttp.send();
}

//Afficher l'interface de création d'une nouvelle catégorie
function showNewCat(newcat)
{
	if (newcat=="0")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","newcategorie.php",true);
	xmlhttp.send();
}

//Afficher l'interface de saisie d'une catégorie existante
function showCurrCat(currcat)
{
	if (currcat=="1")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","currcategorie.php",true);
	xmlhttp.send();
}

//Afficher l'interface de création d'un nouveau type d'événement
function showNewTyp(newtyp)
{
	if (newtyp=="0")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","newtype.php",true);
	xmlhttp.send();
}

//Afficher l'interface de saisie d'un type d'événement existant
function showCurrTyp(currtyp)
{
	if (currtyp=="1")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","currtype.php",true);
	xmlhttp.send();
}

//Afficher l'interface de création d'un nouvel événement
function showNewEve(neweve)
{
	if (neweve=="0")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","newevent.php",true);
	xmlhttp.send();
}

//Afficher l'interface de saisie d'un événement existant
function showCurrEve(curreve)
{
	if (curreve=="1")
	{
		document.getElementById("ProjetHint").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("ProjetHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","currevent.php",true);
	xmlhttp.send();
}

