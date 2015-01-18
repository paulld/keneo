<?php
session_start();
include("appel_db.php");

	if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
	{
		include("headerlight.php");
		?>

		<!-- Background Image Specific to each page -->
		<div class="background-equipe background-image"></div>
		<div class="overlay"></div>

		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
					<div class="section-container section-param" id="mes-parametres">
						
						<section id="param-contacts">
							<?php
							if (isset($_POST['IDmodif'])) {
								$req = "SELECT T1.ID id, T2.nom nom, T2.prenom premom, T2.matricule matricule, 
															 T6.matricule, T7.code, T3.menu, T8.tms, T4.exp, T5.jrl, 
															 T9.telephone, T9.mobile, T9.mail, T9.pays, T9.ville, T9.CP, T9.adresse, 
															 T10.recup, T10.cp, T10.rtt, T1.extstd
										FROM rob_user_rights T1 
										INNER JOIN rob_user T2 ON T1.ID = T2.ID
										INNER JOIN rob_level T3 ON T1.id_lev_menu = T3.ID
										INNER JOIN rob_level T4 ON T1.id_lev_exp = T4.ID
										INNER JOIN rob_level T5 ON T1.id_lev_jrl = T5.ID
										INNER JOIN rob_level T8 ON T1.id_lev_tms = T8.ID
										LEFT JOIN rob_user T6 ON T1.id_hier = T6.ID
										INNER JOIN rob_pole T7 ON T1.id_pole = T7.ID
										INNER JOIN rob_user_info T9 ON T1.ID = T9.ID
										INNER JOIN rob_user_abs T10 ON T1.ID = T10.ID
										WHERE T1.ID = ".$_POST['IDmodif'];
								$reponse = $bdd->query($req );
								$checkrep = $reponse->rowCount();
								if ($checkrep != 0) {
									while ($cur_nom = $reponse->fetch()) {
									?>
									<form action="collaborateurs.php" method="post">

										<fieldset id="infos-generales" class="fieldset-align-middle">
											<h2>Informations g&eacute;n&eacute;rale</h2>
											<?php
											echo '<p><span>Nom :</span><input class="form-control form-control-small" type="text" name="nom" value="'.$cur_nom[1].'" /></p>';
											echo '<p><span>Pr&eacute;nom :</span><input class="form-control form-control-small" type="text" name="prenom" value="'.$cur_nom[2].'" /></p>';
											echo '<p><span>Trigramme :</span><input class="form-control form-control-small" type="text" size="5" name="trig" value="'.$cur_nom[3].'" /></p>';
											?>
										</fieldset>

										<fieldset id="parametres" class="fieldset-align-middle">
											<h2>Param&egrave;tres</h2>
											<?php
											echo '<p><span>Effectif de type :</span><select class="form-control form-control-small" name="extstd">';
											echo '<option value=1>Externe</option>';
											if ($cur_nom[20] == 2) { 
												echo '<option value=2 selected>Interne</option>'; 
											} else { 
												echo '<option value=2>Interne</option>'; 
											}
											echo '</select></p>';
											echo '<p><span>Responsable hi&eacute;rarchique :</span><select class="form-control form-control-small" name="resp">';
											if ($_POST['IDmodif'] == 1)
											{ echo '<option value=NULL selected>-</option>';  } else {
											$reponse2 = $bdd->query("SELECT matricule, ID FROM rob_user ORDER BY matricule");
											while ($donnee2 = $reponse2->fetch() ) {
												if ($donnee2['matricule'] == $cur_nom[4]) { 
													echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['matricule'].'</option>'; 
												} else { 
													echo '<option value="'.$donnee2['ID'].'">'.$donnee2['matricule'].'</option>'; 
												}
											}
											$reponse2->closeCursor(); }
											echo '</select></p>';
											echo '<p><span>Affect&eacute; au p&ocirc;le :</span><select class="form-control form-control-small" name="pole">';
											$reponse2 = $bdd->query("SELECT code, ID FROM rob_pole WHERE actif = 1");
											while ($donnee2 = $reponse2->fetch() ) {
												if ($donnee2['code'] == $cur_nom[5]) { 
													echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['code'].'</option>'; 
												} else { 
													echo '<option value="'.$donnee2['ID'].'">'.$donnee2['code'].'</option>'; 
												}
											}
											$reponse2->closeCursor();
											echo '</select></p>';
											echo '<p><span>Acc&egrave;s aux menus :</span><select class="form-control form-control-small" name="user_lev">';
											$req = "SELECT menu, ID FROM rob_level WHERE actif = 1 AND menu <> ''";
											$reponse2 = $bdd->query($req);
											while ($donnee2 = $reponse2->fetch() ) {
												if ($donnee2['menu'] == $cur_nom[6]) { 
													echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['menu'].'</option>'; 
												} else { 
													echo '<option value="'.$donnee2['ID'].'">'.$donnee2['menu'].'</option>'; 
												}
											}
											$reponse2->closeCursor();
											echo '</select></p>';
											echo '<p><span>Droits sur la timesheet :</span><select class="form-control form-control-small" name="tms_lev">';
											$req = "SELECT tms, ID FROM rob_level WHERE actif = 1 AND tms <> ''";
											$reponse2 = $bdd->query($req);
											while ($donnee2 = $reponse2->fetch() ) {
												if ($donnee2['tms'] == $cur_nom[7]) { 
													echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['tms'].'</option>'; 
												} else { 
													echo '<option value="'.$donnee2['ID'].'">'.$donnee2['tms'].'</option>'; 
												}
											}
											$reponse2->closeCursor();
											echo '</select></p>';
											echo '<p><span>Droits sur les frais :</span><select class="form-control form-control-small" name="exp_lev">';
											$req = "SELECT exp, ID FROM rob_level WHERE actif = 1 AND exp <> ''";
											$reponse2 = $bdd->query($req);
											while ($donnee2 = $reponse2->fetch() ) {
												if ($donnee2['exp'] == $cur_nom[8]) { 
													echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['exp'].'</option>'; 
												} else { 
													echo '<option value="'.$donnee2['ID'].'">'.$donnee2['exp'].'</option>'; 
												}
											}
											$reponse2->closeCursor();
											echo '</select></p>';
											echo '<p><span>Droits sur les transactions :</span><select class="form-control form-control-small" name="jrl_lev">';
											$req = "SELECT jrl, ID FROM rob_level WHERE actif = 1 AND jrl <> ''";
											$reponse2 = $bdd->query($req);
											while ($donnee2 = $reponse2->fetch() ) {
												if ($donnee2['jrl'] == $cur_nom[9]) { 
													echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['jrl'].'</option>'; 
												} else { 
													echo '<option value="'.$donnee2['ID'].'">'.$donnee2['jrl'].'</option>'; 
												}
											}
											$reponse2->closeCursor();
											echo '</select></p>';
											echo '<p><span>Niveau d\'autorisation :</span><select class="form-control form-control-small" name="auth_lev">';
											$req = "SELECT grade, ID FROM rob_grade WHERE actif = 1 AND grade <> ''";
											$reponse2 = $bdd->query($req);
											while ($donnee2 = $reponse2->fetch() ) {
												if ($donnee2['grade'] == $cur_nom[9]) { 
													echo '<option value="'.$donnee2['ID'].'" selected>'.$donnee2['grade'].'</option>'; 
												} else { 
													echo '<option value="'.$donnee2['ID'].'">'.$donnee2['grade'].'</option>'; 
												}
											}
											$reponse2->closeCursor();
											echo '</select></p>';
											?>
										</fieldset>

										<fieldset id="coordonnees" class="fieldset-align-middle">
											<h2>Coordonn&eacute;es</h2>
											<?php
											echo '<p><span>T&eacute;l&eacute;phone :</span><input class="form-control form-control-small" type="text" size="20" name="tel" value="'.$cur_nom[10].'" /></p>';
											echo '<p><span>Mobile :</span><input class="form-control form-control-small" type="text" size="20" name="mobile" value="'.$cur_nom[11].'" /></p>';
											echo '<p><span>Email :</span><input class="form-control form-control-small" type="text" size="50" name="mail" value="'.$cur_nom[12].'" /></p>';
											echo '<p><span>Adresse :</span><input class="form-control form-control-small" type="text" size="50" name="adresse" value="'.$cur_nom[16].'" /></p>';
											echo '<p><span>CP :</span><input class="form-control form-control-small" type="text" size="7" name="cp" value="'.$cur_nom[15].'" /></p>';
											echo '<p><span>Ville :</span><input class="form-control form-control-small" type="text" size="20" name="ville" value="'.$cur_nom[14].'" /></p>';
											echo '<p><span>Pays :</span><input class="form-control form-control-small" type="text" size="15" name="pays" value="'.$cur_nom[13].'" /></p>';
											?>
										</fieldset>

										<fieldset id="param-conges" class="fieldset-align-middle">
											<h2>Solde des cong&eacute;s, RTT et r&eacute;cup&eacute;rations</h2>
											<?php
											$req = "SELECT sum(recup) FROM rob_temps 
												WHERE userID=".$_POST['IDmodif']." AND recup <> 0 AND phaseID='1' AND recupValid IS NULL
												GROUP BY userID";
											$check = $bdd->query($req);
											$checkrow=$check->rowCount();
											if ($checkrow != 0) {
												$dchk = $check->fetch();
												echo '<p><span>R&eacute;cup :</span><span>'.number_format($dchk[0],2,",","").'</span><input type="hidden" name="absrecup" value="0" /></p>';
											} else {
												echo '<p><span>R&eacute;cup :</span><span>'.number_format(0,2,",","").'</span><input type="hidden" name="absrecup" value="0" /></p>';
											}
											$check->closeCursor();
											echo '<p><span>Cong&eacute;s pay&eacute;s :</span><input class="form-control form-control-small" type="text" size="5" name="abscp" value="'.$cur_nom[18].'" /></p>';
											echo '<p><span>RTT :</span><input class="form-control form-control-small" type="text" size="15" name="absrtt" value="'.$cur_nom[19].'" /></p>';
											?>
										</fieldset>

											<?php
									}
										echo '<input type="hidden" value="'.$_POST['IDmodif'].'" name="IDmodif" /><input class="btn btn-small btn-primary btn-param" type="submit" Value="Enregistrer les modifications" name="Valider" />';
										?>
									</form>
									<?php
								} else {
									echo 'on n\'a rien';
								}
								$reponse->closeCursor();
							}
							?>
						</section>
					</div>
				</div>
			</div>
		</div>
		<?php
		include("footer.php");
	}
	else
	{
		header("location:index.php");
	}
?>