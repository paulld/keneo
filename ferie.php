<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['newdateFerie']))
	{
		$dateFerie = date('Y-m-d',mktime(0,0,0,substr($_POST['newdateFerie'],3,2),substr($_POST['newdateFerie'],0,2),substr($_POST['newdateFerie'],6,4)));
		$checkcode = $bdd->query("SELECT dateFerie FROM rob_ferie WHERE dateFerie='$dateFerie'");
		$codepris = $checkcode->rowCount();
		$checkcode->closeCursor();
		if ($codepris != 0)
		{
			echo 'Ce jour f&eacute;ri&eacute; existe d&eacute;j&agrave';
		}
		else
		{
			$desc = $_POST['newdesc'];
			$desc = str_replace("'","\'",$desc);
			$bdd->query("INSERT INTO rob_ferie VALUES('$dateFerie', '$desc')");
		}
	}
	else
	{
		if (isset($_POST['modif']))
		{
			$dateFerie = $_POST['moddateFerie'];
			$desc = $_POST['moddesc'];
			$desc = str_replace("'","\'",$desc);
			$bdd->query("UPDATE rob_ferie SET Description='$desc' WHERE dateFerie='$dateFerie'");
		}
		else
		{
			if (isset($_POST['Suppr']))
			{
				$dateFerie = $_POST['moddateFerie'];
				$bdd->query("DELETE FROM rob_ferie WHERE dateFerie='$dateFerie'");
			}
		}
	}
	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
		<li><a class="typ" href="table.php"><span>Tables</span></a></li>
		<li><a class="typ" href="#"><span>Jours f&eacute;ri&eacute;s</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Jours f&eacute;ri&eacute;s</div>

	<div id="coeur">
		<table id="tablerestit" class="table table-striped temp-table">
			<tr>
				<td id="t-containertit">Date</td>
				<td id="t-containertit">Description</td>
				<td id="t-containertit" width="60px">Actions</td>
			</tr>
			<?php
			$req="SELECT dateFerie, Description
				FROM rob_ferie
				ORDER BY dateFerie DESC";
			$reponse = $bdd->query($req);
			$i=1;
			while ($donnee = $reponse->fetch() )
			{
			?><form action="ferie.php" method="post">
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo date("d/m/Y", strtotime($donnee[0]));?></td>
					<td id="t-container<?php echo $i;?>"><input id="w_input_90" type="text" size="50" value="<?php echo $donnee[1];?>" name="moddesc" /></td>
					<td id="t-ico<?php echo $i;?>">
						<input type="hidden" value="<?php echo $donnee[0];?>" name="moddateFerie" />
						&nbsp;<input id="btValid" type="submit" Value="V" title="Valider la modification" name="modif">
						&nbsp;<input id="btSuppr" type="submit" Value="S" title="Supprimer la ligne" name="Suppr" onclick="return(confirm(\'Etes-vous sur de vouloir supprimer cette entree?\'))" />
					</td>
				</tr>
			</form><?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();
			?>
		</table>
	</div>

	<div id="sstitre">Ajouter un nouveau jour f&eacute;ri&eacute;</div>
	<table id="tablerestit" class="table table-striped temp-table">
		<tr>
			<td id="t-containertit">Date</td>
			<td id="t-containertit">Description</td>
			<td id="t-containertit">Action</td>
		</tr>
		<form action="ferie.php" method="post">
		<tr>
			<td id="t-container"><input id="deadline1" type="text" size="20" name="newdateFerie" /></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="50" name="newdesc" /></td>
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