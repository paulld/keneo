<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	$probldata = 0;
	$comptprblm = 0;
	$comptchg = 0;
	$ok = 0;

	//Deadline
	$dead = $bdd->query("SELECT deadline FROM rob_verrouille WHERE ID=3");
	$deadlinetab = $dead->fetch();
	$deadline = $deadlinetab[0];
	$dead->closeCursor();

	if (isset($_POST['Valider']) AND isset($_POST['phase']) AND isset($_POST['classID']))
	{
		$Phase = $_POST['phase'];
		$classID = $_POST['classID'];
		if (isset($_POST['userID']) AND isset($_POST['client']) AND isset($_POST['projet']) AND isset($_POST['nature1ID']) AND isset($_POST['activID']) AND isset($_POST['dateTransac']))
		{
			if ($_POST['client'] != "none" AND $_POST['projet'] != "none" AND $_POST['nature1ID'] != 0 AND $_POST['competition'] != "none" AND $_POST['activID'] != "none" AND $_POST['dateTransac'] != 0)
			{
				$userID = $_POST['userID'];
				$dateTransac = date('Y-m-d',mktime(0,0,0,substr($_POST['dateTransac'],3,2),substr($_POST['dateTransac'],0,2),substr($_POST['dateTransac'],6,4)));
				$imputID1 = $_POST['client'];
				$imputID2 = $_POST['projet'];
				if (isset($_POST['mission'])) { $imputID3 = $_POST['mission']; } else { $imputID3 = 0; }
				if (isset($_POST['categorie'])) { $imputID4 = $_POST['categorie']; } else { $imputID4 = 0; }
				$compID1 = $_POST['competition'];
				if (isset($_POST['typecomp'])) { $compID2 = $_POST['typecomp']; } else { $compID2 = 0; }
				if (isset($_POST['evnmt'])) { $compID3 = $_POST['evnmt']; } else { $compID3 = 0; }
				if (isset($_POST['lieuEve'])) { $compLieu = $_POST['lieuEve']; } else { $compLieu = ''; }
				if (isset($_POST['deadline2'])) { $compDate = date('Y-m-d',mktime(0,0,0,substr($_POST['deadline2'],3,2),substr($_POST['deadline2'],0,2),substr($_POST['deadline2'],6,4))); } else { $compDate = '0000-00-00'; }
				$nature1ID = $_POST['nature1ID'];
				if (isset($_POST['nature2ID'])) { $nature2ID = $_POST['nature2ID']; } else { $nature2ID = 0; }
				if (isset($_POST['profilID'])) { $profilID = $_POST['profilID']; } else { $profilID = 0; }
				if (isset($_POST['collaborateurID'])) { $collaborateurID = $_POST['collaborateurID']; } else { $collaborateurID = 0; }
				if (isset($_POST['beneficiaire'])) { $beneficiaire = $_POST['beneficiaire']; } else { $beneficiaire = 0; }
				$activID = $_POST['activID'];
				$descriptif = $_POST['info'];
				$descriptif = str_replace("'","\'",$descriptif);
			
				if ($_POST['phase']==1)
				{
					//Actual
					if ($_POST['classID'] == 1)
					{
						$unitaire = $_POST['frsCtUnit'];
						$quantite = $_POST['frsQty'];
						$total = $_POST['frsCtTotHT'];
						$frsID = $_POST['frsID'];
					}
					else
					{
						$unitaire = $_POST['cltUnit'];
						$quantite = $_POST['cltQty'];
						$total = $_POST['cltTotHT'];
						$frsID = 0;
					}
					$affectBudID = 0;
				}
				else
				{
					$unitaire = $_POST['bUnit'];
					$quantite = $_POST['bQty'];
					$total = $_POST['bTotHT'];
					$frsID = 0;
					$affectBudID = $_POST['affectBudID'];
				}
				$devis = $_POST['devis'];
				$BDC = $_POST['BDC'];
				$dateFact = $_POST['dateFact'];
				$numFact = $_POST['numFact'];
				$paiement = $_POST['paiement'];
				$bdd->query("INSERT INTO rob_journal VALUES ('', '$userID', '$activID', '$dateTransac', '$imputID1', '$imputID2', '$imputID3', '$imputID4', '$affectBudID', '$nature1ID', '$nature2ID', '$profilID', '$collaborateurID', '$beneficiaire', '$compID1', '$compID2', '$compID3', '$compLieu', '$compDate', '$descriptif', '$frsID', '$devis', '$BDC', '$dateFact', '$numFact', '$paiement', '$unitaire', '$quantite', '$total', '$Phase', '$classID')");
				$ok = 1;
				if (isset($_POST['Reprise']) AND $_POST['Reprise'] == 2)
				{
					$idenrtmp = $bdd->query("SELECT ID FROM rob_journal WHERE userID='$userID' AND activID='$activID' AND dateTransac='$dateTransac' AND imputID1='$imputID1' 
					AND imputID2='$imputID2' AND imputID3='$imputID3' AND imputID4='$imputID4' AND affectBudID='$affectBudID' AND nature1ID='$nature1ID' 
					AND nature2ID='$nature2ID' AND profilID='$profilID' AND collaborateurID='$collaborateurID' AND beneficiaire='$beneficiaire' AND compID1='$compID1' 
					AND compID2='$compID2' AND compID3='$compID3' AND compLieu='$compLieu' AND compDate='$compDate' AND descriptif='$descriptif' AND frsID='$frsID' 
					AND devis='$devis' AND BDC='$BDC' AND dateFact='$dateFact' AND numFact='$numFact' AND paiement='$paiement' AND unitaire='$unitaire' 
					AND quantite='$quantite' AND total='$total' AND Phase='$Phase' AND classID='$classID' LIMIT 1");
					$idenrid = $idenrtmp->fetch();
					$idenr = $idenrid['ID'];
				}
			}
			else
			{
				$comptprblm = 1;
			}
		}
		else
		{
			$comptprblm = 1;
		}
	}

	if (isset($_POST['Reprise']) AND $_POST['Reprise'] == 1 AND $comptprblm == 0)
	{
		header("location:listing.php");
	}
	else
	{
		if (isset($_POST['Reprise']) AND $_POST['Reprise'] != 1 AND $_POST['Reprise'] != 3 OR isset($_POST['Modif']))
		{
			if (isset($idenr)) { } else { $idenr = $_POST['modid']; }
			$journ = $bdd->query("SELECT * FROM rob_journal WHERE ID = '$idenr' LIMIT 1");
			$journal = $journ->fetch();
			$rphase = $journal['Phase'];
			$rclassID = $journal['classID'];
			$rdateTransac = $journal['dateTransac'];
			$ractivID = $journal['activID'];
			$rimputID1 = $journal['imputID1'];
			$rimputID2 = $journal['imputID2'];
			$rimputID3 = $journal['imputID3'];
			$rimputID4 = $journal['imputID4'];
			$raffectBudID = $journal['affectBudID'];
			$rnature1ID = $journal['nature1ID'];
			$rnature2ID = $journal['nature2ID'];
			$rprofilID = $journal['profilID'];
			$rcollaborateurID = $journal['collaborateurID'];
			$rbeneficiaire = $journal['beneficiaire'];
			$rcompID1 = $journal['compID1'];
			$rcompID2 = $journal['compID2'];
			$rcompID3 = $journal['compID3'];
			if ($journal['compID1'] != 0 and $journal['compID1'] != 0 and $journal['compID1'] != 0)
			{
				$cateve = $bdd->query("SELECT * FROM rob_comprel3 WHERE imputID = '$rcompID1' AND imputID2 = '$rcompID2' AND imputID3 = '$rcompID3' LIMIT 1");
				$categeve = $cateve->fetch();
				$rcompLieu = $categeve['lieu'];
				$rcompDate = $categeve['date'];
				$cateve->closeCursor();
			} else {
				$rcompLieu = "";
				$rcompDate = "";
			}
			$rdescriptif = $journal['descriptif'];
			$rfrsID = $journal['frsID'];
			$rdevis = $journal['devis'];
			$rBDC = $journal['BDC'];
			$rdateFact = $journal['dateFact'];
			$rnumFact = $journal['numFact'];
			$rpaiement = $journal['paiement'];
			$runitaire = $journal['unitaire'];
			$rquantite = $journal['quantite'];
			$rtotal = $journal['total'];
			$journ->closeCursor();
			if (isset($_POST['Modif']))
			{
				if ($deadline < $rdateTransac)
				{
					$bdd->query("DELETE FROM rob_journal WHERE ID='$idenr' LIMIT 1");
				}
				else
				{
					$deadreach = $deadreach + 1;
				}
			}
		}
		?>
			
		<!-- =================== SAISIE ================= -->
		<div id="navigationMap">
			<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="listing.php"><span>Journal</span></a></li><li><a class="typ" href="#"><span>Cr&eacute;ation/ Modification</span></a></li></ul>
		</div>
		<div id="clearl"></div>
		<div id="haut">Cr&eacute;ation/ Modification</div>

		<form action="journal.php" method="post">
		<div id="coeur">
			<div id="f-allphase">Transaction : <select name="phase" onchange="cacher(this.value)">
					<?php
					$reqimput = $bdd->query("SELECT * FROM rob_phase WHERE inputOpen = 1 ORDER BY ID");
					while ($optimput = $reqimput->fetch())
					{
						if (isset($idenr) AND $optimput['ID'] == $rphase) { $sel = " selected"; } else { $sel = ""; }
						echo '<option value='.$optimput['ID'].$sel.'>'.$optimput['Phase'].' - '.$optimput['Description'].'</option>';
					}
					$reqimput->closeCursor();
					?>
				</select><select name="classID" onchange="cacher2(this.value)" >
				<?php
				$reqimput = $bdd->query("SELECT * FROM rob_class ORDER BY code");
				while ($optimput = $reqimput->fetch())
				{
					if (isset($idenr) AND $optimput['ID'] == $rclassID) { $sel = " selected"; } else { $sel = ""; }
					echo '<option value='.$optimput['ID'].$sel.'>'.$optimput['code'].'</option>';
				}
				$reqimput->closeCursor();
				?>
				</select>
				<input type="hidden" id="ma_page" value="2" />
				<input type="hidden" name="userID" value="<?php echo $_SESSION['ID']; ?>" />
				<input size="8" type="text" id="dateTransac" name="dateTransac" title="Date de transaction"
				<?php if (isset($dateTransac)) { echo ' value="'.date("d/m/Y",strtotime($dateTransac)).'"'; } else { 
				if (isset($idenr)) { echo ' value="'.date("d/m/Y",strtotime($rdateTransac)).'"'; } else { echo ' value="'.date("d/m/Y").'"'; } } 
			?> /></div>
			<div id="f-allphase">Nature : <select name="nature1ID" onchange="showAna2(this.value)" >
					<option value="none">Nature...</option>
					<?php
					$reqimput = $bdd->query("SELECT ID, Description FROM rob_nature1 WHERE actif = 1 ORDER BY Description");
					while ($optimput = $reqimput->fetch())
					{
						if (isset($idenr) AND $optimput['ID'] == $rnature1ID) { $sel = " selected"; } else { $sel = ""; }
						echo '<option value='.$optimput['ID'].$sel.'>'.$optimput['Description'].'</option>';
					}
					$reqimput->closeCursor();
					?>
				</select>
			<span id="selectHint">
				<?php if (isset($idenr)) { $a=$rnature1ID; $a2=$rnature2ID; include('getnat2.php'); } else 
				{ if (isset($_POST['nature2ID']) AND $probldata == 1)
					{ $a=$_POST['nature1ID']; $a2=$_POST['nature2ID']; include('getnat2.php'); }
				} ?></span>
			<span id="selectHint2">
				<?php if (isset($idenr)) { $p=$rnature2ID; $pr=$rprofilID; $coll=$rcollaborateurID; $benef=$rbeneficiaire; include('getprofil.php'); } else 
				{ if (isset($_POST['profilID']) AND $probldata == 1)
					{ $p=$_POST['nature2ID']; $pr=$_POST['profilID']; $coll=$_POST['collaborateurID']; $benef=$_POST['beneficiaire']; include('getprofil.php'); }
				} ?></span></div>
			<div id="f-rf1" <?php if (isset($idenr) AND $rphase == 2) { echo 'style="display:block"'; } else { echo 'style="display:none"'; } ?>>Affectation budg&eacute;taire : <select name="affectBudID" >
				<?php
				$reqimput = $bdd->query("SELECT ID, Description FROM rob_affbud WHERE actif = 1 ORDER BY ID");
				while ($optimput = $reqimput->fetch())
				{
					if (isset($idenr) AND $optimput['ID'] == $raffectBudID) { $sel = " selected"; } else { $sel = ""; }
					echo '<option value='.$optimput['ID'].$sel.'>'.$optimput['Description'].'</option>';
				}
				$reqimput->closeCursor();
				?>
			</select></div><br/>
			<div id="f-client">
				Client : <select name="client" id="client" onchange="showProjet(this.value)">
					<option value="none">...</option>
					<?php
					$reqimput = $bdd->query("SELECT * FROM rob_imputl1 WHERE actif=1 ORDER BY description");
					while ($optimput = $reqimput->fetch())
					{
						if (isset($idenr) AND $optimput['ID'] == $rimputID1) { $sel = " selected"; } else { $sel = ""; }
						echo '<option value='.$optimput['ID'].$sel.'>'.$optimput['description'].'</option>';
					}
					$reqimput->closeCursor();
					?>
				</select><span id="txtHint">
					<?php if (isset($idenr)) { $p=$rimputID1; $m=$rimputID2; include('getprojet.php'); } else 
					{ if (isset($_POST['projet']) AND $probldata == 1)
						{ $p=$_POST['client']; $m=$_POST['projet']; include('getprojet.php'); }
					} ?></span>
			</div>
			<div id="txtHint2">
				<?php if (isset($idenr))
				{
					$k=$rimputID1; $m=$rimputID2; $c=$rimputID3; include('getmission.php');
					echo '<span id="txtHint3">';
					$c=$rimputID1; $p=$rimputID2; $m=$rimputID3; $k=$rimputID4; include('getcategorie.php');
					echo '</span>';
					
				} else 
				{
					if (isset($_POST['mission']) AND isset($_POST['projet']) AND $probldata == 1)
					{
						$p=$_POST['client']; $m=$_POST['projet'];  $c=$_POST['mission']; include('getmission.php');
						if (isset($_POST['categorie']))
						{
							echo '<span id="txtHint3">';
							$c=$_POST['client']; $p=$_POST['projet']; $m=$_POST['mission']; $k=$_POST['categorie']; include('getcategorie.php');
							echo '</span>';
						}
					}
				}
				?></div>
			<br/>
			<div id="f-client">
				Comp&eacute;tition : <select name="competition" id="competition" onchange="showType(this.value)">
					<option value="0">Non applicable</option>
					<?php
					$reqimput = $bdd->query("SELECT * FROM rob_compl1 WHERE actif=1 ORDER BY description");
					while ($optimput = $reqimput->fetch())
					{
						if (isset($idenr) AND $optimput['ID'] == $rcompID1) { $sel = " selected"; } else { $sel = ""; }
						echo '<option value='.$optimput['ID'].$sel.'>'.$optimput['description'].'</option>';
					}
					$reqimput->closeCursor();
					?>
				</select><span id="txtHint4">
					<?php if (isset($idenr)) { $p=$rcompID1; $m=$rcompID2; include('gettype.php'); } else 
					{ if (isset($_POST['typecomp']) AND $probldata == 1)
						{ $p=$_POST['competition']; $m=$_POST['typecomp']; include('gettype.php'); }
					} ?></span>
			</div>
			<div id="txtHint5">
				<?php if (isset($idenr)) { $c=$rcompID1; $t=$rcompID2; $e=$rcompID3; include('getevnmt.php'); } else 
				{ if (isset($_POST['evnmt']) AND isset($_POST['typecomp']) AND $probldata == 1)
					{ $c=$_POST['competition']; $t=$_POST['typecomp'];  $e=$_POST['evnmt']; include('getevnmt.php'); }
				} ?><span id="txtHint6">
				<?php if (isset($idenr)) { $c=$rcompID1; $t=$rcompID2; $e=$rcompID3; $rlieu=$rcompLieu; include('getcateve.php'); } else 
				{ if (isset($_POST['evnmt']) AND isset($_POST['typecomp']) AND $probldata == 1)
					{ $c=$_POST['competition']; $t=$_POST['typecomp'];  $e=$_POST['evnmt']; include('getcateve.php'); }
				} ?></span></div><br/>
			
			<div id="f-allphase">Activit&eacute; : <select name="activID" >
				<option value=0>...</option>
				<?php
				$reqimput = $bdd->query("SELECT ID, Description FROM rob_activite WHERE actif = 1 ORDER BY Description");
				while ($optimput = $reqimput->fetch())
				{
					if (isset($idenr) AND $optimput['ID'] == $ractivID) { $sel = " selected"; } else { $sel = ""; }
					echo '<option value='.$optimput['ID'].$sel.'>'.$optimput['Description'].'</option>';
				}
				$reqimput->closeCursor();
				?>
			</select></div>
			<div id="f-descriptif">
				Description : <input type="text" size="70" name="info" placeholder="information libre"<?php if (isset($idenr)) { echo ' value="'.$rdescriptif.'"'; } ?> />
			</div><br/>
			<!--BUDGET-->
			<div id="f-rf2" <?php if (isset($idenr) AND $rphase == 2) { echo 'style="display:block"'; } else { echo 'style="display:none"'; } ?>>
				<input type="text" size="10" id="bUnit" name="bUnit" onchange="bcalc()" title="UnitHT" placeholder="UnitHT"<?php if (isset($idenr)) { echo ' value="'.$runitaire.'"'; } ?> /> x 
				<input type="text" size="5" id="bQty" name="bQty" onchange="bcalc()" title="Qt" placeholder="Qt"<?php if (isset($idenr)) { echo ' value="'.$rquantite.'"'; } else { echo ' value="1"'; } ?> /> = 
				<input type="text" size="10" id="bTot" name="bTot" readonly="readonly" title="TotalHT" placeholder="TotalHT"<?php if (isset($idenr)) { echo ' value="'.$rtotal.'"'; } ?> disabled />
					<input type="hidden" id="bTotHT" name="bTotHT" readonly="readonly"<?php if (isset($idenr)) { echo ' value="'.$rtotal.'"'; } ?> />
			</div>
			<!--FOURNISSEUR-->
			<div id="f-actual2" <?php if (isset($idenr) AND $rphase == 2) { echo 'style="display:none"'; } else { echo 'style="display:block"'; } ?>>
				<div id="f-depenses" <?php if (isset($idenr) AND $rclassID == 2) { echo 'style="display:none"'; } else { echo 'style="display:block"'; } ?>>
					<div id="f-descriptif">Fournisseur : <select name="frsID" id="frsID" onchange="showCatfrs(this.value)">
						<option value="none">...</option>
						<?php
						$reqimput = $bdd->query("SELECT * FROM rob_fournisseur WHERE actif=1 ORDER BY code");
						while ($optimput = $reqimput->fetch())
						{
							if (isset($idenr) AND $optimput['ID'] == $rfrsID) { $sel = " selected"; } else { $sel = ""; }
							echo '<option value='.$optimput['ID'].$sel.'>'.$optimput['code'].' | '.$optimput['Description'].'</option>';
						}
						$reqimput->closeCursor();
						?>
					</select><span id="txtHintfrs"></span></div>
					<input type="text" size="10" id="frsCtUnit" name="frsCtUnit" onchange="frscalc()" title="UnitHT" placeholder="UnitHT"<?php if (isset($idenr)) { echo ' value="'.$runitaire.'"'; } ?> /> x 
					<input type="text" size="5" id="frsQty" name="frsQty" onchange="frscalc()" title="Qt" placeholder="Qt"<?php if (isset($idenr)) { echo ' value="'.$rquantite.'"'; } else { echo ' value="1"'; } ?> /> = 
					<input type="text" size="10" id="frstot" name="frstot" title="TotalHT" placeholder="TotalHT"<?php if (isset($idenr)) { echo ' value="'.$rtotal.'"'; } ?> disabled />
						<input type="hidden" id="frsCtTotHT" name="frsCtTotHT" readonly="readonly"<?php if (isset($idenr)) { echo ' value="'.$rtotal.'"'; } ?> />
				</div>
				<!--KENEO-->
				<div id="f-recette" <?php if (isset($idenr) AND $rclassID == 2) { echo 'style="display:block"'; } else { echo 'style="display:none"'; } ?>>
					<input type="text" size="10" id="cltUnit" name="cltUnit" onchange="cltcalc()" placeholder="UnitHT" title="UnitHT"<?php if (isset($idenr)) { echo ' value="'.$runitaire.'"'; } ?> /> x 
					<input type="text" size="5" id="cltQty" name="cltQty" onchange="cltcalc()" placeholder="Qt" title="Qt"<?php if (isset($idenr)) { echo ' value="'.$rquantite.'"'; } else { echo ' value="1"'; } ?> /> = 
					<input type="text" size="10" id="cltFact" name="cltFact" placeholder="TotalHT" title="TotalHT"<?php if (isset($idenr)) { echo ' value="'.$rtotal.'"'; } ?> disabled />
						<input type="hidden" id="cltTotHT" name="cltTotHT" readonly="readonly"<?php if (isset($idenr)) { echo ' value="'.$rtotal.'"'; } ?> />
				</div>
				<!--TRONC COMMUN REEL-->
				Devis : <input type="text" size="10" name="devis" placeholder="N&deg; devis"<?php if (isset($idenr)) { echo ' value="'.$rdevis.'"'; } ?> /> | BDC : 
				<input type="text" size="10" name="BDC" placeholder="N&deg; BDC"<?php if (isset($idenr)) { echo ' value="'.$rBDC.'"'; } ?> />
				<div id="f-descriptif">Facture : <input type="text" size="10" name="numFact" placeholder="N&deg;"<?php if (isset($idenr)) { echo ' value="'.$rnumFact.'"'; } ?> />
					<input id="deadline1" type="hidden" size="10" name="dateFact" value="<?php echo date("Y-m-d"); ?>" />
					<input id="paiement" type="hidden" name="paiement" value=0 /></div>
				<div id="f-description">
					<select id="Reprise" name="Reprise">
						<option value=1 >Enregistrer et revenir au listing</option>
						<option value=2<?php if (isset($_POST['Reprise']) AND $_POST['Reprise'] == 2) { echo ' selected'; } ?> >Enregistrer et garder le contenu</option>
						<option value=3 >Enregistrer et vider les champs</option>
					</select>
				</div>
			</div>
			<div id="f-valider">
				<?php
				echo '<input id="buttonval" type="submit" Value="Enregistrer" name="Valider" />';
				?> 
			</div>
		</div>
		</form>
		<?php
		if ($ok == 1)
		{
			echo '<div id="message">La ligne de journal &agrave; &eacute;t&eacute; ajout&eacute;e</div>';
		}
		if ($comptprblm == 1)
		{
			echo '<div id="message">l\'enregistrement n\'a pas pu &ecirc;tre ajout&eacute;</div>';
		}
		include("footer.php");
	}
}
else
{
	header("location:index.php");
}
?>