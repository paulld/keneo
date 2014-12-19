<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	$comptprblm = 0;
	$tauxprblm = 0;
	$comptchg = 0;
	$globval = 0;
	$comptprblmmod = 0;
	$recupmsg = 0;
	$deadreach = 0;
	$probldata = 0;
							
	//Deadline
	$dead = $bdd->query("SELECT deadline FROM rob_verrouille WHERE ID=1");
	$deadlinetab = $dead->fetch();
	$deadline = $deadlinetab[0];
	$dead->closeCursor();

	if (isset($_POST['Valider']))
	{
		if (isset($_POST['Valider']) AND isset($_POST['client']) AND isset($_POST['datefrais']) AND isset($_POST['competition']) AND isset($_POST['mtttc']) AND isset($_POST['taux']) AND $_POST['taux'] != "none")
		{
			if ($_POST['client'] != "none" AND $_POST['activite'] != "none" AND $_POST['nature2'] != "none")
			{
				//Check sur projet et mission
				if ($_POST['projet'] != "none")
				{
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
				}
				else
				{
					$comptchg = 1;
				}
				//Combinaison de montants
				if (isset($_POST['mtht']) AND $_POST['mtht'] != 0)
				{
					$mtht = $_POST['mtht'];
					if (isset($_POST['taux']) and $_POST['taux'] != 'none')
					{
						$taux = $_POST['taux'];
						$mttva = $taux * $mtht;
						$mtttc = $mtht + $mttva;
						$req = $bdd->query("SELECT ID FROM rob_tva WHERE actif=1 AND taux='$taux'");
						$optreq = $req->fetch();
						$taux = $optreq['ID'] ;
						$req->closeCursor();
					}
					else
					{
						$mttva = $_POST['mttva'];
						$mtttc = $mtht + $mttva;
						$tauxMT = round(($mtttc - $mtht) / $mtht,2);
						$req = $bdd->query("SELECT ID FROM rob_tva WHERE actif=1 AND taux='$tauxMT'");
						$checkrow=$req->rowCount();
						if ($checkrow != 0)
						{
							$optreq = $req->fetch();
							$taux = $optreq['ID'] ;
						}
						else
						{
							$tauxprblm = $tauxMT;
						}
						$req->closeCursor();
					}
				}
				else
				{
					$mtttc = $_POST['mtttc'];
					if (isset($_POST['taux']) and $_POST['taux'] != 'none')
					{
						$taux = $_POST['taux'];
						$mtht = $mtttc / (1 + $taux);
						$mttva = $mtttc - $mtht;
						$req = $bdd->query("SELECT ID FROM rob_tva WHERE actif=1 AND taux='$taux'");
						$optreq = $req->fetch();
						$taux = $optreq['ID'] ;
						$req->closeCursor();
					}
					else
					{
						$mttva = $_POST['mttva'];
						$mtht = $mtttc - $mttva;
						$tauxMT = round($mttva / $mtht,2);
						$req = $bdd->query("SELECT ID FROM rob_tva WHERE actif=1 AND taux='$tauxMT'");
						$checkrow=$req->rowCount();
						if ($checkrow != 0)
						{
							$optreq = $req->fetch();
							$taux = $optreq['ID'] ;
						}
						else
						{
							$tauxprblm = $tauxMT;
						}
						$req->closeCursor();
					}
				}
				//insertion dans la table
				if ($comptchg == 0 AND $tauxprblm == 0)
				{
					$matricule = $_SESSION['ID'];
					$collab = $_POST['collaborateur'];
					$info = $_POST['info'];
					$info = str_replace("'","\'",$info);
					if (isset($_POST['refact']) and $_POST['refact'] == 1) { $refact=1; } else { $refact=0; }
					$fmonth = date("F", mktime(0,0,0,substr($_POST['datefrais'],3,2),substr($_POST['datefrais'],0,2),substr($_POST['datefrais'],6,4)));
					$month = date("m", mktime(0,0,0,substr($_POST['datefrais'],3,2),substr($_POST['datefrais'],0,2),substr($_POST['datefrais'],6,4)));
					$year = date("Y", mktime(0,0,0,substr($_POST['datefrais'],3,2),substr($_POST['datefrais'],0,2),substr($_POST['datefrais'],6,4)));
					$datefrais = date('Y-m-d',mktime(0,0,0,substr($_POST['datefrais'],3,2),substr($_POST['datefrais'],0,2),substr($_POST['datefrais'],6,4)));
					$client = $_POST['client'];
					$projet = $_POST['projet'];
					if (isset($_POST['mission'])) { $mission = $_POST['mission']; } else { $mission = 0; }
					if (isset($_POST['categorie'])) { $categorie = $_POST['categorie']; } else { $categorie = 0; }
					$competition = $_POST['competition'];
					if (isset($_POST['typecomp'])) { $typecomp = $_POST['typecomp']; } else { $typecomp = 0; }
					if (isset($_POST['evnmt'])) { $evnmt = $_POST['evnmt']; } else { $evnmt = 0; }
					$nature2 = $_POST['nature2'];
					$aujourdhui = date("Y-m-d");
					$activite = $_POST['activite'];
					if ($deadline < $datefrais)
					{
						$bdd->query("INSERT INTO rob_frais VALUES ('', '$datefrais', '$collab', '$client', '$projet', '$mission', '$categorie', '$competition', '$typecomp', '$evnmt', '$nature2', '$activite', '$info', '$refact', '$mtht', '$mttva', '$mtttc', '$taux', '', 0, '$aujourdhui')");
					}
					else
					{
						$deadreach = $deadreach + 1;
					}
				}
			}
			else
			{
				//client non saisi
				$comptchg = 1;
				$probldata = 1;
			}
		}
		else
		{
			$comptprblm = 1;
			$probldata = 1;
		}
	}
	else
	{
		if (isset($_POST['Reprise']) OR isset($_POST['Modif']))
		{
			$rep_id = $_POST['modid'];
			$req = "SELECT imputID, imputIDl2, imputIDl3, imputIDl4, compID, compID2, compID3, info, refact, activID, datejour, totalTTC, taux, nature2ID FROM rob_frais T1
				INNER JOIN rob_tva T2 ON T1.tauxTVA = T2.ID
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
				$rep_refa = $repdon[8];
				$rep_idac = $repdon[9];
				$rep_dajr = date("d/m/Y",strtotime($repdon[10]));
				$rep_tota = $repdon[11];
				$rep_tva = $repdon[12];
				$rep_nat2 = $repdon[13];
			}
			$repreq->closeCursor();
		}
		if (isset($_POST['Suppr']) OR isset($_POST['Modif']))
		{
			$idenr = $_POST['modid'];
			$datejourtmp = $_POST['moddate'];
			if ($deadline < $datejourtmp)
			{
				$bdd->query("DELETE FROM rob_frais WHERE ID='$idenr' LIMIT 1");
			}
			else
			{
				$deadreach = $deadreach + 1;
			}
		}
		else
		{
			if (isset($_POST['deverr']))
			{
				$flag = $_POST['flag'];
				$datejourtmp = $_POST['moddate'];
				if ($deadline < $datejourtmp)
				{
					$bdd->query("UPDATE rob_frais SET noteNum = '' WHERE noteNum='$flag'");
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
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="#"><span>Frais</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Saisir mes frais</div>

	
	<form action="frais.php" method="post">
	<div id="tablesaisie">
		<div id="f-fraisl">
			<select name="nature2" />
				<option value="none">Nature de frais...</option>
				<?php
				$reqimput = $bdd->query("SELECT ID, Description FROM rob_nature2 WHERE actif = 1 AND Compte <> '' ORDER BY Description");
				while ($optimput = $reqimput->fetch())
				{
					if (isset($reprise)) { if ($rep_nat2 == $optimput['ID']) { $optsel = " selected"; } else { $optsel = ""; }
					} else {
					if (isset($_POST['nature2']) AND $probldata == 1) { if ($_POST['nature2'] == $optimput['ID']) {$optsel = " selected"; } else { $optsel = ""; }
					} else { $optsel = "";} }
					echo '<option value='.$optimput['ID'].$optsel.'>'.$optimput['Description'].'</option>';
				}
				$reqimput->closeCursor();
				?>
			</select>
			<?php
			echo ' <input size="12" type="text" name="datefrais" id="datefrais" value="';
				if (isset($_POST['datefrais']))
				{
					echo $_POST['datefrais'];
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
				echo '" />';
			echo '<input type="hidden" value='.$_SESSION['ID'].' name="collaborateur" />';
			?> 
		</div>
		<input type="hidden" id="ma_page" value="1" />
		<div id="f-fraisr">
			<!--Montant HT : <input type="text" size="5" name="mtht" placeholder="0.00" /><br />
			Montant TVA : <input type="text" size="5" name="mttva" placeholder="0.00" /><br />-->
			&euro; TTC : <input style="text-align:right" type="text" size="12" name="mtttc" placeholder="0.00" value="<?php if (isset($reprise)) { echo $rep_tota; } else { if (isset($_POST['mtttc']) AND $probldata == 1) { echo $_POST['mtttc']; } } ?>" />   
			Taux de TVA : <select style="text-align:right" name="taux" />
				<option value="none">TVA...</option>
				<?php
				$reqimput = $bdd->query("SELECT * FROM rob_tva WHERE actif=1 ORDER BY taux");
				while ($optimput = $reqimput->fetch())
				{
					if (isset($reprise)) { if ($rep_tva == $optimput['taux']) { $optsel = " selected"; } else { $optsel = ""; }
					} else {
					if (isset($_POST['taux']) AND $probldata == 1) { if ($_POST['taux'] == $optimput['taux']) {$optsel = " selected"; } else { $optsel = ""; }
					} else { $optsel = "";} }
					echo '<option value='.$optimput['taux'].$optsel.'>'.$optimput['taux'] * 100 .'%</option>';
				}
				$reqimput->closeCursor();
				?>
			</select><br/>
			<?php
			if (isset($reprise)) { if ($rep_refa == 1) { $optsel = " checked"; } else { $optsel = ""; } } else { $optsel = ""; }
			echo '<input type="checkbox" name="refact" value="1" title="Cochez si refacturable"'.$optsel.' />Refacturable au client';
			?>
			
		</div>
		<br/>
		<div id="f-fraislb">
			<div id="f-client">
				Client : <select name="client" id="client" onchange="showProjet(this.value)">
					<option value="none">...</option>
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
			</div>
			<div id="txtHint2">
				<?php if (isset($reprise))
				{
					$k=$rep_idl1; $m=$rep_idl2; $c=$rep_idl3; include('getmission.php');
					echo '<span id="txtHint3">';
					$c=$rep_idl1; $p=$rep_idl2; $m=$rep_idl3; $k=$rep_idl4; include('getcategorie.php');
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
		</div>
		<br/>
		<div id="f-fraisrb">
			<div id="f-client">
				Comp&eacute;tition : <select name="competition" id="competition" onchange="showType(this.value)">
					<option value="0">Non applicable</option>
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
				</select><span id="txtHint4">
				<?php if (isset($reprise)) { $p=$rep_idc1; $m=$rep_idc2; include('gettype.php'); } else 
				{ if (isset($_POST['typecomp']) AND $probldata == 1)
					{ $p=$_POST['competition']; $m=$_POST['typecomp']; include('gettype.php'); }
				} ?></span>
			</div>
			<div id="txtHint5">
				<?php if (isset($reprise)) { $c=$rep_idc1; $t=$rep_idc2; $e=$rep_idc3; include('getevnmt.php'); } else 
				{ if (isset($_POST['evnmt']) AND isset($_POST['typecomp']) AND $probldata == 1)
					{ $c=$_POST['competition']; $t=$_POST['typecomp'];  $e=$_POST['evnmt']; include('getevnmt.php'); }
				} ?></div>
		</div>
		<br/>
		<div id="ActiviteHint">
			Activit&eacute; : <select name="activite" >
				<option value="none">S&eacute;lectionez une activit&eacute;...</option>
				<?php
				$reqimput = $bdd->query("SELECT * FROM rob_activite WHERE actif=1 ORDER BY code");
				while ($optimput = $reqimput->fetch())
				{
					if (isset($reprise)) { if ($rep_idac == $optimput['ID']) { $optsel = " selected"; } else { $optsel = ""; }
					} else {
					if (isset($_POST['activite']) AND $probldata == 1) { if ($_POST['activite'] == $optimput['ID']) {$optsel = " selected"; } else { $optsel = ""; }
					} else { $optsel = "";} }
					echo '<option value='.$optimput['ID'].$optsel.'>'.$optimput['Description'].'</option>';
				}
				$reqimput->closeCursor();
				?>
			</select>
		</div>
		<div id="f-descriptif">
			Description : <input type="text" size="70" name="info" 
			<?php
				if (isset($reprise)) { echo ' value="'.$rep_info.'" />';
				} else {
					if (isset($_POST['info']) AND $probldata == 1) { echo ' value="'.$_POST['info'].'" />';} else { echo ' value=""'; }
				}
			?>
		</div>
		<br/>
		<div id="f-valider">
			<?php
			echo '<input id="buttonval" type="submit" Value="Enregistrer" name="Valider" />';
			?> 
		</div>
	</div>
	</form>

	<?php
		if ($comptchg != 0)
		{
			echo '<div id="message">La combinaison client et/ou comp&eacute;tition n\'est pas bonne ou le type de frais/ activit&eacute; n\'a pas &eacute;t&eacute; s&eacute;lectionn&eacute;. Votre saisie n\'a pas &eacute;t&eacute; enregistr&eacute;e</div>';
		}
		if ($comptprblm == 1)
		{
			echo '<div id="message">Le client, la date, la comp&eacute;tition et/ou une combinaison de montants n\'a pas &eacute;t&eacute; saisie</div>';
		}
		if ($tauxprblm != 0)
		{
			echo '<div id="message">Le taux calcul&eacute; ('.$tauxMT.') &agrave; partir de votre saisie ne correspond pas &agrave; un taux officiel</div>';
		}
		if ($deadreach != 0)
		{
			echo '<div id="message">Cette p&eacute;riode de saisie est ferm&eacute;e.</div>';
		}
	?> 

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

	if (isset($_POST['toutfrais'])) { $titrestrict='Frais sur '.$fmonth.' '.$year; $txtsitu = 1;
	} else { $titrestrict='Frais en attente'; $txtsitu = 0; }
	?>
		
	<!-- =================== RESTITUTION: TABLEAU ================= -->
	<div id="coeur">
		<table id="tableoption2">
			<?php
			//MONTH
			echo '<tr><td style="text-align:left"><form action="frais.php" method="post"><select id="w_input_titrepartie" name="affmonth" />';
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
			echo '&nbsp;<select id="w_input_titrepartie" name="affyear">';
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
			
			//VISUALISER SES FRAIS
			echo '&nbsp;<input type="submit" id="buttonval" name="toutfrais" value="Visualiser tous les frais"></form></td>';
			
			echo '<td style="text-align:right">';
			if ($txtsitu == 1) {
				//RECHARGE LA PAGE
				echo '<form action="frais.php" method="post">';
				echo '<input id="w_input_90val" type="submit" Value="Voir les frais en attente" />';
				echo '</form>';
			} else {
				//CREER NOTE DE FRAIS
				echo '<form action="frais-pdf.php" method="post" target="_blank">';
				echo '<input type="hidden" name="matricule" value="'.$matricule.'" /><input id="w_input_90val" type="submit" Value="Cr&eacute;er la note des frais en attente" name="frais-pdf" onclick="return(confirm(\'En cr&eacute;ant votre PDF, vous allez g&eacute;n&eacute;rer un num&eacute;ro de frais pour tout vos frais en cours, non flagg&eacute;. \'))" />';
				echo '</form>'; }
			echo '</td></tr>'; ?>
		</table>
		
		<div id="sstitre"><?php echo $titrestrict; ?></div>
		<table id="tablerestit" class="table">
			<tr>
				<td id="t-containertit">Date</td><td id="t-containertit" >Nature</td><td id="t-containertit">Client/Projet/Mission/Cat&eacute;gorie</td><td id="t-containertit">Comp&eacute;tition/Type/&Eacute;v&eacute;nement</td><td id="t-containertit">Activit&eacute;</td><td id="t-containertit">Description</td><td id="t-containertit">Flag</td><td id="t-containertit" align="right">Montant TTC</td><td id="t-containertit" align="center" width="85px">Actions</td>
			</tr>
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
			if (isset($_POST['toutfrais'])) { $txtrestrict=" AND datejour >= '$startdate' AND datejour < '$enddate'"; 
			} else { $txtrestrict=" AND T1.validation < 2"; }
			$req = "SELECT T1.ID, T2.matricule, T1.datejour, 
				T3.Description, T4.Description, T5.Description, T6.Description, 
				T7.Description, T8.Description, T9.Description, 
				T1.info, T1.totalHT, T1.totalTVA, T1.totalTTC, T1.refact, T11.Description,
				T4.ID, T5.ID, T6.ID, T8.ID, T9.ID, T1.noteNum, T1.validation, T12.Description FROM rob_frais T1 
				INNER JOIN rob_user T2 ON T2.ID = T1.userID
				INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
				INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
				INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
				INNER JOIN rob_imputl4 T6 ON T6.ID = T1.imputIDl4 
				INNER JOIN rob_compl1 T7 ON T7.ID = T1.compID 
				INNER JOIN rob_compl2 T8 ON T8.ID = T1.compID2 
				INNER JOIN rob_compl3 T9 ON T9.ID = T1.compID3 
				INNER JOIN rob_nature2 T11 ON T11.ID = T1.nature2ID
				INNER JOIN rob_activite T12 ON T12.ID = T1.activID
				WHERE T1.userID='$pseudo'".$txtrestrict."
				ORDER BY T1.datejour, T11.Description, T3.code, T4.code";
			$reponsea = $bdd->query($req);
			$checkrep=$reponsea->rowCount();
			$i=1;
			$j=2;
			if ($checkrep != 0)
			{
				while ($donneea = $reponsea->fetch())
				{
					if ($donneea[8] == 1) { $j = "RoB_Green.png"; } else { $j = "RoB_Orange.png"; }
					if ($donneea[2] <= $deadline OR $donneea[21] != '' OR $donneea[22] == 2) { $l = " disabled"; $k="v"; } else { $l = ""; $k = "";}
					//date du jour
					echo '<tr><td id="t-container'.$i.$k.'" width="70px">'.date("d/m/Y", strtotime($donneea[2])).'</td>';
					echo '<td id="t-container'.$i.$k.'">';
					//refact
					if ($donneea[14] == 1)
					{ echo '<img src="images/RoB_Green.png" title="Refacturable" />&nbsp;'; } else
					{ echo '<img src="images/RoB_Red.png" title="Non refacturable" />&nbsp;'; }
					//nature2
					echo $donneea[15].'</td>';
					//clients
					echo '<td id="t-container'.$i.$k.'">'.$donneea[3];
						if ($donneea[16] != 0) { echo '<br/>&harr;'.$donneea[4];
							if ($donneea[17] != 0) { echo '<br/>&nbsp;&harr;'.$donneea[5];
								if ($donneea[18] != 0) { echo '<BR/>&nbsp;&nbsp;&harr;'.$donneea[6]; } } }
					echo '</td>';
					//Compétition
					echo '<td id="t-container'.$i.$k.'">'.$donneea[7];
						if ($donneea[19] != 0) { echo '<br/>&harr;'.$donneea[8];
							if ($donneea[20] != 0) { echo '<br/>&nbsp;&harr;'.$donneea[9].'</td>'; } }
					echo '</td>';
					//activité
					echo '<td id="t-container'.$i.$k.'">'.$donneea[23].'</td>';
					//info
					echo '<td id="t-container'.$i.$k.'">'.$donneea[10].'</td>';
					//flag
					echo '<td id="t-container'.$i.$k.'">'.$donneea[21].'</td>';
					echo '<form action="frais.php" method="post">';
					echo '<td id="t-container'.$i.$k.'" align="right">';
					//valeurs
					//echo '<td id="t-container'.$i.$k.'"><input type="text" size="5" value="'.$donneea[11].'" name="modht" />HT<br/>';
						echo '<input type="hidden" value="'.$pseudo.'" name="affcoll" />';
						echo '<input type="hidden" value="'.$year.'" name="affyear" />';
						echo '<input type="hidden" value="'.$month.'" name="affmonth" />';
						echo '<input type="hidden" value="'.$donneea[0].'" name="modid" />';
						echo '<input type="hidden" value="'.$donneea[2].'" name="moddate" />';
						echo '<input type="hidden" value="'.$donneea[7].'" name="oldval" />';
						echo '<input type="hidden" value="'.$donneea[9].'" name="imputID2" />';
						echo '<input type="hidden" value="'.$donneea[21].'" name="flag" />';
					//echo '<input type="text" size="5" value="'.$donneea[12].'" name="mdtva" />TVA<br/>';
					//echo '<input type="text" size="5" value="'.$donneea[13].'" name="modttc" />TTC</td>';
					echo $donneea[13].'</td>';
					//status
					if ($donneea[2] <= $deadline OR $donneea[22] == 2)
					{
						echo '<td id="t-container'.$i.$k.'">';
							echo '&nbsp;<input id="btRep" type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise" />';
						echo '</td></form></tr>';
					}
					else
					{
						if ($donneea[21] != '')
						{
							echo '<td id="t-container'.$i.$k.'">';
								echo '&nbsp;<input id="btRep" type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise" />';
								echo '&nbsp;<input id="btMod" type="submit" Value="V" title="D&eacute;v&eacute;rouiller cette note de frais" name="deverr" onclick="return(confirm(\'Etes-vous sur de vouloir d&eacute;v&eacute;rouiller l\int&eacute;gralit&eacute; de cette note de frais?\'))" />';
							echo '</td></form></tr>';
						}
						else
						{
							//echo '<td id="t-container'.$i.$k.'">&nbsp;<input id="w_input_90val" type="submit" Value="Mod." name="Mod" onclick="return(confirm(\'Etes-vous sur de vouloir modifier les temps de cette ligne?\'))" /><br/>';
							echo '<td id="t-container'.$i.$k.'">';
								echo '&nbsp;<input id="btRep" type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise" />';
								echo '&nbsp;<input id="btMod" type="submit" Value="M" title="Modifier les informations de cette ligne" name="Modif" onclick="return(confirm(\'Les donn&eacute;es seront reprises dans le formulaire et cette ligne sera supprim&eacute;e. &Ecirc;tes vous s&ucirc;r?\'))" />';
								echo '&nbsp;<input id="btSuppr" type="submit" Value="S" title="Supprimer la ligne" name="Suppr" onclick="return(confirm(\'Etes-vous sur de vouloir supprimer cette entree?\'))" />';
							echo '</td></form></tr>';
						}
					}
					if ($i == 1) { $i = 2; } else { $i = 1; }
				}
			}
			$reponsea->closeCursor();
			
			//<!-- =================== RESTITUTION: TABLEAU SOUS TOTAL ================= -->
			$req = "SELECT sum(T1.totalHT), sum(T1.totalTVA), sum(T1.totalTTC) FROM rob_frais T1
				WHERE T1.userID='$pseudo'".$txtrestrict;
			$reponse = $bdd->query($req);
			$checkrep = $reponse->rowCount();
			if ($checkrep != 0)
			{
				while ($donnee = $reponse->fetch())
				{
					echo '<tr><td id="t-containertit" align="right" colspan="7">Total</td>';
					echo '<td id="t-containertit" align="right">';
					//echo $donnee[0].' HT<br/>';
					//echo $donnee[1].' TVA<br/>';
					echo $donnee[2];
					echo '</td><td id="t-containertit">&nbsp;</td></tr>';
				}
			}
			$reponse->closeCursor();
			?>
			
		</table>

	</div>
		
<?php include("footer.php"); 
}
else
{
	header("location:index.php");
}
?>