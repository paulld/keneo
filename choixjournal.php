<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headersom.php");
	?>

	<div id="menu">
		<div id="navigationMap">
			<ul><li><a class="typ" href="accueil.php"><img src="images/RoB_Home.png" /></a></li><li><a class="typ" href="#"><span>Extraction du journal</span></a></li></ul>
		</div>
	
		<form action="journal-exp.php" method="post" target="_blank">
			<div id="tablesaisie">
				<div id="f-fraisl">
					<?php
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
						echo '" /> inclu';
					?>
				</div>
				<div id="f-fraisr">
					Phase : <select name="phaseID">
					<?php
					$reqimput = $bdd->query("SELECT * FROM rob_phase WHERE actif = 1 ORDER BY ID");
					while ($optimput = $reqimput->fetch())
					{
						echo '<option value='.$optimput['ID'].'>'.$optimput['Phase'].' - '.$optimput['Description'].'</option>';
					}
					$reqimput->closeCursor();
					?>
					</select>
				</div>
			</div>
			<div id="f-valider">
				<?php
				echo '<input id="buttonval" type="submit" Value="Extraire" name="Valider" />';
				?> 
			</div>
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