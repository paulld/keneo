<!-- ================ FILTRES =============== -->
<?php
if(isset($_GET['filt'])) { 
	session_start();
	include("appel_db.php");
	}

//MONTH
echo '<br/><select name="affmonth" id="affmonth" onchange="showFilterResult(0)">';
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
echo '<select name="affyear" id="affyear" onchange="showFilterResult(0)">';
echo '<option value=99999>Ann&eacute;e (All)</option>';
$reponsey = $bdd->query("SELECT * FROM rob_period ORDER BY year");
while ($option = $reponsey->fetch())
{
	echo '<option value='.$option['year'].'>'.$option['year'].'</option>';
}
$reponsey->closeCursor();
echo '</select>';

//DATE RANGE
echo ' - P&eacute;riode: <input size="8" type="text" id="datejourdeb" name="datejourdeb" value="" onchange="showFilterResult(0)" placeholder="A partir du..." title="A partir du..." />';
echo '-<input size="8" type="text" id="datejourfin" name="datejourfin" value="" onchange="showFilterResult(0)" placeholder="Jusqu\'au..." title="Jusqu\'au..." />';

//PHASE
echo '<br/><select name="affphase" id="affphase" onchange="showFilterResult(0)">';
echo '<option value=99999>Phases (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_phase WHERE inputOpen=1 ORDER BY Description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['Description']).'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//CLASS
echo '<select name="affclass" id="affclass" onchange="showFilterResult(0)">';
echo '<option value=99999>Classes (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_class ORDER BY code");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.$optimput['code'].'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//FOURNISSEUR
echo '<select name="afffrs" id="afffrs" onchange="showFilterResult(0)">';
echo '<option value=99999>Fournisseurs (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_fournisseur WHERE actif=1 ORDER BY Description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['Description']).'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//ACTIVITE
echo '<select name="affactivite" id="affactivite" onchange="showFilterResult(0)">';
echo '<option value=99999>Activit&eacute;s (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_activite WHERE actif=1 ORDER BY Description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['Description']).'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//PAIEMENT
echo '<select name="affpaie" id="affpaie" onchange="showFilterResult(0)">';
echo '<option value=99999>Paiement (All)</option>';
echo '<option value=0>Non pay&eacute;</option>';
echo '<option value=1>Pay&eacute;</option>';
echo '</select>';

//CLIENT
echo '<br/><select name="affclient" id="affclient" onchange="showFilterResult(0)">';
echo '<option value=99999>Clients (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_imputl1 WHERE actif=1 ORDER BY description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['description']).'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//PROJET
echo '<select name="affprojet" id="affprojet" onchange="showFilterResult(0)">';
echo '<option value=99999>Projets (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_imputl2 WHERE actif=1 ORDER BY description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['description']).'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//COMP
echo '<br/><select name="affcomp" id="affcomp" onchange="showFilterResult(0)">';
echo '<option value=99999>Comp&eacute;tition (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_compl1 WHERE actif=1 ORDER BY description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['description']).'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//TYPE
echo '<select name="afftype" id="afftype" onchange="showFilterResult(0)">';
echo '<option value=99999>Type (All)</option>';
$reqimput = $bdd->query("SELECT * FROM rob_compl2 WHERE actif=1 ORDER BY description");
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['description']).'</option>';
}
$reqimput->closeCursor();
echo '</select>';

//REPORTING
echo '<br/><select name="affreportID" id="affreportID" onchange="showFilterResult(0)">';
$req = "SELECT * FROM rob_reporting WHERE actif=1 AND level <= ".$_SESSION['id_lev_jrl']." ORDER BY level,description";
$reqimput = $bdd->query($req);
while ($optimput = $reqimput->fetch())
{
	echo '<option value='.$optimput['ID'].'>'.$optimput['level'].'-'.utf8_encode($optimput['description']).'</option>';
}
$reqimput->closeCursor();
echo '</select>';
?>