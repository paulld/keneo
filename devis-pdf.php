<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include_once('Langues.class.php');
	date_default_timezone_set('Europe/Paris');
	if (isset($_POST['devisData']))
	{
		$data = $_POST['devisData'];
		list($devisNum, $devisVersion) = explode("||", $data);
		$flag = $_SESSION['ID'].'-'.date("ymd").'-'.date("H").date("i");
		$pseudo = $_SESSION['ID'];
		$jourdhui = date("d/m/Y");
		$req = "SELECT DISTINCT T1.dateTransac, T2.description client, T3.description projet, T4.description competition, T5.description type, T6.description evenement FROM rob_devis T1 
		INNER JOIN rob_imputl1 T2 ON T2.ID = T1.imputID1 
		INNER JOIN rob_imputl2 T3 ON T3.ID = T1.imputID2
		INNER JOIN rob_compl1 T4 ON T4.ID = T1.compID1 
		INNER JOIN rob_compl2 T5 ON T5.ID = T1.compID2
		INNER JOIN rob_compl3 T6 ON T6.ID = T1.compID3
		WHERE devisNum = '$devisNum' AND devisVersion = '$devisVersion' AND userID = '$pseudo' LIMIT 1";
		$reponsea = $bdd->query($req);
		$donneea = $reponsea->fetch();
		$dateTransac = $donneea['dateTransac'];
		$client = $donneea['client'];
		$projet = $donneea['projet'];
		$competition = $donneea['competition'];
		$type = $donneea['type'];
		$evenement = $donneea['evenement'];
		$reponsea->closeCursor();
		
		// get the HTML
		ob_start();

		?>
		<page backimgw="100%" backbottom="0" backtop="10mm" footer="date;heure;page">
			<table cellspacing="0" style="width: 100%;">
				<tr>
					<td style="width: 5%;">&nbsp;</td>
					<td style="width: 90%; text-align: right;"><img src="images/LogoL.jpg" height="50" width="120" /></td>
					<td style="width: 5%;">&nbsp;</td>
				</tr>
			</table>
			<br/>
			<table cellspacing="0" style="width: 100%;">
				<tr>
					<td style="width: 5%;">&nbsp;</td>
					<td style="width: 90%; font-size: 120%; color: #439DD1;" ><strong>DEVIS - N&deg;<?php echo $devisNum.' ('.$devisVersion.')'; ?></strong></td>
					<td style="width: 5%;">&nbsp;</td>
				</tr>
			</table>
			<br/>
			<br/>

			<table cellspacing="0" style="width: 100%; text-align: left; font-size: 12px">
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">Date :</td>
					<td style="width: 70%; font-size: 100%; color: #012E4F;"><strong><?php echo date("d/m/Y",strtotime($dateTransac)); ?></strong></td>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
				</tr>
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">Client :</td>
					<td style="width: 70%; font-size: 100%; color: #012E4F;"><strong><?php echo $client; ?></strong></td>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
				</tr>
				<?php if($projet != "-") { ?>
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">Projet :</td>
					<td style="width: 70%; font-size: 100%; color: #012E4F;"><strong><?php echo $projet; ?></strong></td>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
				</tr>
				<?php } if($competition != "-") { ?>
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">Comp&eacute;tition :</td>
					<td style="width: 70%; font-size: 100%; color: #012E4F;"><strong><?php echo $competition;?></strong></td>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
				</tr>
				<?php } if($type != "-") { ?>
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">Type :</td>
					<td style="width: 70%; font-size: 100%; color: #012E4F;"><strong><?php echo $type; ?></strong></td>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
				</tr>
				<?php } if($evenement != "-") { ?>
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">&Eacute;v&eacute;nement :</td>
					<td style="width: 70%; font-size: 100%; color: #012E4F;"><strong><?php echo $evenement; ?></strong></td>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
				</tr>
				<?php } ?>
			</table>
			<br/>

			<?php
			//DETAIL
			$req = "SELECT T1.descriptif descriptif, sum(T1.unitaire) unitaire, sum(T1.quantite) quantite, sum(T1.total) total, T11.Description nature1 FROM rob_devis T1 
				INNER JOIN rob_nature1 T11 ON T11.ID = T1.nature1ID
				WHERE T1.userID='$pseudo' AND T1.devisNum = '$devisNum' AND T1.devisVersion = '$devisVersion'
				GROUP BY T11.Description, T1.descriptif WITH ROLLUP";
			$reponsea = $bdd->query($req);
			$checkrep=$reponsea->rowCount();
			if ($checkrep != 0)
			{
				echo '<table cellspacing="0" style="width: 100%; border: 1px solid #ffffff; border-collapse: collapse; text-align: left; font-size: 10px">';
				echo '<tr><td style="width: 5%;">&nbsp;</td>';
				echo '<td align="center" style="width: 60%; border: 0px; border-collapse: collapse; ">&nbsp;</td>';
				echo '<td align="center" style="width: 10%; border: 1px solid #ffffff; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Co&ucirc;t<br/>unitaire HT</td>';
				echo '<td align="center" style="width: 10%; border: 1px solid #ffffff; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">&nbsp;<br/>Quantit&eacute;s</td>';
				echo '<td align="center" style="width: 10%; border: 1px solid #ffffff; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">&nbsp;<br/>Total</td>';
				echo '<td style="width: 5%;">&nbsp;</td></tr>';
				while ($donneea = $reponsea->fetch())
				{
					if ($donneea['descriptif'] != "") { $n = ""; $d = "border-bottom: 1px solid #cccccc; "; $e = " color: #012E4F;"; $f = ' - '.$donneea['descriptif']; }
					if ($donneea['descriptif'] == "" AND $donneea['nature1'] != "") { $n = "Total "; $d = "border-bottom: 1px solid #cccccc; font-weight: bold; "; $e = " color: #012E4F;"; $f = $donneea['nature1']; }
					if ($donneea['nature1'] == "") { $n = "TOTAL GENERAL"; $d = "border: 1px solid #ffffff; font-weight: bold; "; $e = "background-color: #439DD1; color: #ffffff;"; $f = ""; } 
					echo '<tr><td style="width: 5%;">&nbsp;</td>';
					//Nature
					echo '<td style="width: 60%; '.$d.$e.'">'.$n.$f.'</td>';
					//valeurs
					if ($donneea['descriptif'] != "") {
					echo '<td style="width: 10%; '.$d.$e.'" align="right">'.number_format($donneea['unitaire'],2,".","").'</td>';
					echo '<td style="width: 10%; '.$d.$e.'" align="right">'.number_format($donneea['quantite'],2,".","").'</td>';
					echo '<td style="width: 10%; '.$d.$e.'" align="right">'.number_format($donneea['total'],2,".","").'</td>'; } else {
					echo '<td style="width: 10%; '.$d.$e.'" align="right">&nbsp;</td>';
					echo '<td style="width: 10%; '.$d.$e.'" align="right">&nbsp;</td>';
					echo '<td style="width: 10%; '.$d.$e.'" align="right">'.number_format($donneea['total'],2,".","").'</td>';
					}
					echo '<td style="width: 5%;">&nbsp;</td></tr>';
				}
				echo '</table>';
			}
			$reponsea->closeCursor();
			?>
			<br/>

			<table cellspacing="0" style="width: 100%; border: 0px; border-collapse: collapse; text-align: left; font-size: 10px">
				<tr>
					<td style="width: 5%;">&nbsp;</td>
					<td style="width: 90%; color: #012E4F; border: 0px; border-collapse: collapse; ">
						<u>Conditions de r&egrave;glement</u><br/>
						R&egrave;glement &agrave; r&eacute;ception de facture par ch&egrave;que ou par virement bancaire en Euros à l’ordre de KENEO.
					</td>
					<td style="width: 5%;">&nbsp;</td>
				</tr>
			</table>
		</page>

		<?php
		$content = ob_get_clean();

		// convert to PDF
		require_once('html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
			//$html2pdf->pdf->SetProtection(array('print'), 'spipu');
			$html2pdf->writeHTML($content, isset($_POST['vuehtml']));
			$html2pdf->Output('frais-pdf.pdf');

			//Ajout du flag et validation niveau 1
			$req = "UPDATE rob_frais SET noteNum = '$flag', validation = 1 WHERE userID = '$pseudo' AND noteNum = ''";
			$reponsea = $bdd->query($req);
		}
		
		catch(HTML2PDF_exception $e)
		{
			echo $e;
			exit;
		}
	}
}
else
{
	include("index.php");
}
?>