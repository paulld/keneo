<?php
session_start();
include("appel_db.php");

	if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
	{
		include("headerlight.php");
		?>

		<!-- SAISIE -->
		<div id="navigationMap">
			<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li><li><a class="typ" href="collaborateurs.php"><span>Team management</span></a></li><li><a class="typ" href="#"><span>Modification</span></a></li></ul>
		</div>
		<div id="clearl"></div>
		<div id="haut">Modification</div>

		<div id="coeur">
			<?php
			if (isset($_POST['IDmodif']))
			{
				$req = "SELECT T1.ID, T2.nom, T2.prenom, T2.matricule, T6.matricule, T7.code, T3.desc2, T8.desc4, T4.desc5, T5.desc6, T9.telephone, T9.mobile, T9.mail, T9.pays, T9.ville, T9.CP, T9.adresse, T10.recup, T10.cp, T10.rtt, T1.extstd
						FROM rob_user_rights T1 
						INNER JOIN rob_user T2 ON T1.ID = T2.ID
						INNER JOIN rob_level T3 ON T1.id_lev_menu = T3.ID
						INNER JOIN rob_level T4 ON T1.id_lev_exp = T4.ID
						INNER JOIN rob_level T5 ON T1.id_lev_jrl = T5.ID
						INNER JOIN rob_level T8 ON T1.id_lev_tms = T8.ID
						INNER JOIN rob_user T6 ON T1.id_hier = T6.ID
						INNER JOIN rob_pole T7 ON T1.id_pole = T7.ID
						INNER JOIN rob_user_info T9 ON T1.ID = T9.ID
						INNER JOIN rob_user_abs T10 ON T1.ID = T10.ID
						WHERE T1.ID = ".$_POST['IDmodif'];
				$reponse = $bdd->query($req );
				$checkrep = $reponse->rowCount();
				if ($checkrep != 0)
				{
					?>
					<form action="collaborateurs.php" method="post">
					<?php
					while ($cur_nom = $reponse->fetch())
					{
						echo '<div class="small">Nom : <input id="w_input_75" type="text" name="nom" value="'.$cur_nom[1].'" /></div>';
						echo '<div class="small">Pr&eacute;nom : <input id="w_input_75" type="text" name="prenom" value="'.$cur_nom[2].'" /></div>';
						echo '<div class="small">Trigramme : <input id="w_input_75" type="text" size="5" name="trig" value="'.$cur_nom[3].'" /></div>';
						echo '<div class="small">Effectif de type : <select id="w_input_75" name="extstd">';
							echo '<option value=1>Externe</option>';
							if ($cur_nom[20] == 2) { echo '<option value=2 selected>Interne</option>'; }
								else { echo '<option value=2>Interne</option>'; }
							echo '</select></div>';
						echo '<div class="small">Responsable hi&eacute;rarchique : <select id="w_input_75" name="resp">';
							$reponse2 = $bdd->query("SELECT matricule, ID FROM rob_user ORDER BY matricule");
							while ($donnee2 = $reponse2->fetch() )
							{
								if ($donnee2['matricule'] == $cur_nom[4]) { echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['matricule'].'</option>'; }
								else { echo '<option value="'.$donnee2['ID'].'">'.$donnee2['matricule'].'</option>'; }
							}
							$reponse2->closeCursor();
							echo '</select></div>';
						echo '<div class="small">Affect&eacute; au p&ocirc;le : <select id="w_input_75" name="pole">';
							$reponse2 = $bdd->query("SELECT code, ID FROM rob_pole WHERE actif = 1");
							while ($donnee2 = $reponse2->fetch() )
							{
								if ($donnee2['code'] == $cur_nom[5]) { echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['code'].'</option>'; }
								else { echo '<option value="'.$donnee2['ID'].'">'.$donnee2['code'].'</option>'; }
							}
							$reponse2->closeCursor();
							echo '</select></div>';
						echo '<div class="small">Acc&egrave;s aux menus : <select id="w_input_75" name="user_lev">';
							$req = "SELECT desc2, ID FROM rob_level WHERE actif = 1 AND desc2 <> ''";
							$reponse2 = $bdd->query($req);
							while ($donnee2 = $reponse2->fetch() )
							{
								if ($donnee2['desc2'] == $cur_nom[6]) { echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['desc2'].'</option>'; }
								else { echo '<option value="'.$donnee2['ID'].'">'.$donnee2['desc2'].'</option>'; }
							}
							$reponse2->closeCursor();
							echo '</select></div>';
						echo '<div class="small">Droits sur la timesheet : <select id="w_input_75" name="tms_lev">';
							$req = "SELECT desc4, ID FROM rob_level WHERE actif = 1 AND desc4 <> ''";
							$reponse2 = $bdd->query($req);
							while ($donnee2 = $reponse2->fetch() )
							{
								if ($donnee2['desc4'] == $cur_nom[7]) { echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['desc4'].'</option>'; }
								else { echo '<option value="'.$donnee2['ID'].'">'.$donnee2['desc4'].'</option>'; }
							}
							$reponse2->closeCursor();
							echo '</select></div>';
						echo '<div class="small">Droits sur les frais : <select id="w_input_75" name="exp_lev">';
							$req = "SELECT desc5, ID FROM rob_level WHERE actif = 1 AND desc5 <> ''";
							$reponse2 = $bdd->query($req);
							while ($donnee2 = $reponse2->fetch() )
							{
								if ($donnee2['desc5'] == $cur_nom[8]) { echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['desc5'].'</option>'; }
								else { echo '<option value="'.$donnee2['ID'].'">'.$donnee2['desc5'].'</option>'; }
							}
							$reponse2->closeCursor();
							echo '</select></div>';
						echo '<div class="small">Droits sur les journaux : <select id="w_input_75" name="jrl_lev">';
							$req = "SELECT desc6, ID FROM rob_level WHERE actif = 1 AND desc6 <> ''";
							$reponse2 = $bdd->query($req);
							while ($donnee2 = $reponse2->fetch() )
							{
								if ($donnee2['desc6'] == $cur_nom[9]) { echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['desc6'].'</option>'; }
								else { echo '<option value="'.$donnee2['ID'].'">'.$donnee2['desc6'].'</option>'; }
							}
							$reponse2->closeCursor();
							echo '</select></div>';
						echo '<div class="small">T&eacute;l&eacute;phone : <input id="w_input_75" type="text" size="20" name="tel" value="'.$cur_nom[10].'" /></div>';
						echo '<div class="small">Mobile : <input id="w_input_75" type="text" size="20" name="mobile" value="'.$cur_nom[11].'" /></div>';
						echo '<div class="small">Email : <input id="w_input_75" type="text" size="50" name="mail" value="'.$cur_nom[12].'" /></div>';
						echo '<div class="small">Adresse : <input id="w_input_75" type="text" size="50" name="adresse" value="'.$cur_nom[16].'" /></div>';
						echo '<div class="small">CP : <input id="w_input_75" type="text" size="7" name="cp" value="'.$cur_nom[15].'" /></div>';
						echo '<div class="small">Ville : <input id="w_input_75" type="text" size="20" name="ville" value="'.$cur_nom[14].'" /></div>';
						echo '<div class="small">Pays : <input id="w_input_75" type="text" size="15" name="pays" value="'.$cur_nom[13].'" /></div>';
						//echo '<br/><div class="small">R&eacute;cup : <input id="w_input_75" type="text" size="7" name="absrecup" value="'.$cur_nom[17].'" /></div>';
							$req = "SELECT sum(recup) FROM rob_temps 
								WHERE userID=".$_POST['IDmodif']." AND recup <> 0 AND phaseID='1' AND recupValid IS NULL
								GROUP BY userID";
							$check = $bdd->query($req);
							$checkrow=$check->rowCount();
							if ($checkrow != 0)
							{
								$dchk = $check->fetch();
								echo '<br/><div class="small">R&eacute;cup : '.number_format($dchk[0],2,",","").'<input type="hidden" name="absrecup" value="0" /></div>';
							} else {
								echo '<br/><div class="small">R&eacute;cup : '.number_format(0,2,",","").'<input type="hidden" name="absrecup" value="0" /></div>';
							}
							$check->closeCursor();
						echo '<div class="small">Cong&eacute;s pay&eacute;s : <input id="w_input_75" type="text" size="5" name="abscp" value="'.$cur_nom[18].'" /></div>';
						echo '<div class="small">RTT : <input id="w_input_75" type="text" size="15" name="absrtt" value="'.$cur_nom[19].'" /></div>';
					}
						echo '<div id="f-valider"><input type="hidden" value="'.$_POST['IDmodif'].'" name="IDmodif" /><input id="w_input_90val" type="submit" Value="Enregistrer" name="Valider" /></div>';
						?>
					</form>
					<?php
				}
				else
				{
					echo 'on n\'a rien';
				}
				$reponse->closeCursor();
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