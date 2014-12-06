<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$userID = $_POST['userID'];
		$bdd->query("UPDATE rob_temps SET recup=0, recupValid='0000-00-00' WHERE ID='$id'");
	}

	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_adm.php">Administration</a></li>
		<li><a class="typ" href="#"><span>Suivi des r&eacute;cup&eacute;rations</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Suivi des r&eacute;cup&eacute;rations</div>

	<?php
	if (isset($_POST['IDinact']))
	{
		echo '<div id="message">'.$_POST['val'].' jour de r&eacute;cup&eacute;ration ont &eacute;t&eacute; annul&eacute; pour '.$_POST['trig'].'</div>';
	}
	?>
	<div id="coeur">
		<table id="tablerestit">
			<tr>
				<td id="t-containertit">Trigramme</td>
				<td id="t-containertit">Date</td>
				<td id="t-containertit">Activit&eacute;</td>
				<td id="t-containertit">Client</td>
				<td id="t-containertit">Projet</td>
				<td id="t-containertit">Mission</td>
				<td id="t-containertit">Description</td>
				<td id="t-containertit">Dur&eacute;e</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$req = "SELECT T2.matricule, T1.datejour, T6.Description, T3.description, T4.description, T5.description, T1.info, T1.valeur, T1.ID, T2.ID FROM rob_temps T1 
				INNER JOIN rob_user T2 ON T2.ID = T1.userID
				INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
				INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
				INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
				INNER JOIN rob_activite T6 ON T6.ID = T1.activID 
				WHERE recup <> 0
				ORDER BY T1.datejour DESC, T3.description, T4.description, T5.description";
			$reponse = $bdd->query($req);
			$i=1;
			while ($donnee = $reponse->fetch() )
			{
				if (strtotime(date("Y-m-d")) - strtotime($donnee[1]) > 1814400) {$k="s";} else { $k="";}
				?>
				<form action="recup.php" method="post">
				<tr>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee[0];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo date ("d/m/Y", strtotime($donnee[1]));?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee[2];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee[3];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee[4];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee[5];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee[6];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[7];?></td>
					<td id="t-ico<?php echo $i;?>">
							<input type="hidden" value="<?php echo $donnee[8];?>" name="IDinact" />
							<input type="hidden" value="<?php echo $donnee[7];?>" name="val" />
							<input type="hidden" value="<?php echo $donnee[9];?>" name="userID" />
							<input type="hidden" value="<?php echo $donnee[0];?>" name="trig" />
							&nbsp;<input id="btSuppr" type="submit" Value="S" title="Supprimer la r&eacute;cup&eacute;ration"  onclick="return(confirm(\'Etes-vous sur de vouloir supprimer cette entr&eacute;e?\'))" />
					</td>
				</tr>
				</form>
				<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();
			?>
		</table>
	</div>
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>