<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND ($_SESSION['id_lev_tms'] >= 4 OR $_SESSION['id_lev_exp'] >= 4))
{
	include("headerlight.php");
	?>

	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_adm.php">Administration</a></li>
		<li><a class="typ" href="#"><span>Exports Excel</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Exports Excel</div>
	
	<div id="coeur">
		<?php
		if ($_SESSION['id_lev_tms'] >= 4)
		{
		?>
		<div id="sstitre">Export des temps</div>
		<form action="temps-exp.php" method="post" target="_blank">
			<?php
			echo 'Export des temps du <input size="8" type="text" id="datejourdeb" name="datejourdeb" value="';
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
		<?php
		}
		if ($_SESSION['id_lev_exp'] >= 4)
		{
		?>
		<div id="sstitre">Export des frais</div>
		<form action="frais-exp.php" method="post" target="_blank">
			<div id="f-descriptif"><?php
			echo 'Export des frais du <input size="8" type="text" id="datejourstrt" name="datejourstrt" value="';
				if (isset($datejourdeb))
				{
					echo $datejourdeb;
				}
				else
				{
					echo date("d/m/Y");
				}
				echo '" /> au <input size="8" type="text" id="datejourend" name="datejourend" value="';
				if (isset($datejourfin))
				{
					echo $datejourfin;
				}
				else
				{
					echo date("d/m/Y");
				}
				echo '" /> (inclu)';
			//option
			echo ' - <select name="validation">';
				echo'<option value="2">Valid&eacute;s seulement</option>';
				echo'<option value="1">Valid&eacute;es et en attente de validation</option>';
				echo'<option value="0">Tous les frais</option>';
			echo '</select> <input id="buttonval" type="submit" Value="Extraire" name="Valider" />';
			?> 
			</div>
		</form>
		<?php
		}
		?>
	</div>
	<?php
	include("footer.php");
}
else
{
	header("location:accueil.php");
}
?>