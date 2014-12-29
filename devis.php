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
		if (isset($_POST['client']) AND isset($_POST['dateTransac']) AND isset($_POST['competition']) AND isset($_POST['nature1']))
		{
			if ($_POST['client'] != "none" AND $_POST['nature1'] != "none")
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
				
				//Check sur projet et mission
				if ($_POST['devisNum'] == "none")
				{
					$devisNum = 'D'.$_POST['client'].'-'.$_POST['projet'].'-'.date('ymd',mktime(0,0,0,substr($_POST['dateTransac'],3,2),substr($_POST['dateTransac'],0,2),substr($_POST['dateTransac'],6,4)));
				} else {
					$devisNum = $_POST['devisNum'];
				}
				
				//insertion dans la table
				if ($comptchg == 0)
				{
					$userID = $_SESSION['ID'];
					$info = $_POST['info'];
					$info = str_replace("'","\'",$info);
					$dateTransac = date('Y-m-d',mktime(0,0,0,substr($_POST['dateTransac'],3,2),substr($_POST['dateTransac'],0,2),substr($_POST['dateTransac'],6,4)));
					$nature1 = $_POST['nature1'];
					$client = $_POST['client'];
					$projet = $_POST['projet'];
					if (isset($_POST['mission'])) { $mission = $_POST['mission']; } else { $mission = 0; }
					if (isset($_POST['categorie'])) { $categorie = $_POST['categorie']; } else { $categorie = 0; }
					$competition = $_POST['competition'];
					if (isset($_POST['typecomp'])) { $typecomp = $_POST['typecomp']; } else { $typecomp = 0; }
					if (isset($_POST['evnmt'])) { $evnmt = $_POST['evnmt']; } else { $evnmt = 0; }
					$devisVersion = 'V01';
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

	<section class="container section-container" id="saisie-frais">
		<div class="section-title">
			<h1>Cr&eacute;er une ligne de devis</h1>
		</div>
		<form action="devis.php" method="post" id="form-saisie-frais">
			<div>
				<div>
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
						echo '" />';
					?> 
					<select class="form-control form-control-small" name="devisNum" />
						<option value="none">Cr&eacute;er nouveau Num&eacute;ro de devis</option>
						<?php
						$reqimput = $bdd->query("SELECT DISTINCT devisNum FROM rob_devis WHERE actif = 1 ORDER BY devisNum DESC");
						while ($optimput = $reqimput->fetch())
						{
							if (isset($reprise)) { if ($rep_devisNum == $optimput['devisNum']) { $optsel = " selected"; } else { $optsel = ""; }
							} else {
							if (isset($_POST['devisNum']) AND $probldata == 1) { if ($_POST['devisNum'] == $optimput['devisNum']) {$optsel = " selected"; } else { $optsel = ""; }
							} else { $optsel = "";} }
							echo '<option value='.$optimput['devisNum'].$optsel.'>'.$optimput['devisNum'].'</option>';
						}
						$reqimput->closeCursor();
						?>
					</select>
					<input type="hidden" id="ma_page" value="0" />
				</div>
				<div>
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
					<input class="form-control form-control-small" type="text" size="10" id="frsCtUnit" name="frsCtUnit" onchange="frscalc()" title="UnitHT" placeholder="UnitHT"<?php if (isset($idenr)) { echo ' value="'.$runitaire.'"'; } ?> /> x 
					<input class="form-control form-control-small" type="text" size="5" id="frsQty" name="frsQty" onchange="frscalc()" title="Qt" placeholder="Qt"<?php if (isset($idenr)) { echo ' value="'.$rquantite.'"'; } else { echo ' value="1"'; } ?> /> = 
					<input class="form-control form-control-small" type="text" size="10" id="frstot" name="frstot" title="TotalHT" placeholder="TotalHT"<?php if (isset($idenr)) { echo ' value="'.$rtotal.'"'; } ?> disabled />
						<input type="hidden" id="frsCtTotHT" name="frsCtTotHT" readonly="readonly"<?php if (isset($idenr)) { echo ' value="'.$rtotal.'"'; } ?> />
				</div>
				<div>
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
				</div>
				<div id="txtHint2">
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
				</div>
				<div>
					<select class="form-control form-control-small" name="competition" id="competition" onchange="showType(this.value)">
						<option value="00">Comp&eacute;tition</option>
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
					</select>
					<span id="txtHint4">
						<?php if (isset($reprise)) { $p=$rep_idc1; $m=$rep_idc2; include('gettype.php'); } else 
						{ if (isset($_POST['typecomp']) AND $probldata == 1)
							{ $p=$_POST['competition']; $m=$_POST['typecomp']; include('gettype.php'); }
						} ?>
					</span>
				</div>
				<div id="txtHint5">
					<?php if (isset($reprise)) { $c=$rep_idc1; $t=$rep_idc2; $e=$rep_idc3; include('getevnmt.php'); } else 
					{ if (isset($_POST['evnmt']) AND isset($_POST['typecomp']) AND $probldata == 1)
						{ $c=$_POST['competition']; $t=$_POST['typecomp'];  $e=$_POST['evnmt']; include('getevnmt.php'); }
					} ?>
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

	if (isset($_POST['toutfrais'])) { $titrestrict='Frais sur '.$fmonth.' '.$year; $txtsitu = 1;
	} else { $titrestrict='Frais en attente'; $txtsitu = 0; }
	?>
		
	<!-- =================== RESTITUTION: TABLEAU ================= -->
	<section class="container section-container" id="historique-frais">
		<div class="section-title">
			<h1>Historique de mes devis</h1>
		</div>
		<div class="frais-filter">
			<?php
			//MONTH
			echo '<form action="devis.php" method="post"><select class="form-control" id="w_input_titrepartie" name="affmonth" />';
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
			echo '<select id="w_input_titrepartie" class="form-control" name="affyear">';
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
			echo '<form action="devis-pdf.php" method="post">';
				echo '<select class="form-control" name="devisNumRest" />';
					$reqimput = $bdd->query("SELECT DISTINCT devisNum FROM rob_devis WHERE actif = 1 ORDER BY devisNum DESC");
					while ($optimput = $reqimput->fetch())
					{
						echo '<option value='.$optimput['devisNum'].$optsel.'>'.$optimput['devisNum'].'</option>';
					}
					$reqimput->closeCursor();
				echo '</select>';
				echo '<select class="form-control" name="devisVersRest" />';
					$reqimput = $bdd->query("SELECT DISTINCT devisVers FROM rob_devis WHERE actif = 1 ORDER BY devisVers DESC");
					while ($optimput = $reqimput->fetch())
					{
						echo '<option value='.$optimput['devisVers'].$optsel.'>'.$optimput['devisVers'].'</option>';
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
					<th id="t-containertit">Date</th>
					<th id="t-containertit" >Nature</th>
					<th id="t-containertit">Client/Projet/Mission/Cat&eacute;gorie</th>
					<th id="t-containertit">Comp&eacute;tition/Type/&Eacute;v&eacute;nement</th>
					<th id="t-containertit">Activit&eacute;</th>
					<th id="t-containertit">Description</th>
					<th id="t-containertit">Flag</th>
					<th id="t-containertit" align="right">Montant TTC</th>
					<th id="t-containertit" align="center" width="85px">Actions</th>
				</tr>
			</thead>
			<tbody>

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