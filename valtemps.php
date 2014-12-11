<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
include("headerlight.php");

	// DATABASE: Get current row
	$req = "SELECT T2.ID userID, T2.matricule trig, T1.datejour date, T1.info info, T1.valeur jour, T1.ID ID, T1.validation validation,
			T6.Description activite, T3.description client, T4.description projet, T5.description mission 
		FROM rob_temps T1 
		INNER JOIN rob_user T2 ON T2.ID = T1.userID
		INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
		INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
		INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
		INNER JOIN rob_activite T6 ON T6.ID = T1.activID 
		INNER JOIN rob_user_rights T7 ON T7.ID = T1.userID 
		WHERE T7.id_hier = ".$_SESSION['ID']." AND validation = 0 
		ORDER BY T1.datejour DESC, T3.description, T4.description, T5.description LIMIT 30";
	$result = $bdd->query($req);
	$i = 1;
	$k = "";
	?>
	
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_adm.php">Administration</a></li>
		<li><a class="typ" href="#"><span>Validation des temps</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Validation des temps</div>
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
			while ($donnee = $result->fetch())
			{
			?>
				<tr>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee['trig'];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo date ("d/m/Y", strtotime($donnee['date']));?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee['activite'];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee['client'];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee['projet'];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee['mission'];?></td>
					<td id="t-container<?php echo $i.$k;?>"><?php echo $donnee['info'];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee['jour'];?></td>
					<td>
					<form id="ajax-form" class="autosubmit" method="POST" action="./valtemps-upd.php">
						<input type="checkbox" name="validation" 
						<?php if($donnee['validation'] == 0) { echo 'value="1"'; } else { echo 'value="0" checked'; } ?>
						/>
						<input id="where" type="hidden" name="ID" value="<?php echo $donnee['ID'] ?>" />
					</form>
					</td>
				</tr>

			<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			?>
		</table>
	</div>
	<?php
	$result->closeCursor();
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>
