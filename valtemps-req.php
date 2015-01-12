<?php
if(
	isset($_GET['d1']) 
	AND isset($_GET['d2']) 
	AND isset($_GET['d3']) 
	AND isset($_GET['d4']) 
	AND isset($_GET['p1']) 
	AND isset($_GET['p2'])
	AND isset($_GET['v1'])
	AND isset($_GET['v2'])
	) 
{
	session_start();
	$d1 = intval($_GET['d1']);
	$d2 = intval($_GET['d2']);
	$d3 = intval($_GET['d3']);
	$d4 = intval($_GET['d4']);
	$p1 = intval($_GET['p1']);
	$p2 = intval($_GET['p2']);
	$v1 = intval($_GET['v1']);
	$v2 = intval($_GET['v2']);
}
include("appel_db.php");
?>

<!-- ================= VARIABLES =============== -->
<?php
$total=0;

//Récupération des variables
if (isset($d1) AND $d1 != 99999) { $flt_month = ' AND MONTH(T1.datejour) = '.$d1; } else { $flt_month = ''; }
if (isset($d2) AND $d2 != 99999) { $flt_year = ' AND YEAR(T1.datejour) = '.$d2; } else { $flt_year = ''; }
if (isset($d3) AND $d3 != 0) { $flt_deb = ' AND T1.datejour >= '.$d3; } else { $flt_deb = ''; }
if (isset($d4) AND $d4 != 0) { $flt_fin = ' AND T1.datejour <= '.$d4; } else { $flt_fin = ''; }
if (isset($p1) AND $p1 != 99999) { $flt_clt = ' AND T1.imputID = '.$p1; } else { $flt_clt = ''; }
if (isset($p2) AND $p2 != 99999) { $flt_prj = ' AND T1.imputID2 = '.$p2; } else { $flt_prj = ''; }
if (isset($v1) AND $v1 != 99999) { $flt_val = ' AND T1.validation = '.$v1; } else { if (isset($v1) AND $v1 == 99999) { $flt_val = ''; } else { $flt_val = ' AND T1.validation = 0'; } }
if (isset($v2) AND $v2 != 99999) { $flt_col = ' AND T1.userID = '.$v2; } else { $flt_col = ''; }
if ($_SESSION['id_lev_tms'] == 6) { $fltuser = ''; } else { if ($_SESSION['id_lev_tms'] == 4) { $fltuser = ' AND T7.id_hier ='.$_SESSION['ID']; } }


// ================= REQUETE1 =============== 
$req = "SELECT T2.matricule trig, MONTH(T1.datejour) mois, YEAR(T1.datejour) annee, SUM(T1.valeur) jour,
		T3.description client, T4.description projet, T5.description mission, T1.validation validation,
		T1.userID userID, T1.imputID clientid, T1.imputIDl2 projetid, T1.imputIDl3 missionid
	FROM rob_temps T1 
	INNER JOIN rob_user T2 ON T2.ID = T1.userID
	INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
	INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
	INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
	INNER JOIN rob_user_rights T7 ON T7.ID = T1.userID 
	WHERE T1.imputIDl2 NOT IN (1,2,3,4,5,6,7,8,9) ".$flt_val.$fltuser.$flt_month.$flt_year.$flt_deb.$flt_fin
	.$flt_clt.$flt_prj.$flt_col." 
	GROUP BY T2.matricule, T3.description, T4.description, T5.description, T1.validation, T1.userID, T1.imputID, T1.imputIDl2, T1.imputIDl3 
	ORDER BY T1.datejour DESC LIMIT 30";
$result = $bdd->query($req);
?>


<!-- ================= RESTITUTION1 =============== -->
<section class="container section-container" id="historique-temps">
	<div class="section-title">
		<h1>Validation des temps</h1>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Trigramme</th>
				<th>Date</th>
				<th>Client</th>
				<th>Projet</th>
				<th>Mission</th>
				<th>Dur&eacute;e</th>
				<th>Valider</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i=1;
		while ($donnee = $result->fetch())
		{
		?>
			<tr>
				<td><?php echo $donnee['trig'];?></td>
				<td><?php if($donnee['mois'] < 10) { echo "0"; } echo $donnee['mois'].'.'.$donnee['annee'];?></td>
				<td><?php echo $donnee['client'];?></td>
				<td><?php echo $donnee['projet'];?></td>
				<td><?php echo $donnee['mission'];?></td>
				<td><?php echo $donnee['jour'];?></td>
				<td>
					<form action="valtemps.php" method="post">
						<input type="hidden" value="<?php echo $donnee['clientid'];?>" name="vimputIDl1" />
						<input type="hidden" value="<?php echo $donnee['projetid'];?>" name="vimputIDl2" />
						<input type="hidden" value="<?php echo $donnee['missionid'];?>" name="vimputIDl3" />
						<input type="hidden" value="<?php echo $donnee['userID'];?>" name="vuserID" />
						<input type="hidden" value="<?php echo $donnee['mois'];?>" name="vdatejour" />
						<?php if ($donnee['validation'] == 0) { 
							echo '<input type="hidden" value=1 name="valtps" />';
							echo '<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Valider"><i class="fa fa-toggle-on"></i></button>'; } else {
							echo '<input type="hidden" value=0 name="valtps" />';
							echo '<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="Rejeter"><i class="fa fa-toggle-on"></i></button>'; }
						?>
					</form>
				</td>
			</tr>

		<?php
			if ($i == 1) { $i = 2; } else { $i = 1; }
		}
		?>
		</tbody>
	</table>
</section>
<?php
$result->closeCursor();

// ================= REQUETE2 =============== 
$req = "SELECT T2.ID userID, T2.matricule trig, T1.datejour date, T1.info info, T1.valeur jour, T1.ID ID, T1.validation validation,
		T6.Description activite, T3.description client, T4.description projet, T5.description mission, T1.recup recup 
	FROM rob_temps T1 
	INNER JOIN rob_user T2 ON T2.ID = T1.userID
	INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
	INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
	INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
	INNER JOIN rob_activite T6 ON T6.ID = T1.activID 
	INNER JOIN rob_user_rights T7 ON T7.ID = T1.userID 
	WHERE recup <> 0".$fltuser.$flt_month.$flt_year.$flt_deb.$flt_fin
	.$flt_clt.$flt_prj.$flt_col." 
	ORDER BY T1.userID, T3.description, T4.description, T5.description, T1.datejour DESC  LIMIT 30";
$result = $bdd->query($req);
?>

<!-- ================= RESTITUTION2 =============== -->
<section class="container section-container" id="historique-temps">
	<div class="section-title">
		<h1>R&eacute;cup&eacute;ration en cours</h1>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th></th>
				<th>Trigramme</th>
				<th>Date</th>
				<th>Activit&eacute;</th>
				<th>Client</th>
				<th>Projet</th>
				<th>Mission</th>
				<th>Description</th>
				<th>Dur&eacute;e</th>
				<th>Suppr.</th>
			</tr>
		</thead>
		</tbody>
			<?php
			$i=1;
			while ($donnee = $result->fetch())
			{
				if (strtotime(date("Y-m-d")) - strtotime($donnee['date']) > 1814400) {$val="no-validate"; $highlight="highlight";} else { $val="validate"; $highlight="no-highlight";}
			?>
				<tr class="tr-<?php echo $highlight; ?>">
					<td><?php echo '<i class="fa fa-circle fa-circle-'.$val.'"></i>';?></td>
					<td><?php echo $donnee['trig'];?></td>
					<td><?php echo date ("d/m/Y", strtotime($donnee['date']));?></td>
					<td><?php echo $donnee['activite'];?></td>
					<td><?php echo $donnee['client'];?></td>
					<td><?php echo $donnee['projet'];?></td>
					<td><?php echo $donnee['mission'];?></td>
					<td><?php echo $donnee['info'];?></td>
					<td><?php echo $donnee['jour'];?></td>
					<td>
					<form id="ajax-form" class="autosubmit" method="POST" action="./valrecup-upd.php">
						<input type="checkbox" name="recup" value="0" title="cocher pour supprimer la r&eacute;cup&eacute;ration" />
						<input id="where" type="hidden" name="ID" value="<?php echo $donnee['ID'] ?>" />
					</form>
					</td>
				</tr>

			<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			?>
		</tbody>
	</table>
</section>
<?php
$result->closeCursor();

// ================= REQUETE3 =============== 
$req = "SELECT T2.matricule trig, T1.datejour datejour, T1.valeur jour,
		T3.description client, T4.description projet, T5.description mission, T1.validation validation, T1.ID ID
	FROM rob_temps T1 
	INNER JOIN rob_user T2 ON T2.ID = T1.userID
	INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
	INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
	INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
	INNER JOIN rob_user_rights T7 ON T7.ID = T1.userID 
	WHERE T1.imputIDl2 IN (1,2,3,4,5,6,8,9) ".$flt_val.$fltuser.$flt_month.$flt_year.$flt_deb.$flt_fin.$flt_col." 
	ORDER BY T2.matricule, T1.datejour DESC, T3.description, T4.description, T5.description, T1.validation LIMIT 30";
$result = $bdd->query($req);
?>


<!-- ================= RESTITUTION3 =============== -->
<section class="container section-container" id="historique-temps">
	<div class="section-title">
		<h1>Validation des abscences (hors r&eacute;cup&eacute;ration)</h1>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Trigramme</th>
				<th>Date</th>
				<th>Client</th>
				<th>Projet</th>
				<th>Mission</th>
				<th>Dur&eacute;e</th>
				<th>Valider</th>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($donnee = $result->fetch())
		{
		?>
			<tr>
				<td><?php echo $donnee['trig'];?></td>
				<td><?php echo $donnee['datejour'];?></td>
				<td><?php echo $donnee['client'];?></td>
				<td><?php echo $donnee['projet'];?></td>
				<td><?php echo $donnee['mission'];?></td>
				<td><?php echo $donnee['jour'];?></td>
				<td>
					<form id="ajax-form" class="autosubmit" method="POST" action="./valtemps-upd.php">
						<input type="checkbox" name="validation" value="1" />
						<input id="where" type="hidden" name="ID" value="<?php echo $donnee['ID'] ?>" />
					</form>
				</td>
			</tr>

		<?php
		}
		?>
		</tbody>
	</table>
</section>

<?php
$result->closeCursor();
?>
