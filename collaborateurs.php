<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	// ==================== TRAITEMENT =====================
 	if (isset($_POST['IDinact']))
 	{
 		$id = $_POST['IDinact'];
 		$bdd->query("UPDATE rob_user SET actif=0 WHERE ID='$id'");
 	}
 	else
 	{
		if (isset($_POST['IDact']))
 		{
 			$id = $_POST['IDact']; 
			$bdd->query("UPDATE rob_user SET actif=1 WHERE ID='$id'");
 		}
		else 
		{
			if (isset($_POST['new_mat']))
 			{
 				$matricule = strtoupper($_POST['new_mat']);
				//existence de l'utilsateur ou non
 				$reponse=$bdd->query("SELECT ID FROM rob_user WHERE matricule = '$matricule'");
				$checkrep=$reponse->rowCount();
				$reponse->closeCursor();
				//il n'existe pas
				if ($checkrep == 0)
				{
					$prenom = ucfirst($_POST['new_prenom']);
					$nom = strtoupper($_POST['new_nom']);
					$newmail = strtolower(substr($prenom,0,1).$nom).'@keneo.fr';
					$bdd->query("INSERT INTO rob_user VALUES('', '$matricule', '$nom', '$prenom', 'fr', 'keneo', 1)");
					//récupération de l'ID nouvellement créé
					$reponse=$bdd->query("SELECT ID FROM rob_user WHERE matricule = '$matricule' LIMIT 1");
					$donnee = $reponse->fetch();
					$newID = $donnee['ID'];
					$reponse->closeCursor();
					//Alimentation des tables dépendantes
					$new_pole = $_POST['new_pole'];
					$new_resp = $_POST['new_resp'];
					$new_user = $_POST['new_user'];
					$new_tms = $_POST['new_tms'];
					$new_exp = $_POST['new_exp'];
					$new_jrl = $_POST['new_jrl'];
					$bdd->query("INSERT INTO rob_user_rights VALUES('$newID', '$new_user', '$new_tms', '$new_exp', '$new_jrl', '$new_resp', '$new_pole', 1, 2, 0)");
					$bdd->query("INSERT INTO rob_user_abs VALUES('$newID', 0, 0, 0, 0)");
					$bdd->query("INSERT INTO rob_user_info VALUES('$newID', '', '', '$newmail', '', '', '', '', 0)");
					$bdd->query("INSERT INTO rob_user_fi VALUES('','$newID', 0, 0, '2001-01-01', '9999-01-01')");
				}
				else
				{
					echo 'Trigramme d&eacute;j&agrave; existant';
				}
 			}
 			else
 			{
				if (isset($_POST['IDmodif']))
				{
					$req = "UPDATE rob_user SET 
						matricule = '".strtoupper($_POST['trig'])."',
						nom = '".strtoupper($_POST['nom'])."',
						prenom = '".ucfirst($_POST['prenom'])."'
						WHERE ID = ".$_POST['IDmodif'];
					$bdd->query($req);
					$req = "UPDATE rob_user_info SET 
						telephone = '".$_POST['tel']."',
						mobile = '".$_POST['mobile']."',
						mail = '".strtolower($_POST['mail'])."',
						adresse = '".$_POST['adresse']."',
						cp = '".$_POST['cp']."',
						ville = '".ucfirst($_POST['ville'])."',
						pays = '".strtoupper($_POST['pays'])."'
						WHERE ID = ".$_POST['IDmodif'];
					$bdd->query($req);
					$req = "UPDATE rob_user_rights SET 
						id_lev_menu = ".$_POST['user_lev'].",
						id_lev_exp = ".$_POST['exp_lev'].",
						id_lev_tms = ".$_POST['tms_lev'].",
						id_lev_jrl = ".$_POST['jrl_lev'].",
						id_hier = ".$_POST['resp'].",
						id_pole = ".$_POST['pole'].", 
						extstd = ".$_POST['extstd']." 
						WHERE ID = ".$_POST['IDmodif'];
					$bdd->query($req);
					$req = "UPDATE rob_user_abs SET 
						recup = ".$_POST['absrecup'].",
						cp = ".$_POST['abscp'].",
						rtt = ".$_POST['absrtt']."
						WHERE ID = ".$_POST['IDmodif'];
					$bdd->query($req);
				}
			}
		}
	}

	// ==================== SAISIE =====================
	?>
	
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li><li><a class="typ" href="#"><span>Team management</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Team management</div>

	<div id="coeur">
		<div id="sstitre">Effectif interne</div>
		<table id="tablerestit">
			<tr>
				<td id="t-containertit">Nom</td>
				<td id="t-containertit">Trig.</td>
				<td id="t-containertit">Acc&egrave;s menu</td>
				<td id="t-containertit">Timesheet</td>
				<td id="t-containertit">Frais</td>
				<td id="t-containertit">Journal</td>
				<td id="t-containertit">Resp.</td>
				<td id="t-containertit">Pole</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$req = "SELECT T2.nom, T2.prenom, T2.matricule, T6.matricule, T7.code, T3.menu, T8.tms, T4.exp, T5.jrl, T2.actif, T2.ID
					FROM rob_user_rights T1 
					INNER JOIN rob_user T2 ON T1.ID = T2.ID
					INNER JOIN rob_level T3 ON T1.id_lev_menu = T3.ID
					INNER JOIN rob_level T4 ON T1.id_lev_exp = T4.ID
					INNER JOIN rob_level T5 ON T1.id_lev_jrl = T5.ID
					INNER JOIN rob_level T8 ON T1.id_lev_tms = T8.ID
					INNER JOIN rob_user T6 ON T1.id_hier = T6.ID
					INNER JOIN rob_pole T7 ON T1.id_pole = T7.ID
					WHERE T1.id_lev_menu <= ".$_SESSION['id_lev_menu']." AND T7.actif = 1 AND extstd = 2 ORDER BY T2.nom";
			$reponse = $bdd->query($req );
			$i = 1;
			while ($donnee = $reponse->fetch() )
			{
				?>
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[0].'. '.substr ($donnee[1],0,1);?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[2];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[5];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[6];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[7];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[8];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[3];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[4];?></td>
					<?php if ($donnee[9] == 1)
					{
						?>
						<form action="collaborateurs.php" method="post"><td id="t-ico<?php echo $i;?>">
							<input type="hidden" value="<?php echo $donnee[10];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver un collaborateur" >
						</td></form>
						<?php
					}
					else 
					{
						?>
						<form action="collaborateurs.php" method="post"><td id="t-ico<?php echo $i;?>">
							<input type="hidden" value="<?php echo $donnee[10];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer un collaborateur" >
						</td></form>
						<?php 
					} 
					?>
					<form action="modif_collab.php" method="post"><td id="t-ico<?php echo $i;?>">
						<input type="hidden" value="<?php echo $donnee[10];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier un collaborateur" name="modif">
					</td></form>
				</tr>
				<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			?>
		</table>
		<div id="sstitre">Effectif externe</div>
		<table id="tablerestit">
			<tr>
				<td id="t-containertit">Nom</td>
				<td id="t-containertit">Trig.</td>
				<td id="t-containertit">Acc&egrave;s menu</td>
				<td id="t-containertit">Timesheet</td>
				<td id="t-containertit">Frais</td>
				<td id="t-containertit">Journal</td>
				<td id="t-containertit">Resp.</td>
				<td id="t-containertit">Pole</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$reponse->closeCursor();			
			$req = "SELECT T2.nom, T2.prenom, T2.matricule, T6.matricule, T7.code, T3.menu, T8.tms, T4.exp, T5.jrl, T2.actif, T2.ID
					FROM rob_user_rights T1 
					INNER JOIN rob_user T2 ON T1.ID = T2.ID
					INNER JOIN rob_level T3 ON T1.id_lev_menu = T3.ID
					INNER JOIN rob_level T4 ON T1.id_lev_exp = T4.ID
					INNER JOIN rob_level T5 ON T1.id_lev_jrl = T5.ID
					INNER JOIN rob_level T8 ON T1.id_lev_tms = T8.ID
					INNER JOIN rob_user T6 ON T1.id_hier = T6.ID
					INNER JOIN rob_pole T7 ON T1.id_pole = T7.ID
					WHERE T1.id_lev_menu <= ".$_SESSION['id_lev_menu']." AND T7.actif = 1 AND extstd = 1 ORDER BY T2.nom";
			$reponse = $bdd->query($req );
			$i = 1;
			while ($donnee = $reponse->fetch() )
			{
				?>
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[0].'. '.substr ($donnee[1],0,1);?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[2];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[5];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[6];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[7];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[8];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[3];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[4];?></td>
					<?php if ($donnee[9] == 1)
					{
						?>
						<form action="collaborateurs.php" method="post"><td id="t-ico<?php echo $i;?>">
							<input type="hidden" value="<?php echo $donnee[10];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver un collaborateur" >
						</td></form>
						<?php
					}
					else 
					{
						?>
						<form action="collaborateurs.php" method="post"><td id="t-ico<?php echo $i;?>">
							<input type="hidden" value="<?php echo $donnee[10];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer un collaborateur" >
						</td></form>
						<?php 
					} 
					?>
					<form action="modif_collab.php" method="post"><td id="t-ico<?php echo $i;?>">
						<input type="hidden" value="<?php echo $donnee[10];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier un collaborateur" name="modif">
					</td></form>
				</tr>
				<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();			
			?>
		</table>
	</div>

	<div id="sstitre">Ajouter un nouveau collaborateur</div>
	<table id="tablerestit">
		<tr>
			<td id="t-containertit">Nom Pr&eacute;nom</td>
			<td id="t-containertit">Trig.</td>
			<td id="t-containertit">Resp.</td>
			<td id="t-containertit">Pole</td>
			<td id="t-containertit">Acc&egrave;s menu</td>
			<td id="t-containertit">Timesheet</td>
			<td id="t-containertit">Frais</td>
			<td id="t-containertit">Journal</td>
			<td id="t-containertit">&nbsp;</td>
		</tr>
		<form action="collaborateurs.php" method="post">
		<tr>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="12" name="new_nom" placeholder="NOM" value=""/> <input id="w_inputtxt_90" type="text" size="10" name="new_prenom" placeholder="Pr&eacute;nom" value=""/></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="5" name="new_mat" placeholder="XXX" value=""/></td>
			<td id="t-container">
				<?php
				echo '<select id="w_input_90" name="new_resp"><option value=1>Resp...</option>';
				$reponse = $bdd->query("SELECT matricule, ID FROM rob_user WHERE actif=1 ORDER BY matricule");
				while ($donnee = $reponse->fetch() )
				{
					echo '<option value="'.$donnee['ID'].'">'.$donnee['matricule'].'</option>';
				}
				$reponse->closeCursor();
				echo '</select>';
				?>
			</td>
			<td id="t-container">
				<?php
				echo '<select id="w_input_90" name="new_pole">';
				$reponse = $bdd->query("SELECT code, ID FROM rob_pole WHERE actif=1 ORDER BY ID");
				while ($donnee = $reponse->fetch() )
				{
					echo '<option value="'.$donnee['ID'].'">'.$donnee['code'].'</option>';
				}
				$reponse->closeCursor();
				echo '</select>';
				?>
			</td>
			<td id="t-container">
				<?php
				echo '<select id="w_input_90" name="new_user">';
				$reponse = $bdd->query("SELECT desc2, ID FROM rob_level WHERE desc2 <> '' ORDER BY ID");
				while ($donnee = $reponse->fetch() )
				{
					echo '<option value="'.$donnee['ID'].'">'.$donnee['desc2'].'</option>';
				}
				$reponse->closeCursor();
				echo '</select>';
				?>
			</td>
			<td id="t-container">
				<?php
				echo '<select id="w_input_90" name="new_tms">';
				$reponse = $bdd->query("SELECT desc4, ID FROM rob_level WHERE desc4 <> '' ORDER BY ID");
				while ($donnee = $reponse->fetch() )
				{
					echo '<option value="'.$donnee['ID'].'">'.$donnee['desc4'].'</option>';
				}
				$reponse->closeCursor();
				echo '</select>';
				?>
			</td>
			<td id="t-container">
				<?php
				echo '<select id="w_input_90" name="new_exp">';
				$reponse = $bdd->query("SELECT desc5, ID FROM rob_level WHERE desc5 <> '' ORDER BY ID");
				while ($donnee = $reponse->fetch() )
				{
					echo '<option value="'.$donnee['ID'].'">'.$donnee['desc5'].'</option>';
				}
				$reponse->closeCursor();
				echo '</select>';
				?>
			</td>
			<td id="t-container">
				<?php
				echo '<select id="w_input_90" name="new_jrl">';
				$reponse = $bdd->query("SELECT desc6, ID FROM rob_level WHERE desc6 <> '' ORDER BY ID");
				while ($donnee = $reponse->fetch() )
				{
					echo '<option value="'.$donnee['ID'].'">'.$donnee['desc6'].'</option>';
				}
				$reponse->closeCursor();
				echo '</select>';
				?>
			</td>				
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