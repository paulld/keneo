<!-- ================ FILTRES =============== -->
<?php
if(isset($_GET['filt'])) { 
	session_start();
	include("appel_db.php");
	}

//MONTH
echo '<br/><select name="affmonth" id="affmonth" onchange="showFilterValtps(0)">';
echo '<option value=99999>Mois (All)</option>';
$i=1;
while ($i < 13)
{
	if ($i<10) { $ii="0";} else { $ii =""; }
	echo '<option value='.$ii.$i.'>'.date("F",strtotime("2000-".$ii.$i."-10")).'</option>';
	$i = $i + 1;
}
echo '</select>';

//YEAR
echo '<select name="affyear" id="affyear" onchange="showFilterValtps(0)">';
echo '<option value=99999>Ann&eacute;e (All)</option>';
$reponsey = $bdd->query("SELECT * FROM rob_period ORDER BY year");
while ($option = $reponsey->fetch())
{
	echo '<option value='.$option['year'].'>'.$option['year'].'</option>';
}
$reponsey->closeCursor();
echo '</select>';

//DATE RANGE
echo ' - P&eacute;riode: <input size="8" type="text" id="datejourdeb" name="datejourdeb" value="" onchange="showFilterValtps(0)" placeholder="A partir du..." title="A partir du..." />';
echo '-<input size="8" type="text" id="datejourfin" name="datejourfin" value="" onchange="showFilterValtps(0)" placeholder="Jusqu\'au..." title="Jusqu\'au..." />';

//COLLABORATEURS
if ($_SESSION['id_lev_tms'] == 6) { $fltuser = ''; } else { if ($_SESSION['id_lev_tms'] == 4) { $fltuser = ' AND T2.id_hier ='.$_SESSION['ID']; } }
echo '<br/><select name="affcollab" id="affcollab" onchange="showFilterValtps(0)">';
echo '<option value=99999>Collaborateurs (All)</option>';
$req = "SELECT * FROM rob_user T1 INNER JOIN rob_user_rights T2 ON T1.ID = T2.ID WHERE T1.actif=1".$fltuser." ORDER BY nom, prenom";
$reqimput = $bdd->query($req);
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.$optimput['nom'].' '.$optimput['prenom'].'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//ACTIVITE
echo '<select name="affactivite" id="affactivite" onchange="showFilterValtps(0)">';
echo '<option value=99999>Activit&eacute;s (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_activite WHERE actif=1 ORDER BY Description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.$optimput['Description'].'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//CLIENT
echo '<br/><select name="affclient" id="affclient" onchange="showFilterValtps(0)">';
echo '<option value=99999>Clients (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_imputl1 WHERE actif=1 ORDER BY description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.$optimput['description'].'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//PROJET
echo '<select name="affprojet" id="affprojet" onchange="showFilterValtps(0)">';
echo '<option value=99999>Projets (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_imputl2 WHERE actif=1 ORDER BY description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.$optimput['description'].'</option>';
}
$reqimput->closeCursor();
echo '</select>';
?>