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

	<div class="background-temps background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container" id="historique-temps">
		<div class="section-title">
			<h1>Gestion tickets restaurant</h1>
		</div>
		
		<h2>Extraction</h2>
		<form action="ticket-exp.php" method="post" target="_blank">
			<div class="form-inner">
				<input class="form-control form-control-small form-control-centered" type="text" id="datejourdeb" name="datejourdeb" placeholder="A partir du..." />
				<input class="form-control form-control-small form-control-centered" type="text" id="datejourfin" name="datejourfin" placeholder="Jusqu&#39;au..." />
				<input type="checkbox" name="remb" /> 
				<span>Inclure les tickets restaurant d&eacute;j&agrave; "rembours&eacute;s"</span>
				<input class="btn btn-small btn-primary" type="submit" Value="Extraire" name="Valider" />
			</div>
		</form>
		
		<h2>V&eacute;rrouillage</h2>
		<form action="choixticket.php" method="post" target="_blank">
			<div class="form-inner">
				<input class="form-control form-control-small form-control-centered" type="text" id="deadline1" name="deadline1" />
				<input class="btn btn-small btn-primary" type="submit" Value="Verrouiller" name="Valider" onclick="return(confirm(\'Etes-vous s&ucirc;r de vouloir d&eacute;clarer tous les tickets restaurant jusqu &agrave; cette date comme rembours&eacute;s?\'))" />
				<?php $majinfo; ?> 
			</div>
		</form>

		<h2>D&eacute;clarer tous les tickets restaurant comme &eacute;tant non rembours&eacute;s</h2>
		<form action="choixticket.php" method="post" target="_blank">
			<div class="form-inner">
				<input type="hidden" name="deverr" />
				<input class="btn btn-small btn-primary" type="submit" Value="Deverrouiller" name="Valider" onclick="return(confirm(\'Etes-vous s&ucirc;r de vouloir d&eacute;clarer tous les tickets restaurant comme non rembours&eacute;s?\'))" />
				<?php $majinfo2; ?> 
			</div>
		</form>
	</section>
	
	<?php
	include("footer.php");
}
else
{
	include("index.php");
}
?>