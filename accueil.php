<?php
session_start();
include("appel_db.php");

if (isset($_POST['mot_de_passe']) AND $_POST['mot_de_passe'] != "")
{
 	$pseudo = $_POST['matricule'];
	$pass = $_POST['mot_de_passe'];
	$testpass='';
	$req = "SELECT T1.ID, T1.nom, T1.prenom, T2.id_lev_menu, T2.id_hier, T2.id_pole, T2.id_lev_tms, T2.id_lev_exp, T2.resp_abs, T2.id_lev_jrl, T3.mail, T1.password, T2.id_auth, T4.seuil
			FROM rob_user T1 
			INNER JOIN rob_user_rights T2 ON T1.ID = T2.ID
			INNER JOIN rob_user_info T3 ON T1.ID = T3.ID
			INNER JOIN rob_grade T4 ON T2.id_auth = T4.ID
			WHERE T1.matricule='$pseudo' and T1.password='$pass' and T1.actif=1";
 	$reponse = $bdd->query($req);
	$checkdata = $reponse->rowCount();
	if ($checkdata != 0)
	{
		//Enregistrement des variables de session
		while ($donnee = $reponse->fetch())
		{
			$_SESSION['ID'] = $donnee[0];
			$_SESSION['nom'] = $donnee[1];
			$_SESSION['prenom'] = $donnee[2];
			$_SESSION['id_lev_menu'] = $donnee[3];
			$_SESSION['id_hier'] = $donnee[4];
			$_SESSION['id_pole'] = $donnee[5];
			$_SESSION['id_lev_tms'] = $donnee[6];
			$_SESSION['id_lev_exp'] = $donnee[7];
			$_SESSION['resp_abs'] = $donnee[8];
			$_SESSION['id_lev_jrl'] = $donnee[9];
			$_SESSION['mail'] = $donnee[10];
			$_SESSION['pass'] = $donnee[11];
			$_SESSION['auth'] = $donnee[12];
			$_SESSION['seuil'] = $donnee[13];
		}
		$_SESSION['mot_de_passe'] = $pass;
		$_SESSION['matricule'] = $pseudo;
		
		//Table hiérarchie
		$req = "CREATE TABLE IF NOT EXISTS tmp_hier".$_SESSION['ID']." (
			`respID` int(11) NOT NULL,
			`userID` int(11) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$bdd->query($req);
		$req = "TRUNCATE TABLE tmp_hier".$_SESSION['ID']."";
		$bdd->query($req);
		$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT 0, ID FROM rob_user_rights WHERE id_hier = ".$_SESSION['ID'].")";
		$bdd->query($req); // enfants directs
		$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT 0, a.ID FROM rob_user_rights a INNER JOIN tmp_hier".$_SESSION['ID']." b ON a.id_hier = b.userID)";
		$bdd->query($req); // enfants de niveau 2
		$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT 0, a.ID FROM rob_user_rights a INNER JOIN tmp_hier".$_SESSION['ID']." b ON a.id_hier = b.userID)";
		$bdd->query($req); // enfants de niveau 3
		$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT 0, a.ID FROM rob_user_rights a INNER JOIN tmp_hier".$_SESSION['ID']." b ON a.id_hier = b.userID)";
		$bdd->query($req); // enfants de niveau 4
		$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT 0, a.ID FROM rob_user_rights a INNER JOIN tmp_hier".$_SESSION['ID']." b ON a.id_hier = b.userID)";
		$bdd->query($req); // enfants de niveau 5
		$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT 0, a.ID FROM rob_user_rights a INNER JOIN tmp_hier".$_SESSION['ID']." b ON a.id_hier = b.userID)";
		$bdd->query($req); // enfants de niveau 6
		$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT 0, a.ID FROM rob_user_rights a INNER JOIN tmp_hier".$_SESSION['ID']." b ON a.id_hier = b.userID)";
		$bdd->query($req); // enfants de niveau 7
		$req = "INSERT INTO tmp_hier".$_SESSION['ID']." VALUES (0, ".$_SESSION['ID'].")";
		$bdd->query($req); // sois-même
		$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT ".$_SESSION['ID'].", userID FROM tmp_hier".$_SESSION['ID']." GROUP BY respID, userID)";
		$bdd->query($req);
		$req = "DELETE FROM tmp_hier".$_SESSION['ID']." WHERE respID = 0";
		$bdd->query($req);
	}
	$reponse->closeCursor();
}

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']) 
{
	//Ecriture de la page
	include("headerlight.php");
	?>
	<!-- Background Image Specific to each page -->
	<div class="background-accueil background-image"></div>
	<div class="overlay"></div>
	
	<div id="mainMenuLight" class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
				<div class="row">
					<?php
					$men= "SELECT * FROM rob_ssmenu WHERE actif=1 AND main != 0 ORDER BY main";
			 		$menu = $bdd->query($men);
			 		while ($donnee = $menu->fetch())
			 		{
						echo '<div class="col-xs-6">';
							echo '<div class="menu-item-outer">';
								if ($_SESSION['matricule'] != 'FLC' OR $donnee['ID'] != 5)
								{ echo '<a href="'.$donnee['lien'].'"><span class="menu-link"></span></a>'; } else
								{ echo '<a href="#"><span class="menu-link"></span></a>'; }
								echo '<i class="fa fa-'.$donnee['ID'].'"></i><p>'.$donnee['desc1'].'</p>';
							echo '</div>';
						echo '</div>';
					}
					$menu->closeCursor();
			 		?>
		 		</div>
	 		</div>
 		</div>
	</div>

<?php include("footer.php"); 
}
else
{ 
	header("location:index.php");
}
?>