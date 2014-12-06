<?php
if(isset($p)) { $recupinfo = 1; } else {session_start(); $p = intval($_GET['p']); $recupinfo = 0; }
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{

	if ($p == 1 or $p == 2)
	{
		$pro= "SELECT ID, Description FROM rob_profil WHERE actif = 1 AND nat2ID = ".$p." ORDER BY Description";

		$reppro = $bdd->query($pro);
		$checkpro = $reppro->rowCount();
		if ($checkpro != 0)
		{
			echo '<select name="profilID" />';
				echo '<option value="none">Profil...</option>';
				while ($optimput = $reppro->fetch())
				{
					if ($recupinfo == 1)
					{
						if ($optimput[0] == $pr)
						{
							echo '<option value='.$optimput['ID'].' selected>'.utf8_encode($optimput['Description']).'</option>';
						}
						else
						{
							echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['Description']).'</option>';
						}
					}
					else
					{
						echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['Description']).'</option>';
					}
				}
			echo '</select>';
			$req = "SELECT T1.ID, T1.nom, T1.prenom FROM rob_user T1
			INNER JOIN rob_user_rights T2 ON T1.ID = T2.ID 
			WHERE T1.actif = 1 AND T2.extstd = ".$p." ORDER BY T1.nom, T1.prenom";
			echo '<select name="collaborateurID" />';
				echo '<option value="none">Collaborateur...</option>';
				$reqimput = $bdd->query($req);
				while ($optimput = $reqimput->fetch())
				{
					if ($recupinfo == 1)
					{
						if ($optimput[0] == $coll)
						{
							echo '<option value='.$optimput['ID'].' selected>'.utf8_encode($optimput['nom']).'. '.substr ($optimput['prenom'],0,1).'</option>';
						}
						else
						{
							echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['nom']).'. '.substr ($optimput['prenom'],0,1).'</option>';
						}
					}
					else
					{
						echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['nom']).'. '.substr ($optimput['prenom'],0,1).'</option>';
					}
				}
				$reqimput->closeCursor();
			echo '</select><input type="hidden" value="" name="beneficiaire" />';
		}
		else
		{
			if ($p ==2)
			{
				echo '<input type="hidden" value="0" name="profilID" />';
				$req = "SELECT T1.ID, T1.nom, T1.prenom FROM rob_user T1
				INNER JOIN rob_user_rights T2 ON T1.ID = T2.ID 
				WHERE T1.actif = 1 AND T2.extstd = ".$p." ORDER BY T1.nom, T1.prenom";
				echo '<select name="collaborateurID" />';
					echo '<option value="none">Collaborateur...</option>';
					$reqimput = $bdd->query($req);
					while ($optimput = $reqimput->fetch())
					{
						if ($recupinfo == 1)
						{
							if ($optimput[0] == $coll)
							{
								echo '<option value='.$optimput['ID'].' selected>'.utf8_encode($optimput['nom']).'. '.substr ($optimput['prenom'],0,1).'</option>';
							}
							else
							{
								echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['nom']).'. '.substr ($optimput['prenom'],0,1).'</option>';
							}
						}
						else
						{
							echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['nom']).'. '.substr ($optimput['prenom'],0,1).'</option>';
						}
					}
					$reqimput->closeCursor();
				echo '</select><input type="hidden" value="" name="beneficiaire" />';
			}
			else
			{
				echo '<input type="hidden" value="0" name="profilID" />';
				echo '<input type="hidden" value="0" name="collaborateurID" />';
				echo '<input type="hidden" value="" name="beneficiaire" />';
			}
		}
		$reppro->closeCursor();
	}
	else
	{
		$pro= "SELECT natID1 FROM rob_nature2 WHERE ID = ".$p;
		$reppro = $bdd->query($pro);
		$optimput = $reppro->fetch();
		if ($optimput[0] == 2)
		{
			echo '<input type="hidden" value="0" name="profilID" />';
			$req = "SELECT T1.ID, T1.nom, T1.prenom FROM rob_user T1
			INNER JOIN rob_user_rights T2 ON T1.ID = T2.ID 
			WHERE T1.actif = 1 ORDER BY T1.nom, T1.prenom";
			echo '<select name="collaborateurID" />';
				echo '<option value="none">Collaborateur...</option>';
				$reqimput = $bdd->query($req);
				while ($optimput = $reqimput->fetch())
				{
					if ($recupinfo == 1)
					{
						if ($optimput[0] == $coll)
						{
							echo '<option value='.$optimput['ID'].' selected>'.utf8_encode($optimput['nom']).'. '.substr ($optimput['prenom'],0,1).'</option>';
						}
						else
						{
							echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['nom']).'. '.substr ($optimput['prenom'],0,1).'</option>';
						}
					}
					else
					{
						echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['nom']).'. '.substr ($optimput['prenom'],0,1).'</option>';
					}
				}
				$reqimput->closeCursor();
			echo '</select><input type="hidden" value="" name="beneficiaire" />';
		}
		else
		{
			echo '<input type="hidden" value="0" name="profilID" />';
			echo '<input type="hidden" value="0" name="collaborateurID" />';
			echo '<input type="hidden" value="" name="beneficiaire" />';
		}
		$reppro->closeCursor();
	}
	 
}
?> 