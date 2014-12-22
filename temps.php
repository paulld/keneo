<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	$comptprblm = 0;
	$comptprblmrec = 0;
	$comptrepl = 0;
	$comptchg = 0;
	$globval = 0;
	$comptprblmmod = 0;
	$recupmsg = 0;
	$recupmsguse = 0;
	$deadreach = 0;
	$probldata = 0;
	
	//Deadline
	$dead = $bdd->query("SELECT deadline FROM rob_verrouille WHERE ID=2");
	$deadlinetab = $dead->fetch();
	$deadline = $deadlinetab[0];
	$dead->closeCursor();

	if (isset($_POST['client']) AND isset($_POST['projet']) AND isset($_POST['datejourdeb']) AND isset($_POST['datejourfin']) AND isset($_POST['activite']))
	{
		if ($_POST['client'] != "none" AND $_POST['projet'] != "none" AND $_POST['activite'] != "none" AND $_POST['datejourdeb'] != 0 AND $_POST['datejourfin'] != 0 AND $_POST['heure'] != 0)
		{
			//Définition du niveau de profondeur client/projet/mission
			$imputID2 = $_POST['projet'];
			if ($_POST['projet'] != 0 and isset($_POST['mission']) and $_POST['mission'] != "none")
			{
				$imputID3 = $_POST['mission'];
			}
			else
			{
				if ($_POST['projet'] == 0)
				{
					$imputID3 = 0;
				}
				else
				{
					$comptchg = 1;
				}
			}

			//Définition des variables
			$matricule = $_SESSION['ID'];
			$collab = $_POST['collaborateur'];
			$info = $_POST['info'];
			$info = str_replace("'","\'",$info);
			$aujourdhui = date("Y-m-d");
			$fmonth = date("F", mktime(0,0,0,substr($_POST['datejourdeb'],3,2),substr($_POST['datejourdeb'],0,2),substr($_POST['datejourdeb'],6,4)));
			$month = date("m", mktime(0,0,0,substr($_POST['datejourdeb'],3,2),substr($_POST['datejourdeb'],0,2),substr($_POST['datejourdeb'],6,4)));
			$year = date("Y", mktime(0,0,0,substr($_POST['datejourdeb'],3,2),substr($_POST['datejourdeb'],0,2),substr($_POST['datejourdeb'],6,4)));
			$val = $_POST['heure'];
			$datejourdeb = date('Y-m-d',mktime(0,0,0,substr($_POST['datejourdeb'],3,2),substr($_POST['datejourdeb'],0,2),substr($_POST['datejourdeb'],6,4)));
			$datejourtmp = date('Y-m-d',mktime(0,0,0,substr($_POST['datejourdeb'],3,2),substr($_POST['datejourdeb'],0,2),substr($_POST['datejourdeb'],6,4)));
			$datejourfin = date('Y-m-d',mktime(0,0,0,substr($_POST['datejourfin'],3,2),substr($_POST['datejourfin'],0,2),substr($_POST['datejourfin'],6,4)));
			if (isset($_POST['ticket'])) { $ticket = 1; } else { $ticket = 0; }
			$surtxt = 1;
			$imputID = $_POST['client'];
			$activite = $_POST['activite'];
			
			//On balaie la plage définie
			while ($datejourtmp <= $datejourfin)
			{
				if ($deadline < $datejourtmp)
				{
					$req = "SELECT dateFerie FROM rob_ferie WHERE dateFerie='$datejourtmp'";
					$checkferie = $bdd->query($req);
					$checkrow=$checkferie->rowCount();
					if ($checkrow != 0) { $flagferie = 1; } else { $flagferie = 0; }
					$checkferie->closeCursor();

					//la saisie sur le week-end/ ferie n'est pas possible lors de la sélection de plage
					if (($datejourdeb == $datejourfin) OR (date("D", strtotime($datejourtmp)) != "Sun" AND date("D", strtotime($datejourtmp)) != "Sat" AND $flagferie != 1))
					{
						if (date("D", strtotime($datejourtmp)) == "Sun" OR date("D", strtotime($datejourtmp)) == "Sat" OR $flagferie == 1) 
						{ $recupval=$_POST['heure']; $recupmsg = $_POST['heure']; $typabs = "r&eacute;cup"; } else { $recupval=0; }
						
						//Si récupération, on vérifie qu'il y a des journée de récupération possible
						if ($imputID == 1 AND $imputID2 == 7)
						{
							$req = "SELECT sum(recup) FROM rob_temps 
								WHERE userID='$collab' AND recup <> 0 AND phaseID='1' AND recupValid IS NULL
								GROUP BY userID";
							$check = $bdd->query($req);
							$checkrow=$check->rowCount();
							//on en trouve
							if ($checkrow != 0)
							{
								$dchk = $check->fetch();
								//pas assez
								if ($dchk[0] - $val < 0)
								{
									$comptprblmrec = $comptprblmrec + 1;
								//assez
								} else {
									//Flag de la plus vielle journee de recup
									$recuptmp = $val;
									$req2 = "SELECT ID, recup FROM rob_temps WHERE userID='$collab' AND recup <> 0 AND phaseID='1' AND recupValid IS NULL 
											ORDER BY datejour";
									$flagrec = $bdd->query($req2);
									while ($testrec = $flagrec->fetch())
									{
										if($recuptmp > 0)
										{
											$IDtmp = $testrec['ID'];
											//jour de recup en ligne avec jour de recup qu'on possède
											if ($recuptmp == $val)
											{
												$recuptmp = 0;
												$newrecup = 0;
												$bdd->query("UPDATE rob_temps SET recup='$newrecup', recupValid='$datejourtmp' WHERE ID='$IDtmp'");

											//sinon
											} else {
												if ($recuptmp > $val)
												{
													$recuptmp = 0;
													$newrecup = $testrec['recup'] - $val;
													$bdd->query("UPDATE rob_temps SET recup='$newrecup' WHERE ID='$IDtmp'");

												} else {
													$recuptmp = $val - $testrec['recup'];
													$newrecup = $testrec['recup'];
													$bdd->query("UPDATE rob_temps SET recup='$newrecup' WHERE ID='$IDtmp'");

												}
											}
											$recupmsguse = $recupmsguse + $val;
										}
									}
									$flagrec->closeCursor();
								}
							}
							//on en trouve pas
							else
							{
								$comptprblmrec = $comptprblmrec + 1;
								$probldata = 1;
							}
							$check->closeCursor();
						}
						
						//Si pas de problème de recup
						if ($comptprblmrec == 0)
						{
							//On verifie l'existence d'enregistrement
							$req = "SELECT sum(valeur) FROM rob_temps 
								WHERE userID='$collab' AND datejour='$datejourtmp' AND phaseID='1'
								GROUP BY userID, datejour, phaseID";
							$check = $bdd->query($req);
							$checkrow=$check->rowCount();
							if ($checkrow != 0)
							{
								$dchk = $check->fetch();
								$globval = $dchk[0] + $val;
								if ($globval > 1)
								{
									//On n'insère pas la donnée
									$comptprblm = $comptprblm + 1;
									$probldata = 1;
								}
								else
								{
									$bdd->query("INSERT INTO rob_temps VALUES ('', '$collab', '$imputID', '$imputID2', '$imputID3', '', '$datejourtmp', '0', '$aujourdhui', '$aujourdhui', '$matricule', '', '$info', '$val', '$surtxt', '$ticket', 0, '$activite', '$recupval', NULL, '$globval')");
									$bdd->query("UPDATE rob_temps SET valtot='$globval' WHERE userID='$collab' AND datejour='$datejourtmp' AND phaseID='1'");
									$bdd->query("UPDATE rob_temps SET ticket='$ticket' WHERE userID='$collab' AND datejour='$datejourtmp' AND phaseID='1'");
								}
							}
							else
							{
								$bdd->query("INSERT INTO rob_temps VALUES ('', '$collab', '$imputID', '$imputID2', '$imputID3', '', '$datejourtmp', '0', '$aujourdhui', '$aujourdhui', '$matricule', '', '$info', '$val', '$surtxt', '$ticket', 0, '$activite', '$recupval', NULL, '$val')");
							}
							$check->closeCursor();
							
							//Si récup
							if ($imputID2 == 7) { $typabs = "r&eacute;cup"; }
							
							//Si saisie de rtt ou cp
							if (date("D", strtotime($datejourtmp)) != "Sun" and date("D", strtotime($datejourtmp)) != "Sat" and ($imputID2 == 2 or $imputID2 == 1) and $comptprblm == 0)
							{
								if ($imputID2 == 1) { $abs = "rtt"; $typabs = "RTT"; } else { $abs = "cp"; $typabs = "cong&eacute;s pay&eacute;s"; }
								$abscurr = $bdd->query("SELECT $abs FROM rob_user_abs WHERE ID='$collab'");
								$absccurr = $abscurr->fetch();
								$absup = $absccurr[0] - $val;
								$abscurr->closeCursor();
								$bdd->query("UPDATE rob_user_abs SET $abs='$absup' WHERE ID='$collab'");
								$recupmsg = - $val;
							}
						}
					}
				}
				else
				{
					$deadreach = $deadreach + 1;
					$probldata = 1;
				}
				$datejourtmp = date ("Y-m-d", strtotime("$datejourtmp, +1 day"));
			}
		}
		else
		{
			//client ou activite ou projet ou heure non saisi
			$comptchg = 1;
			$probldata = 1;
		}
	}
	else
	{
		if (isset($_POST['Mod']))
		{
			$mod_id = $_POST['modid'];
			$mod_valeur = $_POST['modval'];
			$aujourdhui = date("Y-m-d");
			$collab = $_POST['affcoll'];
			$datejourtmp = $_POST['moddate'];
			$oldval = $_POST['oldval'];
			$oldtotval = $_POST['oldtotval'];
			$imputID2 = $_POST['imputID2'];
			if (isset($_POST['modticket'])) { $ticket = 1; } else { $ticket = 0; }
			$globval = $oldtotval + $mod_valeur - $oldval;
			if ($globval > 1)
			{
				//On n'insère pas la donnée
				$comptprblmmod = 1;
			}
			else
			{
				if ($deadline < $datejourtmp)
				{
					$bdd->query("UPDATE rob_temps SET valeur='$mod_valeur', datevalid='$datejourtmp', datedemande='$datejourtmp' WHERE ID='$mod_id'");
					$bdd->query("UPDATE rob_temps SET valtot='$globval' WHERE userID='$collab' AND datejour='$datejourtmp' AND phaseID='1'");
					$bdd->query("UPDATE rob_temps SET ticket='$ticket' WHERE userID='$collab' AND datejour='$datejourtmp' AND phaseID='1'");
							
					//Si récup
					if ($imputID2 == 7) { $typabs = "r&eacute;cup"; }
					
					//Si modif de rtt ou cp
					if ($imputID2 == 2 or $imputID2 == 1)
					{
						if ($imputID2 == 1) { $abs = "rtt"; $typabs = "RTT"; } else { $abs = "cp"; $typabs = "cong&eacute;s pay&eacute;s"; }
						$abscurr = $bdd->query("SELECT $abs FROM rob_user_abs WHERE ID='$collab'");
						$absccurr = $abscurr->fetch();
						$absup = $absccurr[0] - $mod_valeur + $oldval;
						$abscurr->closeCursor();
						$bdd->query("UPDATE rob_user_abs SET $abs='$absup' WHERE ID='$collab'");
						$recupmsg = - $mod_valeur + $oldval;
					}
				}
				else
				{
					$deadreach = $deadreach + 1;
				}
			}
		}
		else
		{
			if (isset($_POST['Reprise']) OR isset($_POST['Modif']))
			{
				$rep_id = $_POST['modid'];
				$req = "SELECT T3.ID, T3.description, T4.ID, T4.description, T5.ID, T5.description, T1.info, T1.valeur, T1.ticket, T6.ID, T6.code,T1.datejour FROM rob_temps T1 
					INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
					INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
					INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
					INNER JOIN rob_activite T6 ON T6.ID = T1.activID 
					WHERE T1.userID='".$_SESSION['ID']."' AND T1.ID = '$rep_id'";
				$repreq = $bdd->query($req);
				$checkrow=$repreq->rowCount();
				if ($checkrow != 0)
				{
					$reprise = $_POST['modid'];
					$repdon = $repreq->fetch();
					$rep_idl1 = $repdon[0];
					$rep_dsl1 = $repdon[1];
					$rep_idl2 = $repdon[2];
					$rep_dsl2 = $repdon[3];
					$rep_idl3 = $repdon[4];
					$rep_dsl3 = $repdon[5];
					$rep_info = $repdon[6];
					$rep_val = $repdon[7];
					$rep_tick = $repdon[8];
					$rep_idac = $repdon[9];
					$rep_dsac = $repdon[10];
					$rep_dajr = date("d/m/Y",strtotime($repdon[11]));
				}
				$repreq->closeCursor();
			}
			if (isset($_POST['Suppr']) OR isset($_POST['Modif']))
			{
				$aujourdhui = date("Y-m-d");
				$collab = $_POST['affcoll'];
				$idenr = $_POST['modid'];
				$oldval = $_POST['oldval'];
				$imputID2 = $_POST['imputID2'];
				$datejourtmp = $_POST['moddate'];
				if ($deadline < $datejourtmp)
				{
					$bdd->query("DELETE FROM rob_temps WHERE ID='$idenr' LIMIT 1");
					$req = "SELECT sum(valeur) FROM rob_temps 
						WHERE userID='$collab' AND datejour='$datejourtmp' AND phaseID='1'
						GROUP BY userID, datejour, phaseID";
					$check = $bdd->query($req);
					$checkrow=$check->rowCount();
					if ($checkrow != 0)
					{
						$dchk = $check->fetch();
						$globval = $dchk[0];
						$bdd->query("UPDATE rob_temps SET valtot='$globval' WHERE userID='$collab' AND datejour='$datejourtmp' AND phaseID='1'");
					}
					$check->closeCursor();
							
					//Si suppr de recup
					if ($imputID2 == 7)
					{
						$typabs = "r&eacute;cup";
						//Flag de la plus recente journee de recup avec une date recupValid
						$req2 = "SELECT ID FROM rob_temps WHERE userID='$collab' AND recup=0 AND phaseID='1' AND recupValid IS NOT NULL ORDER BY datejour DESC";
						$flagrec = $bdd->query($req2);
						while ($testrec = $flagrec->fetch())
						{
							$IDtmp = $testrec['ID'];
							$bdd->query("UPDATE rob_temps SET recup='$oldval', recupValid = NULL WHERE ID='$IDtmp'");
						}
						$flagrec->closeCursor();
					}

					//Si suppr de rtt ou cp
					if ($imputID2 == 2 or $imputID2 == 1)
					{
						if ($imputID2 == 1) { $abs = "rtt"; $typabs = "RTT"; } else { $abs = "cp"; $typabs = "cong&eacute;s pay&eacute;s"; }
						$abscurr = $bdd->query("SELECT $abs FROM rob_user_abs WHERE ID='$collab'");
						$absccurr = $abscurr->fetch();
						$absup = $absccurr[0] + $oldval;
						$abscurr->closeCursor();
						$bdd->query("UPDATE rob_user_abs SET $abs='$absup' WHERE ID='$collab'");
						$recupmsg = $oldval;
					}
				}
				else
				{
					$deadreach = $deadreach + 1;
				}
			}
		}
	}
	?>
		
    <!-- =================== SAISIE ================= -->
	<!-- Background Image Specific to each page -->
	<div class="background-temps background-image"></div>
	<div class="overlay"></div>

	<!-- <div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="#"><span>Feuille de temps</span></a></li></ul>
	</div> -->

	<section class="container section-container section-toggle" id="saisie-temps">
		<div class="section-title toggle-botton-margin" id="toggle-title">
			<h1>
				<i class="fa fa-chevron-down"></i>
				Saisir mes temps
				<i class="fa fa-chevron-down"></i>
			</h1>
		</div>
		<form action="temps.php" method="post" id="toggle-content" style="display: none;">
			<div id="tablesaisie">
				<?php echo '<input type="hidden" value='.$_SESSION['ID'].' name="collaborateur" />'; ?>
				<div id="f-timeframe">
					<?php
					echo '<input type="hidden" name="datedemande" value="' . date("Y") . '-' . date("m") . '-' . date("d") . '" />';
					echo 'Enregistrer du <input class="form-control form-control-small form-control-centered" size="8" type="text" id="datejourdeb" name="datejourdeb" value="';
						if (isset($_POST['datejourdeb']))
						{
							echo $_POST['datejourdeb'];
						}
						else
						{
							if (isset($reprise))
							{
								echo $rep_dajr;
							}
						}
						echo '" /> au <input class="form-control form-control-small form-control-centered" size="8" type="text" id="datejourfin" name="datejourfin" value="';
						if (isset($_POST['datejourfin']))
						{
							echo $_POST['datejourfin'];
						}
						else
						{
							if (isset($reprise))
							{
								echo $rep_dajr;
							}
						}
						echo '" /> (inclus), ';
					?> 
					<select class="form-control form-control-small form-control-centered" name="heure" />
						<option value="0"></option>
						<?php
						if (isset($reprise))
						{
							if ($rep_val == 0.25) { echo '<option value="0.25" selected>1/4</option>'; } else { echo '<option value="0.25">1/4</option>'; }
							if ($rep_val == 0.50) { echo '<option value="0.50" selected>1/2</option>'; } else { echo '<option value="0.50">1/2</option>'; }
							if ($rep_val == 0.75) { echo '<option value="0.75" selected>3/4</option>'; } else { echo '<option value="0.75">3/4</option>'; }
							if ($rep_val == 1) { echo '<option value="1.00" selected>1</option>'; } else { echo '<option value="1.00">1</option>'; }
						} else {
							if (isset($_POST['heure']) AND $probldata == 1)
							{
								if ($_POST['heure'] == 0.25) { echo '<option value="0.25" selected>1/4</option>'; } else { echo '<option value="0.25">1/4</option>'; }
								if ($_POST['heure'] == 0.50) { echo '<option value="0.50" selected>1/2</option>'; } else { echo '<option value="0.50">1/2</option>'; }
								if ($_POST['heure'] == 0.75) { echo '<option value="0.75" selected>3/4</option>'; } else { echo '<option value="0.75">3/4</option>'; }
								if ($_POST['heure'] == 1) { echo '<option value="1.00" selected>1</option>'; } else { echo '<option value="1.00">1</option>'; }
							} else {
							?>
							<option value="0.25">1/4</option>
							<option value="0.50">1/2</option>
							<option value="0.75">3/4</option>
							<option value="1.00">1</option>
						<?php } } ?>
					</select> d'occupation moyenne journali&egrave;re.
				</div>
				<div id="f-descriptif">
					<?php
						if (isset($reprise)) { if ($rep_tick == 1) {$cbbox = " checked"; } else { $cbbox = ""; }
						} else {
						if (isset($_POST['ticket']) AND $probldata == 1) { $cbbox = " checked"; } else { $cbbox = ""; }
					} ?>
					<input class="checkbox" type="checkbox"<?php echo $cbbox; ?> name="ticket" title="Repas pris, par journ&eacute;e de la plage d&eacute;finie ci-dessus, donnant droit &agrave; un ticket restaurant" /> Ticket restaurant (T.R.)
				</div>
				<div id="f-fraislb">
					<div id="f-client">
						<select class="form-control form-control-small" name="client" id="client" onchange="showProjet(this.value)">
							<option value="none">Client</option>
							<?php
							$reqimput = $bdd->query("SELECT * FROM rob_imputl1 WHERE actif=1 ORDER BY description");
							while ($optimput = $reqimput->fetch())
							{
								if (isset($reprise)) { if ($rep_idl1 == $optimput['ID']) { $optsel = " selected"; } else { $optsel = ""; }
								} else {
								if (isset($_POST['client']) AND $probldata == 1) { if ($_POST['client'] == $optimput['ID']) {$optsel = " selected"; } else { $optsel = ""; }
								} else { $optsel = "";} }
								echo '<option value='.$optimput['ID'].$optsel.'>'.$optimput['description'].'</option>';
							}
							$reqimput->closeCursor();
							?>
						</select><span id="txtHint">
						<?php if (isset($reprise)) { $p=$rep_idl1; $m=$rep_idl2; include('getprojet.php'); } else 
						{ if (isset($_POST['projet']) AND $probldata == 1)
							{ $p=$_POST['client']; $m=$_POST['projet']; include('getprojet.php'); }
						} ?></span>
						<input type="hidden" id="ma_page" value="0" />
					</div>
					<div id="txtHint2">
						<?php if (isset($reprise)) { $p=$rep_idl1; $m=$rep_idl2; $c=$rep_idl3; include('getmission.php'); } else 
						{ if (isset($_POST['mission']) AND isset($_POST['projet']) AND $probldata == 1)
							{ $p=$_POST['client']; $m=$_POST['projet'];  $c=$_POST['mission']; include('getmission.php'); }
						} ?></div>
				</div>
				<div id="ActiviteHint">
					<select class="form-control form-control-small" name="activite" >
						<option value="none">S&eacute;lectionnez une activit&eacute;</option>
						<?php
						$reqimput = $bdd->query("SELECT * FROM rob_activite WHERE actif=1 ORDER BY code");
						while ($optimput = $reqimput->fetch())
						{
							if (isset($reprise)) { if ($rep_idac == $optimput['ID']) { $optsel = " selected"; } else { $optsel = ""; }
							} else { 
							if (isset($_POST['activite']) AND $probldata == 1) { if ($_POST['activite'] == $optimput['ID']) { $optsel = " selected"; } else { $optsel = ""; }
							} else { $optsel = ""; } }
							echo '<option value='.$optimput['ID'].$optsel.'>'.$optimput['Description'].'</option>';
						}
						$reqimput->closeCursor();
						?>
					</select>
					<input class="form-control form-control-small" type="text" size="70" name="info" placeholder="Description" 
						<?php
							if (isset($_POST['info'])) {
								echo ' value="'.$_POST['info'].'" ';
							} else {
								if (isset($_POST['modinfo'])) {
									echo ' value="'.$_POST['modinfo'].'" ';
								} else {
									if (isset($reprise)) {
										echo ' value="'.$rep_info.'" ';
									} else {
										echo 'placeholder="information libre" ';
									}
								}
							}
						?>
					/>
				</div>
				<div id="f-valider">
					<?php
					echo '<input class="btn btn-small btn-primary" id="buttonval" type="submit" Value="Enregistrer" name="Valider" />';
					?> 
				</div>
			</div>
		</form>
		<?php
			if ($comptprblmmod != 0)
			{
				echo '<div id="message" class="form-error-message">La modification des temps n\'a pas pu &ecirc;tre aport&eacute;e (le total exc&eacute;derait 1 journ&eacute;e pour ce jour)</div>';
			}
			if ($comptprblm != 0)
			{
				echo '<div id="message" class="form-error-message">'.$comptprblm.' enregistrement(s) n\'a(ont) pu &ecirc;tre ajout&eacute;(s). (temps restant par journ&eacute;e insuffisant ou week-end)</div>';
			}
			if ($comptprblmrec != 0)
			{
				echo '<div id="message" class="form-error-message">'.$comptprblmrec.' enregistrement(s) n\'a(ont) pu &ecirc;tre ajout&eacute;(s). Vous ne disposez pas d\'assez de journ&eacute;es de r&eacute;cup&eacute;ration</div>';
			}
			if ($comptchg != 0)
			{
				echo '<div id="message" class="form-error-message">Certain champs n\'ont pas &eacute;t&eacute; remplis. Votre saisie n\'a pas &eacute;t&eacute; enregistr&eacute;e</div>';
			}
			if ($recupmsg != 0)
			{
				echo '<div id="message" class="form-error-message">'.$recupmsg.' jour(s) de '.$typabs.' impacte(nt) votre total de suivi des jours sp&eacute;ciaux <a href="passwd.php" >[voir]</a></div>';
			}
			if ($recupmsguse != 0)
			{
				echo '<div id="message" class="form-error-message">'.$recupmsguse.' jour(s) de r&eacute;cup&eacute;ration ont &eacute;t&eacute; utilis&eacute;(s) <a href="passwd.php" >[voir]</a></div>';
			}
			if ($deadreach != 0)
			{
				echo '<div id="message" class="form-error-message">'.$deadreach.' enregistrement(s) n\'a(ont) pu &ecirc;tre ajout&eacute;(s). (Cette p&eacute;riode de saisie est ferm&eacute;e)</div>';
			}
		?> 
	</section>

    <!-- =================== OPTIONS ================= -->
	<?php
	if (isset($_POST['affmonth']))
	{
		$month = $_POST['affmonth'];
		$fmonth = date("F",strtotime("2000-".$month."-10"));
		$year = $_POST['affyear'];
		$matricule = $_POST['affcoll'];
	}
	else
	{
		if (isset($fmonth) AND isset($month) AND isset($year))
		{
			$matricule = $_SESSION['ID'];
		}
		else
		{
			$fmonth = date("F");
			$month = date("m");
			$year = date("Y");
			$matricule = $_SESSION['ID'];
		}
	}
	?>
    <!-- =================== RESTITUTION: PARAM ================= -->
<section class="container section-container" id="historique-temps">
	<div class="section-title">
		<h1>Historique de mes temps</h1>
	</div>
	<div class="temps-filter">
		<form action="temps.php" method="post">
			<?php
			//MONTH
			echo '<select class="form-control form-control-small" id="w_input_titrepartie" name="affmonth" class="form-control form-control-small">';
			$i=1;
			while ($i < 13)
			{
				{
					if ($i < 10) { $tmo = "0".$i; } else { $tmo = $i; }
					if ($i == $month)
					{
						echo '<option value='.$tmo.' selected>'.date("F",strtotime("2000-".$tmo."-10")).'</option>';
					}
					else
					{
						echo '<option value='.$tmo.'>'.date("F",strtotime("2000-".$tmo."-10")).'</option>';
					}
				}
				$i = $i + 1;
			}
			echo '</select>';
			
			//YEAR
			echo '<select class="form-control form-control-small" id="w_input_titrepartie" name="affyear" class="form-control form-control-small">';
			$reponsey = $bdd->query("SELECT * FROM rob_period ORDER BY year");
			while ($option = $reponsey->fetch())
			{
				if ($option['year'] == $year)
				{
					echo '<option value='.$option['year'].' selected>'.$option['year'].'</option>';
				}
				else
				{
					echo '<option value='.$option['year'].'>'.$option['year'].'</option>';
				}
			}
			$reponsey->closeCursor();
			echo '</select>';
			
			//MATRICULE
			echo '<input type="hidden" name="affcoll" value='.$_SESSION['ID'].' />';
			echo '<input type="submit" id="buttonval" class="btn btn-small btn-primary" value="Mettre a jour">';
			?>
			<a href="temps-pdf.php?month=<?php echo $month; ?>&amp;year=<?php echo $year; ?>&amp;matricule=<?php echo $matricule; ?>" target="_blank"><i class="fa fa-file-pdf-o" title="<?php echo 'Extraire '.$month.'.'.$year.' sous PDF'; ?>"></i></a>
		</form>
	</div>
		
	<!-- =================== RESTITUTION: TABLEAU ================= -->
		<table id="tablerestit" class="table table-striped">
			<thead>
				<tr>
					<th id="t-containertit" align="center" colspan="2">Date</th>
					<th id="t-containertit">Activit&eacute;</th>
					<th id="t-containertit">Client</th>
					<th id="t-containertit">Projet</th>
					<th id="t-containertit">Mission</th>
					<th id="t-containertit">Description</th>
					<th id="t-containertit" width="30px">T.R.</th>
					<th id="t-containertit" width="40px">Jours</th>
					<th id="t-containertit" align="center" width="85px">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$nbjm = 0;
				$startdate = $year.'-'.$month.'-01';
				if ($month < 12) { $tmpmonth = $month + 1; $tmpyear = $year; } else {$tmpmonth = 1; $tmpyear = $year + 1; }
				if ($tmpmonth < 10) { $tmpmonth = "0".$tmpmonth; }
				$enddate = $tmpyear.'-'.$tmpmonth.'-01';
				if (isset($_POST['collaborateur']))
				{
					$pseudo=$_POST['collaborateur'];
				}
				else
				{
					if (isset($_POST['affcoll']))
					{
						$pseudo=$_POST['affcoll'];
					}
					else
					{
						$pseudo=$_SESSION['ID'];
					}
				}
				$req = "SELECT T1.ID, T2.matricule, T1.datejour, T3.description, T4.description, T5.description, T1.info, T1.valeur, T1.valtot, T4.ID, T1.ticket, T6.code, T1.recup, T1.recupValid, 
					T1.validation validation, T7.matricule userValidID FROM rob_temps T1 
					INNER JOIN rob_user T2 ON T2.ID = T1.userID
					INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
					INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
					INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
					INNER JOIN rob_activite T6 ON T6.ID = T1.activID
					INNER JOIN rob_user T7 ON T7.ID = T1.userValidID 
					WHERE T1.userID='".$_SESSION['ID']."' AND datejour >= '$startdate' AND datejour < '$enddate'
					ORDER BY T1.datejour, T3.description, T4.description, T5.description";
				$reponsea = $bdd->query($req);
				$checkrep=$reponsea->rowCount();
				$i=1;
				$j=2;
				if ($checkrep != 0)
				{
					while ($donneea = $reponsea->fetch())
					{
						if ($donneea[8] == 1) { $j = "RoB_Green.png"; } else { $j = "RoB_Orange.png"; }
						if ($donneea[2] <= $deadline OR $donneea['validation'] != 0) { $l = " disabled"; $k="v"; } else 
						{
							$l = "";
							if ($donneea[12] != 0 or $donneea[13] != NULL) {$k="s";} else { $k="";}
						}
						//date du jour
						echo '<tr>';
						echo '<td id="t-container'.$i.$k.'" width="50px"><img src="images/'.$j.'" />&nbsp;'.date("D", strtotime($donneea[2])).'</td>';
						echo '<td id="t-container'.$i.$k.'" width="70px">'.date("d/m/Y", strtotime($donneea[2])).'</td>';
						//collaborateur
						echo '<td id="t-container'.$i.$k.'">'.$donneea[11].'</td>';
						//clients
						echo '<td id="t-container'.$i.$k.'">'.$donneea[3].'</td>';
						//projet
						echo '<td id="t-container'.$i.$k.'">'.$donneea[4].'</td>';
						//mission
						echo '<td id="t-container'.$i.$k.'">'.$donneea[5].'</td>';
						//info
						echo '<td id="t-container'.$i.$k.'">'.$donneea[6].'</td>';
						//ticket
						$tmptick='';
						if ($donneea[10] == 1) { 
							$tmptick=' checked'; 
						}
						echo '<td id="t-container'.$i.$k.'">';
							echo '<input type="checkbox" name="modticket"'.$tmptick./*$l.*/' disabled />';
						echo '</td>';
						//valeur
						echo '<td id="t-container'.$i.$k.'">'.$donneea[7].'</td>';
						// echo '</td>';
						//status
						echo '<td id="t-container'.$i.$k.'">';
						echo '<form action="temps.php" method="post" class="duplicate-edit-remove">';
							echo '<input type="hidden" value="'.$pseudo.'" name="affcoll" />';
							echo '<input type="hidden" value="'.$year.'" name="affyear" />';
							echo '<input type="hidden" value="'.$month.'" name="affmonth" />';
							echo '<input type="hidden" value="'.$donneea[0].'" name="modid" />';
							echo '<input type="hidden" value="'.$donneea[2].'" name="moddate" />';
							echo '<input type="hidden" value="'.$donneea[7].'" name="oldval" />';
							echo '<input type="hidden" value="'.$donneea[8].'" name="oldtotval" />';
							echo '<input type="hidden" value="'.$donneea[9].'" name="imputID2" />';
							$nbjm = $nbjm + $donneea[7];
							if ($donneea[2] <= $deadline OR $donneea['validation'] != 0) {
									echo '<button class="btn btn-small btn-default" id="btRep" type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise"><i class="fa fa-trash-o"></i></button>';
								// echo '</td></form></tr>';
							} else {
								// echo '<td id="t-container'.$i.$k.'">';
									//echo '<button class="btn btn-small btn-default" id="btValid" type="submit" title="Valider les modifications" Value="V" name="Mod" onclick="return(confirm(\'Etes-vous sur de vouloir modifier les temps et/ou le ticket restaurant de cette ligne?\'))"></button>';
									echo '<button class="btn btn-small btn-default" id="btMod" type="submit" Value="M" title="Modifier les informations de cette ligne" name="Modif" onclick="return(confirm(\'Les donn&eacute;es seront reprises dans le formulaire et cette ligne sera supprim&eacute;e. &Ecirc;tes vous s&ucirc;r?\'))"><i class="fa fa-pencil-square-o"></i></button>';
									echo '<button class="btn btn-small btn-default" id="btRep" type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise"><i class="fa fa-files-o"></i></button>';
									echo '<button class="btn btn-small btn-default" id="btSuppr" type="submit" Value="S" title="Supprimer la ligne" name="Suppr" onclick="return(confirm(\'Etes-vous sur de vouloir supprimer cette entree?\'))"><i class="fa fa-trash-o"></i></button>';
							}
						echo '</td></form></tr>';
						if ($i == 1) { $i = 2; } else { $i = 1; }
					}
					echo '<tr><td id="t-containertit" align="right" colspan="9"><strong>Total '.$month.'.'.$year.' : '.$nbjm.' jours</strong></td><td id="t-containertit">&nbsp;</td></tr>';
				}
				$reponsea->closeCursor();
				
				//<!-- =================== RESTITUTION: TABLEAU SOUS TOTAL ================= -->
				//Détails par client
				$req = "SELECT T3.description, sum(T1.valeur) FROM rob_temps T1 
					INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
					WHERE T1.userID='".$_SESSION['ID']."' AND T1.datejour >= '$startdate' AND T1.datejour < '$enddate'
					GROUP BY T3.description
					ORDER BY T3.description";
				$reponse = $bdd->query($req);
				$checkrep = $reponse->rowCount();
				if ($checkrep != 0)
				{
					while ($donnee = $reponse->fetch())
					{
						echo '<tr><td align="right" colspan="10"><i>';
								echo 'dont '.$donnee[0].' : '.$donnee[1].' jours';
						echo '</td><td colspan="2">&nbsp;</td></tr>';
					}
				}
				$reponse->closeCursor();
				
				//Droits aux ticket resto
				$req = "SELECT DISTINCT datejour FROM rob_temps
					WHERE userID='".$_SESSION['ID']."' AND datejour >= '$startdate' AND datejour < '$enddate' AND ticket = '1'";
				$reponse = $bdd->query($req);
				$checkrep = $reponse->rowCount();
				echo '<tr><td colspan="12"><i>Nombre de ticket restaurant &agrave; r&eacute;cup&eacute;rer : <strong>'.$checkrep.'</strong></i></td></tr>';
				$reponse->closeCursor();
				?>
			</tbody>
		</table>

</section>

	
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>