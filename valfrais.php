<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	$ok='';
	if (isset($_POST['Valider']))
	{
		$flag = $_POST['flag'];
		$dateval = date("Y-m-d");
		$bdd->query("UPDATE rob_frais SET validation = 2, datevalid = '$dateval' WHERE noteNum='$flag'");
		$ok = 'La note de frais '.$flag.' a &eacute;t&eacute; valid&eacute;e le '.date("d/m/Y");
	}
	else
	{
		if (isset($_POST['Rejeter']))
		{
			$flag = $_POST['flagrej'];
			$dateval = date("Y-m-d");
			$bdd->query("UPDATE rob_frais SET validation = 1, datevalid = '$dateval' WHERE noteNum='$flag'");
			$ok = 'La note de frais '.$flag.' a &eacute;t&eacute; rejett&eacute;e le '.date("d/m/Y");
		}
	}
	?>
		
    <!-- =================== SAISIE ================= -->
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_adm.php">Administration</a></li>
		<li><a class="typ" href="#"><span>Validation des frais</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Validation des frais</div>

	<div id="coeur">
		<form action="valfrais.php" method="post">
			<select name="flag" />
				<option>S&eacute;lectionez la note...</option>
				<?php
				$reqimput = $bdd->query("SELECT DISTINCT noteNum FROM rob_frais WHERE validation != 2 AND noteNum != '' ORDER BY noteNum");
				while ($optimput = $reqimput->fetch())
				{
					echo '<option value='.$optimput['noteNum'].'>'.$optimput['noteNum'].'</option>';
				}
				$reqimput->closeCursor();
				?>
			</select>
			<input id="buttonval" type="submit" Value="Valider la note" name="Valider" />
		</form><br/>

		<form action="valfrais.php" method="post">
			<input type="text" size="20" name="flagrej" />
			<input id="buttonval" type="submit" Value="Rejeter la note" name="Rejeter" />
		</form>

		<?php
		if ($ok != '')
		{
			echo '<div id="f-descriptif"><b>'.$ok.'</b></div>';
		}
		?>

	</div>
	
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>