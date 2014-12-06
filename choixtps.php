<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");
	?>

	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_adm.php">Administration</a></li>
		<li><a class="typ" href="#"><span>Export des temps</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Export des temps</div>
	
	<div id="coeur">
		D&eacute;finir la plage des dates &agrave; extraire sous Excel :
		<form action="temps-exp.php" method="post" target="_blank">
			<?php
			echo 'Du <input size="8" type="text" id="datejourdeb" name="datejourdeb" value="';
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
			echo '" /> (inclu) <input id="buttonval" type="submit" Value="Extraire" name="Valider" />';
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