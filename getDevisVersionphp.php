<?php
if(isset($d)) { $recupinfo = 1; } else {session_start(); $d = intval($_GET['d']); $recupinfo = 0; }
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	?>
	<select class="form-control form-control-small" name="devisVersion" id="devisVersion" >
		<?php
		$reqimput = $bdd->query("SELECT DISTINCT devisVersion FROM rob_devis WHERE actif = 1 AND devisNum = '$d' ORDER BY devisVersion DESC");
		while ($optimput = $reqimput->fetch())
		{
			if ($recupinfo == 1)
			{
				if ($optimput['devisVersion'] == $v)
				{
					echo '<option value='.$optimput['devisVersion'].' selected>'.$optimput['devisVersion'].'</option>';
				}
				else
				{
					echo '<option value='.$optimput['devisVersion'].'>'.$optimput['devisVersion'].'</option>';
				}
			}
			else
			{
				echo '<option value='.$optimput['devisVersion'].'>'.$optimput['devisVersion'].'</option>';
			}
		}
		$reqimput->closeCursor();
		?>
		<option value="none">Cr&eacute;er une nouvelle version</option>
	</select>
	<?php	 
}
?> 