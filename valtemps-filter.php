<section class="container section-container section-toggle" id="saisie-temps">
	<div class="section-title toggle-botton-margin" id="toggle-title">
		<h1>
			<i class="fa fa-chevron-down"></i>
			Filtrer
			<i class="fa fa-chevron-down"></i>
		</h1>
	</div>

<!-- ================ FILTRES =============== -->
	<form action="#" method="post" id="toggle-content" style="display: none;" >
		<div class="form-inner">
		<?php
		if(isset($_GET['filt'])) { 
			session_start();
			include("appel_db.php");
			}

		//MONTH
		echo '<select name="affmonth" class="form-control form-control-small" id="affmonth" onchange="showFilterValtps(0)">';
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
		echo '<select name="affyear" class="form-control form-control-small" id="affyear" onchange="showFilterValtps(0)">';
		echo '<option value=99999>Ann&eacute;e (All)</option>';
		$reponsey = $bdd->query("SELECT * FROM rob_period ORDER BY year");
		while ($option = $reponsey->fetch())
		{
			echo '<option value='.$option['year'].'>'.$option['year'].'</option>';
		}
		$reponsey->closeCursor();
		echo '</select>';

		//DATE RANGE
		echo '<input class="form-control form-control-small" type="text" id="datejourdeb" name="datejourdeb" value="" onchange="showFilterValtps(0)" placeholder="A partir du..." title="A partir du..." />';
		echo '<input class="form-control form-control-small" type="text" id="datejourfin" name="datejourfin" value="" onchange="showFilterValtps(0)" placeholder="Jusqu\'au..." title="Jusqu\'au..." />';

		//COLLABORATEURS
		if ($_SESSION['id_lev_tms'] == 6) { $fltuser = ''; } else { if ($_SESSION['id_lev_tms'] == 4) { $fltuser = ' AND T2.id_hier ='.$_SESSION['ID']; } }
		echo '<select name="affcollab" class="form-control form-control-small" id="affcollab" onchange="showFilterValtps(0)">';
		echo '<option value=99999>Collaborateurs (All)</option>';
		$req = "SELECT * FROM rob_user T1 INNER JOIN rob_user_rights T2 ON T1.ID = T2.ID WHERE T1.actif=1".$fltuser." ORDER BY nom, prenom";
		$reqimput = $bdd->query($req);
		while ($optimput = $reqimput->fetch())
		{
			echo '<option value='.$optimput['ID'].'>'.$optimput['nom'].' '.$optimput['prenom'].'</option>';
		}
		$reqimput->closeCursor();
		echo '</select>';

		//CLIENT
		echo '<select name="affclient" class="form-control form-control-small" id="affclient" onchange="showFilterValtps(0)">';
		echo '<option value=99999>Clients (All)</option>';
		$reqimput = $bdd->query("SELECT * FROM rob_imputl1 WHERE actif=1 ORDER BY description");
		while ($optimput = $reqimput->fetch())
		{
			echo '<option value='.$optimput['ID'].'>'.$optimput['description'].'</option>';
		}
		$reqimput->closeCursor();
		echo '</select>';

		//PROJET
		echo '<select name="affprojet" class="form-control form-control-small" id="affprojet" onchange="showFilterValtps(0)">';
		echo '<option value=99999>Projets (All)</option>';
		$reqimput = $bdd->query("SELECT * FROM rob_imputl2 WHERE actif=1 ORDER BY description");
		while ($optimput = $reqimput->fetch())
		{
			echo '<option value='.$optimput['ID'].'>'.$optimput['description'].'</option>';
		}
		$reqimput->closeCursor();
		echo '</select>';

		//VALIDATION
		echo '<select name="affvalid" class="form-control form-control-small" id="affvalid" onchange="showFilterValtps(0)">';
		echo '<option value=0 selected>Non valid&eacute;s</option>';
		echo '<option value=1>Valid&eacute;s</option>';
		echo '<option value=99999>Tout</option>';
		echo '</select>';
		?>
		</div>
	</form>
</section>