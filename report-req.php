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
	AND isset($_GET['v3'])
	AND isset($_GET['v4'])
	AND isset($_GET['v5'])
	AND isset($_GET['c1']) 
	AND isset($_GET['c2'])
	AND isset($_GET['r1'])
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
	$v3 = intval($_GET['v3']);
	$v4 = intval($_GET['v4']);
	$v5 = intval($_GET['v5']);
	$c1 = intval($_GET['c1']);
	$c2 = intval($_GET['c2']);
	$r1 = intval($_GET['r1']);
}
include("appel_db.php");
?>

<!-- ================= VARIABLES =============== -->
<?php
$total=0;

//Récupération des variables
if ($_SESSION['id_lev_jrl'] == 6)
{
	$flt_mat = 'userID > -1';
}
else
{
	$flt_mat = 'userID='.$_SESSION['ID'];
}
if (isset($d1) AND $d1 != 99999) { $flt_month = ' AND MONTH(dateTransac) = '.$d1; } else { $flt_month = ''; }
if (isset($d2) AND $d2 != 99999) { $flt_year = ' AND YEAR(dateTransac) = '.$d2; } else { $flt_year = ''; }
if (isset($d3) AND $d3 != 0) { $flt_deb = ' AND dateTransac >= '.$d3; } else { $flt_deb = ''; }
if (isset($d4) AND $d4 != 0) { $flt_fin = ' AND dateTransac <= '.$d4; } else { $flt_fin = ''; }
if (isset($p1) AND $p1 != 99999) { $flt_clt = ' AND imputID1 = '.$p1; } else { $flt_clt = ''; }
if (isset($p2) AND $p2 != 99999) { $flt_prj = ' AND imputID2 = '.$p2; } else { $flt_prj = ''; }
if (isset($v1) AND $v1 != 99999) { $flt_act = ' AND activID = '.$v1; } else { $flt_act = ''; }
if (isset($v2) AND $v2 != 99999) { $flt_phase = ' AND Phase = '.$v2; } else { $flt_phase = ''; }
if (isset($v3) AND $v3 != 99999) { $flt_class = ' AND classID = '.$v3; } else { $flt_class = ''; }
if (isset($v4) AND $v4 != 99999) { $flt_paie = ' AND paiement = '.$v4; } else { $flt_paie = ''; }
if (isset($v4) AND $v5 != 99999) { $flt_frs = ' AND frsID = '.$v5; } else { $flt_frs = ''; }
if (isset($c1) AND $c1 != 99999) { $flt_comp = ' AND compID1 = '.$c1; } else { $flt_comp = ''; }
if (isset($c2) AND $c2 != 99999) { $flt_type = ' AND compID2 = '.$c2; } else { $flt_type = ''; }
if (isset($r1)) { } else { $r1 = 1; }

$req = "SELECT * FROM rob_reporting WHERE ID = ".$r1;
$reqimput = $bdd->query($req);
$optimput = $reqimput->fetch();
$tabcol1 = $optimput['tabCol1'];
$tabcol2 = $optimput['tabCol2'];
$selecttabtmp = $optimput['SELECTtmpTBL'];
$selecttabfull = $optimput['SELECTfullTBL'];
$reqimput->closeCursor();
?>

<input type="hidden" id="page" name="page" value=2 />
<!-- ================= REQUETE =============== -->
<?php
$reqB = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_full AS (SELECT ".$selecttabtmp."
	FROM rob_journal TMP1
	INNER JOIN rob_class TC1 ON TMP1.classID = TC1.ID 
	WHERE ".$flt_mat.$flt_month.$flt_year.$flt_deb.$flt_fin
	.$flt_clt.$flt_prj.$flt_act.$flt_phase.$flt_class.$flt_paie.$flt_frs.$flt_comp.$flt_type.")";
$bdd->query($reqB);
	
$req1 = "SELECT ".$selecttabfull." FROM tmp_full T1 
	INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID1 
	INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputID2 
	INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputID3 
	GROUP BY imput1, imput2, imput3 WITH ROLLUP";
$reponsea = $bdd->query($req1);
	
$req2 = "DROP TEMPORARY TABLE tmp_full";
$bdd->query($req2);
?>

<!-- ================= RESTITUTION =============== -->
<table id="tablerestit">
	<?php
	echo '<tr>'.$tabcol1.'</tr>';
	$checkrep=$reponsea->rowCount();
	$i=1;
	$j=2;
	if ($checkrep != 0)
	{
		while ($donneea = $reponsea->fetch())
		{
			if ($donneea['imput1'] == "") {$j = "TOTAL GENERAL"; $k = ""; $l = ""; $i = "tt"; } else {
			if ($donneea['imput2'] == "") {$j = ""; $k = "TOTAL "; $l = ""; $i = "st"; } else {
			if ($donneea['imput3'] == "") {$j = ""; $k = ""; $l = "Total "; $i = "ut";} else {$j = ""; $k = ""; $l = "";} } }
			//clients
			echo '<tr><td id="t-container'.$i.'">'.$j.$k.utf8_encode($donneea['imput1']).'</td>';
			//projets
			echo '<td id="t-container'.$i.'">'.$l.utf8_encode($donneea['imput2']).'</td>';
			//missions
			echo '<td id="t-container'.$i.'">'.utf8_encode($donneea['imput3']).'</td>';
			//recette
			echo '<td id="t-container'.$i.'" align="right">'.number_format($donneea['recette'],2,",",".").'</td>';
			//depense
			echo '<td id="t-container'.$i.'" align="right">'.number_format($donneea['depense'],2,",",".").'</td>';
			//marge
			echo '<td id="t-container'.$i.'" align="right">'.number_format($donneea['marge'],2,",",".").'</td>';
			//marge
			echo '<td id="t-container'.$i.'" align="right">'.number_format($donneea['margepct'],2,",",".").'%</td></tr>';
			if ($i == 1) { $i = 2; } else { $i = 1; }
		}
	}
	$reponsea->closeCursor();
	?>
	
</table>
