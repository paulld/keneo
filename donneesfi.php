<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	// ==================== TRAITEMENT =====================
	$prblm = 0;
	if (isset($_POST['new_user']))
	{
		$new_ctHybride = $_POST['new_ctHybride'];
		$new_ctReel = $_POST['new_ctReel'];
		$new_user = $_POST['new_user'];
		$new_validFrom = date('Y-m-d',mktime(0,0,0,substr($_POST['new_validFrom'],3,2),substr($_POST['new_validFrom'],0,2),substr($_POST['new_validFrom'],6,4)));
		$new_validTo = date('Y-m-d',mktime(0,0,0,substr($_POST['new_validTo'],3,2),substr($_POST['new_validTo'],0,2),substr($_POST['new_validTo'],6,4)));
		$req = "INSERT INTO rob_user_fi VALUES('','$new_user', '$new_ctHybride', '$new_ctReel', '$new_validFrom', '$new_validTo')";
		echo $req;
		$bdd->query($req);
	}
	else
	{
		if (isset($_POST['IDmodif']) AND isset($_POST['ctHybride']) AND isset($_POST['ctReel']) AND isset($_POST['validFrom']) AND isset($_POST['validTo']))
		{
			$validFrom = date('Y-m-d',mktime(0,0,0,substr($_POST['validFrom'],3,2),substr($_POST['validFrom'],0,2),substr($_POST['validFrom'],6,4)));
			$validTo = date('Y-m-d',mktime(0,0,0,substr($_POST['validTo'],3,2),substr($_POST['validTo'],0,2),substr($_POST['validTo'],6,4)));
			$req = "UPDATE rob_user_fi SET 
				ctHybride = ".$_POST['ctHybride'].",
				ctReel = ".$_POST['ctReel'].",
				validFrom = '".$validFrom."',
				validTo = '".$validTo."'
				WHERE ID = ".$_POST['IDmodif'];
			$bdd->query($req);
		}
		else
		{
			if (isset($_POST['IDmodif']))
			{
				$prblm = 1;
			}
		}
	}

	// ==================== SAISIE =====================
	?>
	
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li><li><a class="typ" href="#"><span>Donn&eacute;es financi&egrave;res</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Donn&eacute;es financi&egrave;res</div>

	<?php
	if ($prblm == 1)
	{
		echo '<div id="message">l\'enregistrement n\'a pas pu &ecirc;tre modifi&eacute;</div>';
	}
	?>
	
	<div id="coeur">
		<div id="sstitre">Effectif interne</div>
		<table id="tablerestit" class="table">
			<tr>
				<td id="t-containertit">Nom</td>
				<td id="t-containertit">Co&ucirc;t hybride</td>
				<td id="t-containertit">Co&ucirc;t r&eacute;el</td>
				<td id="t-containertit">Valide du</td>
				<td id="t-containertit">Valide jusqu'&agrave;</td>
				<td id="t-containertit">Actions</td>
			</tr>
			<?php
			$req = "SELECT T2.nom nom, T2.prenom prenom, T1.ID ID, T1.ctHybride ctHybride, T1.ctReel ctReel, T1.validFrom validFrom, T1.validTo validTo
					FROM rob_user_fi T1 
					INNER JOIN rob_user T2 ON T1.userID = T2.ID
					INNER JOIN rob_user_rights T3 ON T1.userID = T3.ID
					WHERE T3.extstd = 2 ORDER BY T2.nom, T1.validFrom";
			$reponse = $bdd->query($req );
			$i = 1;
			while ($donnee = $reponse->fetch() )
			{
				?>
				<form id="formbasic" action="donneesfi.php" method="post"><td id="t-ico<?php echo $i;?>">
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee['nom'].'. '.substr ($donnee['prenom'],0,1);?></td>
					<td id="t-container<?php echo $i;?>">
						<input type="text" id="f-Arrow<?php echo $i;?>" name="ctHybride" value="<?php echo $donnee['ctHybride'];?>" style="text-align:right" /></td>
					<td id="t-container<?php echo $i;?>">
						<input type="text" id="f-Arrow<?php echo $i;?>" name="ctReel" value="<?php echo $donnee['ctReel'];?>" style="text-align:right" /></td>
					<td id="t-container<?php echo $i;?>">
						<input type="text" id="f-Arrow<?php echo $i;?>" name="validFrom" value="<?php echo date("d/m/Y",strtotime($donnee['validFrom']));?>" /></td>
					<td id="t-container<?php echo $i;?>">
						<input type="text" id="f-Arrow<?php echo $i;?>" name="validTo" value="<?php echo date("d/m/Y",strtotime($donnee['validTo']));?>" /></td>
					<td id="t-container<?php echo $i;?>">
						<input type="hidden" value="<?php echo $donnee['ID'];?>" name="IDmodif" />
						<input id="w_input_90val" type="submit" Value="MaJ" />
					</td>
				</tr>
				</form>
				<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			?>
		</table>
		<div id="sstitre">Effectif externe</div>
		<table id="tablerestit" class="table">
			<tr>
				<td id="t-containertit">Nom</td>
				<td id="t-containertit">Co&ucirc;t hybride</td>
				<td id="t-containertit">Co&ucirc;t r&eacute;el</td>
				<td id="t-containertit">Valide du</td>
				<td id="t-containertit">Valide jusqu'&agrave;</td>
				<td id="t-containertit">Actions</td>
			</tr>
			<?php
			$req = "SELECT T2.nom nom, T2.prenom prenom, T1.ID ID, T1.ctHybride ctHybride, T1.ctReel ctReel, T1.validFrom validFrom, T1.validTo validTo
					FROM rob_user_fi T1 
					INNER JOIN rob_user T2 ON T1.userID = T2.ID
					INNER JOIN rob_user_rights T3 ON T1.userID = T3.ID
					WHERE T3.extstd = 1 ORDER BY T2.nom, T1.validFrom";
			$reponse = $bdd->query($req );
			$i = 1;
			while ($donnee = $reponse->fetch() )
			{
				?>
				<form action="donneesfi.php" method="post"><td id="t-ico<?php echo $i;?>">
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee['nom'].'. '.substr ($donnee['prenom'],0,1);?></td>
					<td id="t-container<?php echo $i;?>">
						<input type="text" id="f-Arrow<?php echo $i;?>" name="ctHybride" value="<?php echo $donnee['ctHybride'];?>" style="text-align:right" /></td>
					<td id="t-container<?php echo $i;?>">
						<input type="text" id="f-Arrow<?php echo $i;?>" name="ctReel" value="<?php echo $donnee['ctReel'];?>" style="text-align:right" /></td>
					<td id="t-container<?php echo $i;?>">
						<input type="text" id="f-Arrow<?php echo $i;?>" name="validFrom" value="<?php echo date("d/m/Y",strtotime($donnee['validFrom']));?>" /></td>
					<td id="t-container<?php echo $i;?>">
						<input type="text" id="f-Arrow<?php echo $i;?>" name="validTo" value="<?php echo date("d/m/Y",strtotime($donnee['validTo']));?>" /></td>
					<td id="t-container<?php echo $i;?>">
						<input type="hidden" value="<?php echo $donnee['ID'];?>" name="IDmodif" />
						<input id="w_input_90val" type="submit" Value="MaJ" />
					</td>
				</tr>
				</form>
				<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			?>
		</table>
	</div>

	<div id="sstitre">Ajouter une nouvelle combinaison</div>
	<table id="tablerestit" class="table">
		<tr>
				<td id="t-containertit">Nom</td>
				<td id="t-containertit">Co&ucirc;t hybride</td>
				<td id="t-containertit">Co&ucirc;t r&eacute;el</td>
				<td id="t-containertit">Valide du</td>
				<td id="t-containertit">Valide jusqu'&agrave;</td>
			<td id="t-containertit">&nbsp;</td>
		</tr>
		<form action="donneesfi.php" method="post">
		<tr>
			<td id="t-container">
				<?php
				echo '<select id="w_input_90" name="new_user"><option value=0>User...</option>';
				$reponse = $bdd->query("SELECT nom, prenom, ID FROM rob_user WHERE actif=1 ORDER BY nom");
				while ($donnee = $reponse->fetch() )
				{
					echo '<option value="'.$donnee['ID'].'">'.$donnee['nom'].'. '.substr ($donnee['prenom'],0,1).'</option>';
				}
				$reponse->closeCursor();
				echo '</select>';
				?>
			</td>
			<td id="t-container"><input type="text" name="new_ctHybride" style="text-align:right" /></td>
			<td id="t-container"><input type="text" name="new_ctReel" style="text-align:right" /></td>
			<td id="t-container"><input id="datefrais" type="text" name="new_validFrom" placeholder="JJ/MM/AAAA" /></td>
			<td id="t-container"><input id="dateTransac" type="text" name="new_validTo" placeholder="JJ/MM/AAAA" /></td>
			<td id="t-container"><input id="w_input_90val" type="submit" Value="Ajouter" /></td>
		</tr>
		</form>
	</table>

<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>