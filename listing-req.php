<?php
if(
	isset($_GET['Action']) 
	AND isset($_GET['d1']) 
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
	) 
{
	session_start();
	$action = intval($_GET['Action']);
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
} else { $action = 0; }
include("appel_db.php");
?>

<!-- ================= VARIABLES =============== -->
<?php
//Deadline
$dead = $bdd->query("SELECT deadline FROM rob_verrouille WHERE ID=3");
$deadlinetab = $dead->fetch();
$deadline = $deadlinetab[0];
$dead->closeCursor();

$total=0;

//Récupération des variables
$matricule = $_SESSION['ID'];
$flt_limit = ' LIMIT 20';
$flt_order = ' ORDER BY T1.dateFact';
$flt_asc = ' DESC';
if (isset($d1) AND $d1 != 99999) { $flt_month = ' AND MONTH(T1.dateTransac) = '.$d1; } else { $flt_month = ''; }
if (isset($d2) AND $d2 != 99999) { $flt_year = ' AND YEAR(T1.dateTransac) = '.$d2; } else { $flt_year = ''; }
if (isset($d3) AND $d3 != 0) { $flt_deb = ' AND T1.dateTransac >= '.$d3; } else { $flt_deb = ''; }
if (isset($d4) AND $d4 != 0) { $flt_fin = ' AND T1.dateTransac <= '.$d4; } else { $flt_fin = ''; }
if (isset($p1) AND $p1 != 99999) { $flt_clt = ' AND T1.imputID1 = '.$p1; } else { $flt_clt = ''; }
if (isset($p2) AND $p2 != 99999) { $flt_prj = ' AND T1.imputID2 = '.$p2; } else { $flt_prj = ''; }
if (isset($v1) AND $v1 != 99999) { $flt_act = ' AND T1.activID = '.$v1; } else { $flt_act = ''; }
if (isset($v2) AND $v2 != 99999) { $flt_phase = ' AND T1.Phase = '.$v2; } else { $flt_phase = ''; }
if (isset($v3) AND $v3 != 99999) { $flt_class = ' AND T1.classID = '.$v3; } else { $flt_class = ''; }
if (isset($v4) AND $v4 != 99999) { $flt_paie = ' AND T1.paiement = '.$v4; } else { $flt_paie = ''; }
if (isset($v4) AND $v5 != 99999) { $flt_frs = ' AND T1.frsID = '.$v5; } else { $flt_frs = ''; }
if (isset($c1) AND $c1 != 99999) { $flt_comp = ' AND T1.compID1 = '.$c1; } else { $flt_comp = ''; }
if (isset($c2) AND $c2 != 99999) { $flt_type = ' AND T1.compID2 = '.$c2; } else { $flt_type = ''; }
?>

<!-- ================= REQUETE =============== -->
<?php
if ($action != 0)
{
	$bdd->query("DELETE FROM rob_journal WHERE ID='$action' LIMIT 1");
}

$req = "SELECT T1.ID ID, T10.Phase Phase, T1.classID classID, T1.userID user, T1.dateTransac dateTransac, 
	T1.nature2ID nature2ID, T13.Description nature1, T11.Description nature2, 
	T1.profilID profilID, T1.collaborateurID collaborateurID, T14.Description profil, T15.nom nom, T15.prenom prenom,
	T3.Description imput1, T4.Description imput2, T5.Description imput3, T6.Description imput4, 
	T7.Description comp1, T8.Description comp2, T9.Description comp3, 
	T1.descriptif descriptif, T1.unitaire unitaire, T1.quantite quantite, T1.total total, T1.paiement paiement,
	T1.imputID2 imputID2, T1.imputID3 imputID3, T1.imputID4 imputID4, 
	T1.compID2 compID2, T1.compID3 compID3, T16.factor factor, T16.sens sens FROM rob_journal T1 
	INNER JOIN rob_user T2 ON T2.ID = T1.userID
	INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID1 
	INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputID2 
	INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputID3 
	INNER JOIN rob_imputl4 T6 ON T6.ID = T1.imputID4 
	INNER JOIN rob_compl1 T7 ON T7.ID = T1.compID1 
	INNER JOIN rob_compl2 T8 ON T8.ID = T1.compID2 
	INNER JOIN rob_compl3 T9 ON T9.ID = T1.compID3 
	INNER JOIN rob_phase T10 ON T10.ID = T1.Phase
	INNER JOIN rob_nature1 T13 ON T13.ID = T1.nature1ID
	INNER JOIN rob_nature2 T11 ON T11.ID = T1.nature2ID
	INNER JOIN rob_profil T14 ON T14.ID = T1.profilID
	LEFT JOIN rob_user T15 ON T15.ID = T1.collaborateurID
	INNER JOIN rob_class T16 ON T16.ID = T1.classID
	WHERE T1.userID='$matricule'".$flt_month.$flt_year.$flt_deb.$flt_fin
	.$flt_clt.$flt_prj.$flt_act.$flt_phase.$flt_class.$flt_paie.$flt_frs.$flt_comp.$flt_type
	.$flt_order.$flt_asc.$flt_limit;
	
	//echo $req;
?>

<input type="hidden" id="page" name="page" value=1 />
<!-- ================= RESTITUTION =============== -->
<table id="tablerestit" class="table table-striped">
	<thead>
		<tr>
			<th id="t-containertit">Phase</th>
			<th id="t-containertit">Date</th>
			<th id="t-containertit" >Nature</th>
			<th id="t-containertit">Client/Projet/Mission/Cat&eacute;gorie</th>
			<th id="t-containertit">Comp&eacute;tition/Type/&Eacute;v&eacute;nement</th>
			<th id="t-containertit">Description</th>
			<!--<th id="t-containertit" align="right">Unit</th>-->
			<!--<th id="t-containertit" align="right">Qt&eacute;</th>-->
			<th id="t-containertit" align="right">Total</th>
			<th id="t-containertit" align="center">Paiement</th>
			<th id="t-containertit" colspan="2" align="center" width="85px">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$reponsea = $bdd->query($req);
		$checkrep=$reponsea->rowCount();
		$i=1;
		$j=2;
		if ($checkrep != 0)
		{
			while ($donneea = $reponsea->fetch())
			{
				if ($donneea['classID'] == 1) { $k = "s"; } else { if ($donneea['classID'] == 2) { $k = "r"; } else { $k = ""; } }
				//$k = "";
				//Phase
				echo '<tr><td id="t-container'.$i.'">'.utf8_encode($donneea['Phase']).'</td>';
				//date du jour
				echo '<td id="t-container'.$i.'" width="70px">'.date("d/m/Y", strtotime($donneea['dateTransac'])).'</td>';
				//nature
				echo '<td id="t-container'.$i.'">'.utf8_encode($donneea['nature1']);
					if ($donneea['nature2ID'] != 0) { echo '<br/>&harr;'.utf8_encode($donneea['nature2']); }
						//if ($donneea['profilID'] != 0) { echo '<br/>&nbsp;&harr;'.utf8_encode($donneea['profil']);
						//	if ($donneea['collaborateurID'] != 0) { echo '<BR/>&nbsp;&nbsp;&harr;'.utf8_encode($donneea['nom']).' '.substr(utf8_encode($donneea[27]),0,1).'.'; } } }
				echo '</td>';
				//clients
				echo '<td id="t-container'.$i.'">'.utf8_encode($donneea['imput1']);
					if ($donneea['imputID2'] != 0) { echo '<br/>&harr;'.utf8_encode($donneea['imput2']); 
						if ($donneea['imputID3'] != 0) { echo '<br/>&nbsp;&harr;'.utf8_encode($donneea['imput3']); } }
						//	if ($donneea['imputID4'] != 0) { echo '<BR/>&nbsp;&nbsp;&harr;'.utf8_encode($donneea['imput4']); } } }
				echo '</td>';
				//Compétition
				echo '<td id="t-container'.$i.'">'.utf8_encode($donneea['comp1']);
					if ($donneea['compID2'] != 0) { echo '<br/>&harr;'.utf8_encode($donneea['comp2']);
						if ($donneea['compID3'] != 0) { echo '<br/>&nbsp;&harr;'.utf8_encode($donneea['comp3']); } }
				echo '</td>';
				//info
				echo '<td id="t-container'.$i.'">'.utf8_encode($donneea['descriptif']).'</td>';
				//popup: unitaire x quantité (débit/crédit)
				echo '<td id="t-container'.$i.$k.'" align="right"><span style="cursor: help;" title="'.number_format($donneea['unitaire'],2,",",".").' x '.number_format($donneea['quantite'],0,",",".").' ('.$donneea['sens'].')" </span>';
				//total
				echo number_format($donneea['total'],2,",",".").'</td>';
				$total=$total+$donneea['total']*$donneea['factor'];
				//paiement
				if ($donneea['paiement'] == 1) { echo '<td id="t-container'.$i.'" align="center">oui</td>'; } else { echo '<td id="t-container'.$i.'" align="center">non</td>'; }
				//status
				if ($donneea['dateTransac'] <= $deadline)
				{
					echo '<td id="t-container'.$i.$k.'" width="56px">';
						echo '<form action="journal.php" method="post">';
						echo '<input type="hidden" value="'.$donneea['ID'].'" name="modid" />';
						echo '&nbsp;<input id="btRep" type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise" />';
					echo '</form></td><td id="t-container'.$i.$k.'" width="28px">&nbp;</td></tr>';
				}
				else
				{
					echo '<td id="t-container'.$i.$k.'" width="56px">';
						echo '<form action="journal.php" method="post">';
						echo '<input type="hidden" value="'.$donneea['ID'].'" name="modid" />';
						echo '&nbsp;<input id="btRep" type="submit" Value="D" title="Dupliquer les informations de cette ligne" name="Reprise" />';
						echo '&nbsp;<input id="btMod" type="submit" Value="M" title="Modifier les informations de cette ligne" name="Modif" onclick="return(confirm(\'Les donn&eacute;es seront reprises dans le formulaire et cette ligne sera supprim&eacute;e. &Ecirc;tes vous s&ucirc;r?\'))" />';
					echo '</form></td>';
					echo '<td id="t-container'.$i.$k.'" width="28px">';
						echo '&nbsp;<input id="btSuppr" type="submit" Value="S" title="Supprimer la ligne" name="Suppr" onclick="if(confirm(\'Etes-vous sur de vouloir supprimer cette entree?\')) showFilterResult('.$donneea[0].');" />';
					echo '</td></tr>';
				}
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
		}
		$reponsea->closeCursor();
		
		//<!-- =================== RESTITUTION: TABLEAU SOUS TOTAL ================= -->
		echo '<tr><td id="t-containertit" align="right" colspan="6">Total</td>';
		echo '<td id="t-containertit" align="right">';
		echo  number_format(($total),2,",",".");
		echo '</td><td id="t-containertit" colspan="3">&nbsp;</td></tr>';
		?>
	</tbody>
</table>
