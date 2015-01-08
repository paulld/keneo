<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	$comptchg = 0;
	$probldata = 0;
	if (isset($_POST['Valider']))
	{
		if (isset($_POST['client']) AND isset($_POST['dateTransac']) AND isset($_POST['competition']) AND isset($_POST['nature1']) OR isset($_POST['devisNum']))
		{
			if ($_POST['client'] != "none" AND $_POST['projet'] != "none" AND $_POST['nature1'] != "none" OR isset($_POST['devisNum']))
			{
				
				//Check sur projet et client
				if ($_POST['devisNum'] == "none")
				{
					$devisNum = 'D'.$_POST['client'].'-'.$_POST['projet'].'-'.date('ymd',mktime(0,0,0,substr($_POST['dateTransac'],3,2),substr($_POST['dateTransac'],0,2),substr($_POST['dateTransac'],6,4)));;
					if ($comptchg == 0)
					{
						$client = $_POST['client'];
						$projet = $_POST['projet'];
						if (isset($_POST['mission'])) { $mission = $_POST['mission']; } else { $mission = 0; }
						if (isset($_POST['categorie'])) { $categorie = $_POST['categorie']; } else { $categorie = 0; }
						$competition = $_POST['competition'];
						if (isset($_POST['typecomp'])) { $typecomp = $_POST['typecomp']; } else { $typecomp = 0; }
						if (isset($_POST['evnmt'])) { $evnmt = $_POST['evnmt']; } else { $evnmt = 0; }
						$dateTransac = date('Y-m-d',mktime(0,0,0,substr($_POST['dateTransac'],3,2),substr($_POST['dateTransac'],0,2),substr($_POST['dateTransac'],6,4)));
					}
				} else {
					$devisNum = $_POST['devisNum'];
					$req = "SELECT imputID1, imputID2, imputID3, imputID4, compID1, compID2, compID3, dateTransac FROM rob_devis
						WHERE userID='".$_SESSION['ID']."' AND devisNum = '$devisNum' LIMIT 1";
					$repreq = $bdd->query($req);
					$checkrow=$repreq->rowCount();
					if ($checkrow != 0)
					{
						$repdon = $repreq->fetch();
						$client = $repdon[0];
						$projet = $repdon[1];
						$mission = $repdon[2];
						$categorie = $repdon[3];
						$competition = $repdon[4];
						$typecomp = $repdon[5];
						$evnmt = $repdon[6];
						$dateTransac = $repdon[7];
					}
					$repreq->closeCursor();
					echo $_POST['devisNum'];
				}
				//Version
				if (isset($_POST['devisVersion']))
				{
					if ($_POST['devisVersion'] == "none")
					{
						$devisVersion = 'V'.date('ymd');
					} else {
						$devisVersion = $_POST['devisVersion'];
					}
				} else {
					$devisVersion = 'V'.date('ymd');
				}
				
				//insertion dans la table
				if ($comptchg == 0)
				{
					$nature1 = $_POST['nature1'];
					$userID = $_SESSION['ID'];
					$info = $_POST['info'];
					$info = str_replace("'","\'",$info);
					$aujourdhui = date("Y-m-d");
					$frsCtUnit = $_POST['frsCtUnit'];
					$frsQty = $_POST['frsQty'];
					$frsCtTotHT = $_POST['frsCtTotHT'];
					$bdd->query("INSERT INTO rob_devis VALUES ('', '$userID', '$dateTransac', '$client', '$projet', '$mission', '$categorie', '$nature1', 0, '$competition', '$typecomp', '$evnmt', '$info', '$devisNum', '$devisVersion', '$userID', '$aujourdhui', 0, 1, '$aujourdhui', '$frsCtUnit', '$frsQty', '$frsCtTotHT')");
				}
			}
			else
			{
				//client non saisi
				$comptchg = 1;
				$probldata = 1;
			}
		}
	}
	else
	{
		if (isset($_POST['Reprise']) OR isset($_POST['Modif']))
		{
			$rep_id = $_POST['modid'];
			$req = "SELECT imputID1, imputID2, imputID3, imputID4, compID1, compID2, compID3, descriptif, nature1ID, dateTransac, unitaire, quantite, total, devisNum, devisVersion FROM rob_devis T1
				WHERE userID='".$_SESSION['ID']."' AND T1.ID = '$rep_id'";
			$repreq = $bdd->query($req);
			$checkrow=$repreq->rowCount();
			if ($checkrow != 0)
			{
				$reprise = $_POST['modid'];
				$repdon = $repreq->fetch();
				$rep_idl1 = $repdon[0];
				$rep_idl2 = $repdon[1];
				$rep_idl3 = $repdon[2];
				$rep_idl4 = $repdon[3];
				$rep_idc1 = $repdon[4];
				$rep_idc2 = $repdon[5];
				$rep_idc3 = $repdon[6];
				$rep_info = $repdon[7];
				$rep_nat1 = $repdon[8];
				$rep_dajr = date("d/m/Y",strtotime($repdon[9]));
				$rep_unit = $repdon[10];
				$rep_nbre = $repdon[11];
				$rep_tota = $repdon[12];
				$rep_devisNum = $repdon[13];
				$rep_devisVers = $repdon[14];
			}
			$repreq->closeCursor();
		}
		if (isset($_POST['Suppr']) OR isset($_POST['Modif']))
		{
			$idenr = $_POST['modid'];
			//if ($deadline < $datejourtmp)
			//{
				$bdd->query("DELETE FROM rob_devis WHERE ID='$idenr' LIMIT 1");
			//}
			//else
			//{
			//	$deadreach = $deadreach + 1;
			//}
		}
	}
	?>
		
    <!-- =================== SAISIE ================= -->
	<!-- Background Image Specific to each page -->
		<div class="background-transactions background-image"></div>
		<div class="overlay"></div>

	<div class="container nav-tabs-outer" id="mainMenuDB">
		<ul class="nav nav-tabs nav-justified">
			<li><a role="presentation" href="menu_transac.php"><span>Vue d'Ensemble</span></a></li>
			<li class="active"><a role="presentation" href="#"><span>Devis</span></a></li>
			<li><a role="presentation" href="#"><span>Bons de commandes</span></a></li>
			<li><a role="presentation" href="#"><span>Journal</span></a></li>
		</ul>
	</div>

	<section class="container section-container section-toggle" id="saisie-frais">
		<div class="section-title" id="toggle-title">
			<h1>
				<i class="fa fa-chevron-down"></i>
				Cr&eacute;er une ligne de devis
				<i class="fa fa-chevron-down"></i>
			</h1>
		</div>
		<form action="devis.php" method="post" id="toggle-content" style="<?php if (isset($_POST['Reprise']) || isset($_POST['Modif']) || isset($_POST['Valider'])) { } else { echo 'display: none;'; } ?>">
			<div class="form-inner">
				<div>
					<select class="form-control form-control-small" name="devisNum" id="devisNum" onchange="showDevisVersion(this.value)" >
						<option value="none">Cr&eacute;er nouveau Num&eacute;ro de devis</option>
						<?php
						$reqimput = $bdd->query("SELECT DISTINCT devisNum FROM rob_devis WHERE actif = 1 ORDER BY devisNum DESC");
						while ($optimput = $reqimput->fetch())
						{
							if (isset($reprise)) { if ($rep_devisNum == $optimput['devisNum']) { $optsel = " selected"; } else { $optsel = ""; }
							} else {
							if (isset($_POST['devisNum']) AND $probldata == 1) { if ($_POST['devisNum'] == $optimput['devisNum']) {$optsel = " selected"; } else { $optsel = ""; }
							} else { $optsel = "";} }
							echo '<option value="'.$optimput['devisNum'].'"'.$optsel.'>'.$optimput['devisNum'].'</option>';
						}
						$reqimput->closeCursor();
						?>
					</select>
					<span id="txtHint7">
						<?php
						if (isset($reprise)) {
							$d=$rep_devisNum; $v=$rep_devisVers; include('getDevisVersion.php');
						} else {
							if (isset($_POST['devisVersion']) AND $probldata == 1) {
								$d=$_POST['devisNum']; $v=$_POST['devisVersion']; include('getDevisVersion.php');
							} else {
							echo '<input type="hidden" name="devisVersion" value="none" />';
							}
						}
						?>
					</span>
					<input type="hidden" id="ma_page" value="0" />
					<span id="f-rf3" <?php if (isset($_POST['Reprise'])) { echo 'style="display: none;"'; } ?>>
					<?php
					echo ' <input class="form-control form-control-small" size="12" type="text" name="dateTransac" id="dateTransac" value="';
						if (isset($_POST['dateTransac']))
						{
							echo $_POST['dateTransac'];
						}
						else
						{
							if (isset($reprise))
							{
								echo $rep_dajr;
							}
							else
							{
								echo date("d/m/Y");
							}
						}
						echo '" title="date d\'&eacute;tablissement du devis" />';
					?> 
					</span>
				</div>
				<div class="form-divider" id="f-rf1" <?php if (isset($_POST['Reprise'])) { echo 'style="display: none;"'; } ?>>
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
					</select>
					<span id="txtHint">
						<?php
							if (isset($reprise)) {
								$p=$rep_idl1; $m=$rep_idl2; include('getprojet.php');
							} else {
								if (isset($_POST['projet']) AND $probldata == 1) {
									$p=$_POST['client']; $m=$_POST['projet']; include('getprojet.php');
								}
							}
						?>
					</span>
					<span id="txtHint2">
						<?php 
						if (isset($reprise)) {
							$k=$rep_idl1; $m=$rep_idl2; $c=$rep_idl3; include('getmission.php');
							echo '<span id="txtHint3">';
							$c=$rep_idl1; $p=$rep_idl2; $m=$rep_idl3; $k=$rep_idl4; include('getcategorie.php');
							echo '</span>'; } 
							else {
								if (isset($_POST['mission']) AND isset($_POST['projet']) AND $probldata == 1) {
									$p=$_POST['client']; $m=$_POST['projet'];  $c=$_POST['mission']; include('getmission.php');
									if (isset($_POST['categorie'])) {
										echo '<span id="txtHint3">';
										$c=$_POST['client']; $p=$_POST['projet']; $m=$_POST['mission']; $k=$_POST['categorie']; include('getcategorie.php');
										echo '</span>';
									}
								}
							}
						?>
					</span>
				</div>
				<div class="form-divider" id="f-rf2" <?php if (isset($_POST['Reprise'])) { echo 'style="display: none;"'; } ?>>
					<select class="form-control form-control-small" name="competition" id="competition" onchange="showType(this.value)">
						<option value="0">Comp&eacute;tition</option>
						<?php
						$reqimput = $bdd->query("SELECT * FROM rob_compl1 WHERE actif=1 ORDER BY description");
						while ($optimput = $reqimput->fetch())
						{
							if (isset($reprise)) { if ($rep_idc1 == $optimput['ID']) { $optsel = " selected"; } else { $optsel = ""; }
							} else {
							if (isset($_POST['competition']) AND $probldata == 1) { if ($_POST['competition'] == $optimput['ID']) {$optsel = " selected"; } else { $optsel = ""; }
							} else { $optsel = "";} }
							echo '<option value='.$optimput['ID'].$optsel.'>'.$optimput['description'].'</option>';
						}
						$reqimput->closeCursor();
						?>
					</select>
					<span id="txtHint4">
						<?php if (isset($reprise)) { $p=$rep_idc1; $m=$rep_idc2; include('gettype.php'); } else 
						{ if (isset($_POST['typecomp']) AND $probldata == 1)
							{ $p=$_POST['competition']; $m=$_POST['typecomp']; include('gettype.php'); }
						} ?>
					</span>
					<span id="txtHint5">
						<?php if (isset($reprise)) { $c=$rep_idc1; $t=$rep_idc2; $e=$rep_idc3; include('getevnmt.php'); } else 
						{ if (isset($_POST['evnmt']) AND isset($_POST['typecomp']) AND $probldata == 1)
							{ $c=$_POST['competition']; $t=$_POST['typecomp'];  $e=$_POST['evnmt']; include('getevnmt.php'); }
						} ?>
					</span>
				</div>
				<div class="form-divider">
					<select class="form-control form-control-small" name="nature1" />
						<option value="none">Nature de prestation</option>
						<?php
						$reqimput = $bdd->query("SELECT ID, Description FROM rob_nature1 WHERE actif = 1 ORDER BY Description");
						while ($optimput = $reqimput->fetch())
						{
							if (isset($reprise)) { if ($rep_nat1 == $optimput['ID']) { $optsel = " selected"; } else { $optsel = ""; }
							} else {
							if (isset($_POST['nature1']) AND $probldata == 1) { if ($_POST['nature1'] == $optimput['ID']) {$optsel = " selected"; } else { $optsel = ""; }
							} else { $optsel = "";} }
							echo '<option value='.$optimput['ID'].$optsel.'>'.$optimput['Description'].'</option>';
						}
						$reqimput->closeCursor();
						?>
					</select>
					<input class="form-control form-control-small" type="text" size="70" name="info" placeholder="Description"
						<?php
							if (isset($reprise)) { echo ' value="'.$rep_info.'" ';
							} else {
								if (isset($_POST['info']) AND $probldata == 1) { echo ' value="'.$_POST['info'].'" '; } else { echo ' value="" '; }
							}
						?>
					/>
				</div>
				<div>
					<input class="form-control form-control-small" type="text" size="10" id="frsCtUnit" name="frsCtUnit" onkeyup="frscalc()" title="UnitHT" placeholder="Unit HT"<?php if (isset($reprise)) { echo ' value="'.$rep_unit.'"'; } ?> />
					<span class="form-operator">x</span>
					<input class="form-control form-control-small" type="text" size="5" id="frsQty" name="frsQty" onkeyup="frscalc()" title="Qt" placeholder="Quantit&eacute;"<?php if (isset($reprise)) { echo ' value="'.$rep_nbre.'"'; } else { echo ' value="1"'; } ?> />
					<span class="form-operator">=</span>
					<input class="form-control form-control-small" type="text" size="10" id="frstot" name="frstot" title="TotalHT" placeholder="Total HT" <?php if (isset($reprise)) { echo ' value="'.$rep_tota.'"'; } ?> disabled />
					<input type="hidden" id="frsCtTotHT" name="frsCtTotHT" readonly="readonly"<?php if (isset($reprise)) { echo ' value="'.$rep_tota.'"'; } ?> />
				</div>
				<div>
					<input class="btn btn-small btn-primary" type="submit" Value="Enregistrer" name="Valider" />
				</div>
			</div>
		</form>
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

	if (isset($_POST['toutfrais'])) { $titrestrict='Devis sur '.$fmonth.' '.$year; $txtsitu = 1;
	} else { $titrestrict='Devis en attente'; $txtsitu = 0; }
	?>
		
	<!-- =================== RESTITUTION: TABLEAU ================= -->
	<section class="container section-container" id="historique-frais">
		<div class="section-title">
			<h1>Historique de mes devis</h1>
		</div>
		<div class="frais-filter">
			<?php
			//MONTH
			echo '<form action="devis.php" method="post"><select class="form-control form-control-small" name="affmonth" />';
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
			echo '<select class="form-control form-control-small" name="affyear">';
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
			
			//VISUALISER SES DEVIS
			echo '<input type="submit" id="buttonval" class="btn btn-primary" name="toutdevis" value="Visualiser tous les devis"></form>';
			
			//EDITER LE DEVIS
			echo '<form action="devis-pdf.php" method="post" class="form-right" target="_blank">';
				echo '<select class="form-control form-control-small" name="devisNum" />';
					$reqimput = $bdd->query("SELECT DISTINCT devisNum FROM rob_devis WHERE actif = 1 ORDER BY devisNum DESC");
					while ($optimput = $reqimput->fetch())
					{
						echo '<option value='.$optimput['devisNum'].$optsel.'>'.$optimput['devisNum'].'</option>';
					}
					$reqimput->closeCursor();
				echo '</select>';
				echo '<select class="form-control form-control-small" name="devisVers" />';
					$reqimput = $bdd->query("SELECT DISTINCT devisVersion FROM rob_devis WHERE actif = 1 ORDER BY devisVersion DESC");
					while ($optimput = $reqimput->fetch())
					{
						echo '<option value='.$optimput['devisVersion'].$optsel.'>'.$optimput['devisVersion'].'</option>';
					}
					$reqimput->closeCursor();
				echo '</select>';
				echo '<input type="submit" id="buttonval" class="btn btn-primary" name="EditerDevis" value="&Eacute;diter le devis" />';
			echo '</form>'; ?>
		</div>
		
		<h2><?php echo $titrestrict; ?></h2>
		<table id="tablerestit" class="table table-striped">
			<thead>
				<tr>
					<th id="t-containertit">Num</th>
					<th id="t-containertit">Version</th>
					<th id="t-containertit">Client/ Projet/ Mission/ Cat&eacute;gorie</th>
					<th id="t-containertit">Comp&eacute;tition/ Type/ &Eacute;v&eacute;nement</th>
					<th id="t-containertit">Date</th>
					<th id="t-containertit">Nature</th>
					<th id="t-containertit">Description</th>
					<th id="t-containertit" align="right">Co&ucirc;t unitaire HT</th>
					<th id="t-containertit" align="right">Nombre</th>
					<th id="t-containertit" align="right">Total HT</th>
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
				if (isset($_POST['toutdevis'])) { $txtrestrict=" AND T1.dateTransac >= '$startdate' AND T1.dateTransac < '$enddate'"; 
				} else { $txtrestrict=" AND T1.validation < 2"; }
				$req = "SELECT T1.ID ID, T2.matricule userID, T1.dateTransac dateTransac, 
					T3.Description client, T4.Description projet, T5.Description mission, T6.Description categorie, 
					T7.Description type, T8.Description competition, T9.Description evenement, T11.Description nature1, 
					T1.descriptif descriptif, T1.unitaire unitaire, T1.quantite quantite, T1.total total, T1.devisNum devisNum, T1.devisVersion devisVersion,
					T4.ID projetID, T5.ID missionID, T6.ID categorieID, T8.ID competitionID, T9.ID evenementID FROM rob_devis T1 
					INNER JOIN rob_user T2 ON T2.ID = T1.userID
					INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID1 
					INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputID2 
					INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputID3 
					INNER JOIN rob_imputl4 T6 ON T6.ID = T1.imputID4 
					INNER JOIN rob_compl1 T7 ON T7.ID = T1.compID1 
					INNER JOIN rob_compl2 T8 ON T8.ID = T1.compID2 
					INNER JOIN rob_compl3 T9 ON T9.ID = T1.compID3 
					INNER JOIN rob_nature1 T11 ON T11.ID = T1.nature1ID
					WHERE T1.userID='$pseudo'".$txtrestrict."
					ORDER BY T1.devisNum DESC, T1.devisVersion DESC, T3.code, T4.code, T11.Description";
				$reponsea = $bdd->query($req);
				$checkrep=$reponsea->rowCount();
				$devisNumTmp = "";
				$devisVersTmp = "";
				$devisTotTmp = 0;

				if ($checkrep != 0)
				{
					while ($donneea = $reponsea->fetch())
					{
						if ($devisNumTmp == "")
						{
							$devisNumTmp = $donneea['devisNum'];
							$devisVersTmp = $donneea['devisVersion'];
							$devisTotTmp = $devisTotTmp + $donneea['total'];
						}
						else
						{
							if ($devisNumTmp != $donneea['devisNum'] OR $devisVersTmp != $donneea['devisVersion'])
							{
								echo '<tr class="tr-'.$highlight.'">';
								echo '<td><strong>'.$devisNumTmp.'</strong></td>';
								echo '<td><strong>'.$devisVersTmp.'</strong></td>';
								echo '<td colspan=7><strong>&nbsp;</strong></td>';
								echo '<td align="right"><strong>'.$devisTotTmp.'</strong></td>';
								echo '<td>&nbsp;</td>';
								$devisNumTmp = $donneea['devisNum'];
								$devisVersTmp = $donneea['devisVersion'];
								$devisTotTmp = $donneea['total'];
							}
							else
							{
								$devisTotTmp = $devisTotTmp + $donneea['total'];
							}
						}
						//if ($donneea[2] <= $deadline OR $donneea[21] != '' OR $donneea[22] == 2) { 
						//	$l = " disabled"; 
						//	$highlight ="v"; 
						//} else { 
							$l = ""; 
							$highlight = "no-highlight";
						//}
						
						echo '<tr class="tr-'.$highlight.'">';
						echo '<td>'.$donneea['devisNum'].'</td>';
						echo '<td>'.$donneea['devisVersion'].'</td>';
						//clients
						echo '<td>'.$donneea['client'];
							if ($donneea['projetID'] != 0) { echo '<br/>&harr;'.$donneea['projet'];
								if ($donneea['missionID'] != 0) { echo '<br/>&nbsp;&harr;'.$donneea['mission'];
									if ($donneea['categorieID'] != 0) { echo '<BR/>&nbsp;&nbsp;&harr;'.$donneea['categorie']; } } }
						echo '</td>';
						//Compétition
						echo '<td>'.$donneea['type'];
							if ($donneea['competitionID'] != 0) { echo '<br/>&harr;'.$donneea['competition'];
								if ($donneea['evenementID'] != 0) { echo '<br/>&nbsp;&harr;'.$donneea['evenement'].'</td>'; } }
						echo '</td>';
						//date du jour
						echo '<td>'.date("d/m/Y", strtotime($donneea['dateTransac'])).'</td>';
						//nature1
						echo '<td>'.$donneea['nature1'].'</td>';
						//info
						echo '<td>'.$donneea['descriptif'].'</td>';
						//valeurs
						echo '<td align="right">'.$donneea['unitaire'].'</td>';
						echo '<td align="right">'.$donneea['quantite'].'</td>';
						echo '<td align="right">'.$donneea['total'].'</td>';
						//status
						echo '<td>';
						echo '<form action="devis.php" method="post" class="duplicate-edit-remove">';
							echo '<input type="hidden" value="'.$pseudo.'" name="affcoll" />';
							echo '<input type="hidden" value="'.$year.'" name="affyear" />';
							echo '<input type="hidden" value="'.$month.'" name="affmonth" />';
							echo '<input type="hidden" value="'.$donneea['ID'].'" name="modid" />';
							//if ($donneea[2] <= $deadline OR $donneea[22] == 2) {
							//	echo '<button type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise"><i class="fa fa-files-o"></i></button>';
								// echo '</form></td></tr>';
							//} else {
							//	if ($donneea[21] != '') {
							//		echo '<button type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise"><i class="fa fa-files-o"></i></button>';
							//		echo '<button type="submit" Value="V" title="D&eacute;v&eacute;rouiller cette note de frais" name="deverr" onclick="return(confirm(\'Etes-vous sur de vouloir d&eacute;v&eacute;rouiller l\int&eacute;gralit&eacute; de cette note de frais?\'))"><i class="fa fa-unlock"></i></button>';
							//	} else {
									//echo '<td><button type="submit" Value="Mod." name="Mod" onclick="return(confirm(\'Etes-vous sur de vouloir modifier les temps de cette ligne?\'))" /><br/>';
									echo '<button type="submit" Value="M" title="Modifier les informations de cette ligne" name="Modif" onclick="return(confirm(\'Les donn&eacute;es seront reprises dans le formulaire et cette ligne sera supprim&eacute;e. &Ecirc;tes vous s&ucirc;r?\'))"><i class="fa fa-pencil-square-o"></i></button>';
									echo '<button type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise"><i class="fa fa-files-o"></i></button>';
									echo '<button type="submit" Value="S" title="Supprimer la ligne" name="Suppr" onclick="return(confirm(\'Etes-vous sur de vouloir supprimer cette entree?\'))"><i class="fa fa-trash-o"></i></button>';
							//	}
							//}
						echo '</form></td></tr>';
						if ($i == 1) { $i = 2; } else { $i = 1; }
					}
					echo '<tr class="tr-'.$highlight.'">';
					echo '<td><strong>'.$devisNumTmp.'</strong></td>';
					echo '<td><strong>'.$devisVersTmp.'</strong></td>';
					echo '<td colspan=7><strong>&nbsp;</strong></td>';
					echo '<td align="right"><strong>'.$devisTotTmp.'</strong></td>';
					echo '<td>&nbsp;</td>';
				}
				$reponsea->closeCursor();
				?>
			</tbody>
		</table>
	</section>
		
<?php include("footer.php"); 
}
else
{
	header("location:index.php");
}
?>