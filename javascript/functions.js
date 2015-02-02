$(document).ready(function(){
  $(document).on('click', '.ajax-recup-absences button', function(e){
  	// Prevent the form from being submitted with preventDefault
    e.preventDefault();
    var button = $(this);
		var column = button.attr('name');
		// VARIABLES: Form-specific
		var form = button.parents('form');
		var method = form.attr('method');
		var action = form.attr('action');
		// VARIABLES: Where to update in database
		var where_val = form.find('.where').val();
		var where_col = form.find('.where').attr('name');
		var value = button.val();
		console.log (column, method, action, where_val, where_col, value);
		var tr = form.parents('tr')

		$.ajax({
			url: action,
			type: method,
			data: {
				val: value,
				col: column,
				w_col: where_col,
				w_val: where_val
				},
			cache: false,
			timeout: 10000,
			success: function(data) {
				// Alert if update failed
				if (data) {
					alert(data);
				}
				// Load output into a P
				else {
					tr.fadeOut();
				}
			}
		});
  });
});


// AJAX - VALTEMPS
// $(function(){
	// $('.ajax-form button').autoSubmit();
// });

// (function($)
// {
// 	$.fn.autoSubmit = function(options) {
// 		return $.each(this, function() {
// 			// VARIABLES: Input-specific
// 			var input = $(this);
// 			var column = input.attr('name');
// 			// VARIABLES: Form-specific
// 			var form = input.parents('form');
// 			var tr = form.parents('tr')
// 			var method = form.attr('method');
// 			var action = form.attr('action');
// 			// VARIABLES: Where to update in database
// 			var where_val = form.find('.where').val();
// 			var where_col = form.find('.where').attr('name');
// 			// ONBLUR: Dynamic value send through Ajax
// 			input.bind('blur', function(event) {
// 				// Get latest value
// 				var value = input.val();
// 				// AJAX: Send values
// 				$.ajax({
// 					url: action,
// 					type: method,
// 					data: {
// 						val: value,
// 						col: column,
// 						w_col: where_col,
// 						w_val: where_val
// 						},
// 					cache: false,
// 					timeout: 10000,
// 					success: function(data) {
// 						// Alert if update failed
// 						if (data) {
// 							alert(data);
// 						}
// 						// Load output into a P
// 						else {
// 							tr.fadeOut();
// 							// $('#notice').text('Updated');
// 							// $('#notice').fadeOut().fadeIn();
// 						}
// 					}
// 				});
// 			// Prevent normal submission of form
// 			return false;
// 			})
// 		});
// 	}
// })(jQuery);

//CALENDRIER
$(function() {
	var daysShortFr = [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ];
	var monthsFr = [ "Janvier", "F&eacute;vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Ao&ucirc;t", "Septembre", "Octobre", "Novembre", "D&eacute;cembre" ];

	var datepickerOptions = {
		defaultDate: "0",
		changeMonth: false,
		numberOfMonths: 1,
		dayNamesMin: daysShortFr,
	  monthNames: monthsFr,
		firstDay: 1
	}

	$( "#datejourdeb" ).datepicker({
		defaultDate: "0",
		changeMonth: false,
		numberOfMonths: 1,
		dayNamesMin: daysShortFr,
	  monthNames: monthsFr,
		firstDay: 1,
		onClose: function( selectedDate ) {
			$( "#datejourfin" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$( "#datejourfin" ).datepicker({
		defaultDate: "0",
		changeMonth: false,
		numberOfMonths: 1,
		dayNamesMin: daysShortFr,
	  monthNames: monthsFr,
		firstDay: 1,
		onClose: function( selectedDate ) {
			$( "#datejourdeb" ).datepicker( "option", "maxDate", selectedDate );
		}
	});

	$( "#datejourstrt" ).datepicker({
		defaultDate: "0",
		changeMonth: false,
		numberOfMonths: 1,
		dayNamesMin: daysShortFr,
	  monthNames: monthsFr,
		firstDay: 1,
		onClose: function( selectedDate ) {
			$( "#datejourend" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$( "#datejourend" ).datepicker({
		defaultDate: "0",
		changeMonth: false,
		numberOfMonths: 1,
		dayNamesMin: daysShortFr,
	  monthNames: monthsFr,
		firstDay: 1,
		onClose: function( selectedDate ) {
			$( "#datejourstrt" ).datepicker( "option", "maxDate", selectedDate );
		}
	});


	$( "#dateFact" ).datepicker(datepickerOptions);
	$( "#dateTransac" ).datepicker(datepickerOptions);
	$( "#datefrais" ).datepicker(datepickerOptions);
	$( "#deadline1" ).datepicker(datepickerOptions);
	$( "#deadline2" ).datepicker(datepickerOptions);
	$( "#deadline3" ).datepicker(datepickerOptions);
	$( ".datepicker" ).datepicker(datepickerOptions);
});


//TIMESHEET
//Afficher le projet dynamiquement
function showProjet(str)
{
	//if (str==1)
	//{
	//	document.getElementById('ActiviteHint').style.display = 'none';
	//}
	//else
	//{
	//	document.getElementById('ActiviteHint').style.display = 'block';
	//}
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
	var ma_page = document.getElementById('ma_page');
	var strma_page = ma_page.value;
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
	var adres3 = "&page="+strma_page;
	var adresfull = adres1.concat(adres2).concat(adres3);
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
	var ma_page = document.getElementById('ma_page');
	var strma_page = ma_page.value;
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
	var adres3 = "&page="+strma_page;
	var adresfull = adres1.concat(adres2).concat(adres3);
	xmlhttp.open("GET","getevnmt.php?"+adresfull,true);
	xmlhttp.send();
}

//Afficher carac d'even dynamiquement
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


//DEVIS
//Afficher le projet dynamiquement
function showDevisVersion(str)
{
	if (str=="none")
	{
		document.getElementById("txtHint7").innerHTML="";
		document.getElementById('f-rf1').style.display = 'block';
		document.getElementById('f-rf2').style.display = 'block';
		document.getElementById('f-rf3').style.display = 'block';
		return;
	}
	else
	{
		document.getElementById('f-rf1').style.display = 'none';
		document.getElementById('f-rf2').style.display = 'none';
		document.getElementById('f-rf3').style.display = 'none';
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
			document.getElementById("txtHint7").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","getDevisVersion.php?d="+str,true);
	xmlhttp.send();
}
 

//VALIDATION DES TEMPS
//Afficher la requete du filtre dynamiquement
function showFilterValtps(param)
{
	var d1 = document.getElementById('affmonth').value;
	var d2 = document.getElementById('affyear').value;
	var d3 = document.getElementById('datejourdeb').value;
	var d3 = d3.substring(6,10) + d3.substring(3,5) + d3.substring(0,2);
	var d4 = document.getElementById('datejourfin').value;
	var d4 = d4.substring(6,10) + d4.substring(3,5) + d4.substring(0,2);
	var p1 = document.getElementById('affclient').value;
	var p2 = document.getElementById('affprojet').value;
	var v1 = document.getElementById('affvalid').value;
	var v2 = document.getElementById('affcollab').value;
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
			document.getElementById("req").innerHTML=xmlhttp.responseText;
		}
	}
	var a1 = "&d1="+d1;
	var a2 = "&d2="+d2;
	var a3 = "&p1="+p1;
	var a4 = "&p2="+p2;
	var a5 = "&v1="+v1;
	var a6 = "&v2="+v2;
	var a7 = "&d3="+d3;
	var a8 = "&d4="+d4;
	if (param == 0)
	{
		var adresfull = a1.concat(a2).concat(a3).concat(a4).concat(a5).concat(a6).concat(a7).concat(a8);
		xmlhttp.open("GET","valtemps-req.php?"+adresfull,true);
	}
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
	xmlhttp.open("GET","getnat2.php?a="+strana2,true);
	xmlhttp.send();
}
 
//Afficher profil dynamiquement
function showProfil(nat2)
{
	if (nat2 == "")
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
	xmlhttp.open("GET","getprofil.php?p="+nat2,true);
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

//Afficher le listing ou le reporting
function showListRep(param)
{
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
			document.getElementById("coeur").innerHTML=xmlhttp.responseText;
		}
	}
	if (param == 1)
	{
		xmlhttp.open("GET","listing-coeur.php?filt="+param,true);
		xmlhttp.send();
	}
	if (param == 2)
	{
		xmlhttp.open("GET","report-coeur.php?filt="+param,true);
		xmlhttp.send();
	}
}

//Afficher la requete du filtre dynamiquement
function showFilterResult(param)
{
	var page = document.getElementById('page').value;
	var d1 = document.getElementById('affmonth').value;
	var d2 = document.getElementById('affyear').value;
	var d3 = document.getElementById('datejourdeb').value;
	var d3 = d3.substring(6,10) + d3.substring(3,5) + d3.substring(0,2);
	var d4 = document.getElementById('datejourfin').value;
	var d4 = d4.substring(6,10) + d4.substring(3,5) + d4.substring(0,2);
	var p1 = document.getElementById('affclient').value;
	var p2 = document.getElementById('affprojet').value;
	var v1 = document.getElementById('affactivite').value;
	var v2 = document.getElementById('affphase').value;
	var v3 = document.getElementById('affclass').value;
	var v4 = document.getElementById('affpaie').value;
	var v5 = document.getElementById('afffrs').value;
	var c1 = document.getElementById('affcomp').value;
	var c2 = document.getElementById('afftype').value;
	var r1 = document.getElementById('affreportID').value;
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
			document.getElementById("req").innerHTML=xmlhttp.responseText;
		}
	}
	var a1 = "&d1="+d1;
	var a2 = "&d2="+d2;
	var a3 = "&p1="+p1;
	var a4 = "&p2="+p2;
	var a5 = "&v1="+v1;
	var a6 = "&v2="+v2;
	var a7 = "&v3="+v3;
	var a8 = "&v4="+v4;
	var a9 = "&c1="+c1;
	var a10 = "&c2="+c2;
	var a11 = "&d3="+d3;
	var a12 = "&d4="+d4;
	var a13 = "&v5="+v5;
	var a14 = "&r1="+r1;
	if (page == 1)
	{
		if (param == -2)
		{
			var adresfull = a1.concat(a2).concat(a3).concat(a4).concat(a5).concat(a6).concat(a7).concat(a8).concat(a9).concat(a10).concat(a11).concat(a12).concat(a13).concat(a14);
			xmlhttp.open("GET","report-req.php?"+adresfull,true);
		}
		if (param >= 0)
		{
			var a0 = "Action="+param;
			var adresfull = a0.concat(a1).concat(a2).concat(a3).concat(a4).concat(a5).concat(a6).concat(a7).concat(a8).concat(a9).concat(a10).concat(a11).concat(a12).concat(a13);
			xmlhttp.open("GET","listing-req.php?"+adresfull,true);
		}
	}
	if (page == 2)
	{
		if (param == -1)
		{
			var a0 = "Action=0";
			var adresfull = a0.concat(a1).concat(a2).concat(a3).concat(a4).concat(a5).concat(a6).concat(a7).concat(a8).concat(a9).concat(a10).concat(a11).concat(a12).concat(a13);
			xmlhttp.open("GET","listing-req.php?"+adresfull,true);
		}
		if (param == 0)
		{
			var adresfull = a1.concat(a2).concat(a3).concat(a4).concat(a5).concat(a6).concat(a7).concat(a8).concat(a9).concat(a10).concat(a11).concat(a12).concat(a13).concat(a14);
			xmlhttp.open("GET","report-req.php?"+adresfull,true);
		}
	}
	xmlhttp.send();
}


//Cachecache et calcul
function cacher(phase)
{
	if (phase==2)
	{
		//document.getElementById('f-actual1').style.display = 'none';
		document.getElementById('f-actual2').style.display = 'none';
		document.getElementById('f-rf1').style.display = 'block';
		document.getElementById('f-rf2').style.display = 'block';
	}
	else
	{
		//document.getElementById('f-actual1').style.display = 'block';
		document.getElementById('f-actual2').style.display = 'block';
		document.getElementById('f-rf1').style.display = 'none';
		document.getElementById('f-rf2').style.display = 'none';
	}
}

function cacher2(type)
{
	if (type==2)
	{
		document.getElementById('f-depenses').style.display = 'none';
		document.getElementById('f-recette').style.display = 'block';
	}
	else
	{
		document.getElementById('f-depenses').style.display = 'block';
		document.getElementById('f-recette').style.display = 'none';
	}
}

function activeFiltre(type)
{
	if (type==1)
	{
		document.getElementById('nofilter').style.display = 'none';
		document.getElementById('filter').style.display = 'block';
	}
	else
	{
		document.getElementById('nofilter').style.display = 'block';
		document.getElementById('filter').style.display = 'none';
	}
}

function bcalc(){
	if (document.getElementById('bUnit').value!=0 && document.getElementById('bQty').value!=0)
	{
		var bUnit = parseFloat(document.getElementById('bUnit').value).toFixed(2);
		var bQty = parseFloat(document.getElementById('bQty').value).toFixed(2);
		document.getElementById('bTot').value = parseFloat(bUnit * bQty).toFixed(2);
		document.getElementById('bTotHT').value = parseFloat(bUnit * bQty).toFixed(2);
	}
}

function cltcalc(){
	if (document.getElementById('cltUnit').value!=0 && document.getElementById('cltQty').value!=0)
	{
		var cltUnit = parseFloat(document.getElementById('cltUnit').value).toFixed(2);
		var cltQty = parseFloat(document.getElementById('cltQty').value).toFixed(2);
		document.getElementById('cltFact').value = parseFloat(cltUnit * cltQty).toFixed(2);
		document.getElementById('cltTotHT').value = parseFloat(cltUnit * cltQty).toFixed(2);
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



// SHOW/HIDE NEW FORM VS. EXISTING FORM (CLIENTS, COMPETITIONS...)
function showOption(option) {
	$('.show-option').hide();
	$('#show-option-' + option).show();
}

// SHOW/HIDE INPUT FORM (SAISI TEMPS, SAISIR FRAIS...)

$(document).ready(function(){
  $(document).on('click', '#toggle-title', function(e){
  	$( "#toggle-content" ).slideToggle( "slow", function() {});
  	$('#toggle-title i.fa').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
  });
  $(document).on('click', '#toggle-title2', function(e){
  	$( "#toggle-content2" ).slideToggle( "slow", function() {});
  	$('#toggle-title2 i.fa').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
  });
  $(document).on('click', '#toggle-title3', function(e){
  	$( "#toggle-content3" ).slideToggle( "slow", function() {});
  	$('#toggle-title3 i.fa').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
  });
});

// CHANGE PROFILE PICTURE

$(document).on('change', '.btn-file :file', function() {
  var input = $(this),
    numFiles = input.get(0).files ? input.get(0).files.length : 1,
    //label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    label = input.val();
  input.trigger('fileselect', [numFiles, label]);
});
$(document).ready( function() {
  $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
    $('#new-picture-label').text(label);
  });
});


// TEAM - SHOW / HIDE TROMBI OR TABLE VIEW

$(document).ready( function() {
  $('#effectif-buttons button').on('click', function() {
    $('#effectif-interne-table').toggle();
    $('#effectif-interne-trombi').toggle();
    // NB: to activate trombi for effectif externe, uncomment these 2 lines and in lit_collab.php, move the "display:none" from trombi to table
    // $('#effectif-externe-table').toggle();
    // $('#effectif-externe-trombi').toggle();
    $('#effectif-buttons button').attr("disabled",false);
    $(this).attr("disabled",true);
  });
});
