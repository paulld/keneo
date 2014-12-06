<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	//Mise à jour Infos
	$majinfo ='';
	$majinfo2 ='';
	if (isset($_POST['deadline1']))
	{
		$deadline = $_POST['deadline1'];
		//Verrouillage ticket-resto
		$req = "UPDATE rob_temps SET 
			ticketValid = '1' 
			WHERE datejour <= '".$deadline."' AND ticketValid = '0' AND ticket = '1'";
		$bdd->query($req);
		$majinfo =' - <strong>maj!</strong>';
	}
	if (isset($_POST['deverr']))
	{
		//Deerrouillage ticket-resto
		$req = "UPDATE rob_temps SET 
			ticketValid = '0' 
			WHERE ticketValid = '1'";
		$bdd->query($req);
		$majinfo2 =' - <strong>maj!</strong>';
	}
	?>

	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_adm.php">Administration</a></li>
		<li><a class="typ" href="#"><span>Gestion tickets restaurant</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Gestion tickets restaurant</div>
	
	<div id="coeur">
		<span class="titpart">D&eacute;finir la plage des dates &agrave; extraire sous Excel :</span>
		<form action="ticket-exp.php" method="post" target="_blank">
			<?php
			echo '<div class="small">';
			echo 'Extraction du <input size="8" type="text" id="datejourdeb" name="datejourdeb" value="';
			if (isset($datejourdeb))
			{
				echo $datejourdeb;
			}
			else
			{
				echo date("d/m/Y");
			}
			echo '" /> au <input size="8" type="text" id="datejourfin" name="datejourfin" value="';
			if (isset($datejourfin))
			{
				echo $datejourfin;
			}
			else
			{
				echo date("d/m/Y");
			}
			echo '" /> (inclu)</div>';
			echo '<div class="small"><input type="checkbox" name="remb" /> Inclure les tickets restaurant d&eacute;j&agrave; "rembours&eacute;s"</div>';
			echo '<div id="f-valider"><input id="buttonval" type="submit" Value="Extraire" name="Valider" /></div>';
			?> 
		</form>
		<span class="titpart">D&eacute;clarer les tickets restaurant, jusqu'&agrave; la date suivante, comme &eacute;tant rembours&eacute;s</span>
		<div class="small">(Il est recommand&eacute; d'avoir v&eacute;rrouill&eacute; les temps au pr&eacute;alable)</div>
		<form action="choixticket.php" method="post" >
			<?php
			echo '<input size="8" type="text" id="deadline1" name="deadline1" />';
			echo '<input id="buttonval" type="submit" Value="Verrouiller" name="Valider" onclick="return(confirm(\'Etes-vous s&ucirc;r de vouloir d&eacute;clarer tous les tickets restaurant jusqu &agrave; cette date comme rembours&eacute;s?\'))" />'.$majinfo;
			?> 
		</form>
		<span class="titpart">D&eacute;clarer tous les tickets restaurant comme &eacute;tant non rembours&eacute;s</span>
		<form action="choixticket.php" method="post" >
			<?php
			echo '<input type="hidden" name="deverr" />';
			echo '<input id="buttonval" type="submit" Value="Deverrouiller" name="Valider" onclick="return(confirm(\'Etes-vous s&ucirc;r de vouloir d&eacute;clarer tous les tickets restaurant comme non rembours&eacute;s?\'))" />'.$majinfo2;
			?> 
		</form>
	</div>
	
	<?php
	include("footer.php");
}
else
{
	include("index.php");
}
?>