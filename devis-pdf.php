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
		$userValid = $_SESSION['ID'];
		$jourdhui = date("Y-m-d");
		$req = "SELECT DISTINCT T1.dateTransac, T2.description client, T2.adresse adresse, T2.cp cp, T2.ville ville, T2.pays pays, T3.description projet, T4.description competition, T5.description type, T6.description evenement, T7.mail contact FROM rob_devis T1 
		INNER JOIN rob_imputl1 T2 ON T2.ID = T1.imputID1 
		INNER JOIN rob_imputl2 T3 ON T3.ID = T1.imputID2
		INNER JOIN rob_compl1 T4 ON T4.ID = T1.compID1 
		INNER JOIN rob_compl2 T5 ON T5.ID = T1.compID2
		INNER JOIN rob_compl3 T6 ON T6.ID = T1.compID3
		INNER JOIN rob_user_info T7 ON T2.respFactID = T7.ID 
		WHERE devisNum = '$devisNum' AND devisVersion = '$devisVersion' LIMIT 1";
		$reponsea = $bdd->query($req);
		$donneea = $reponsea->fetch();
		$dateTransac = $donneea['dateTransac'];
		$client = $donneea['client'];
		$adresse = $donneea['adresse'];
		$cp = $donneea['cp'];
		$ville = $donneea['ville'];
		$pays = $donneea['pays'];
		$projet = $donneea['projet'];
		$competition = $donneea['competition'];
		$type = $donneea['type'];
		$evenement = $donneea['evenement'];
		$contact = $donneea['contact'];
		$reponsea->closeCursor();
		
		// get the HTML
		ob_start();

		?>
		<page backimgw="100%" backbottom="0" backtop="10mm" footer="date;heure;page">
			<table cellspacing="0" style="width: 100%;">
				<tr>
					<td style="width: 5%;">&nbsp;</td>
					<td style="width: 55%; text-align: left;"><img src="images/LogoL.jpg" height="85" width="204" /></td>
					<td style="width: 35%; font-size: 120%; color: #439DD1; text-align: right;"><strong>DEVIS <?php echo $devisNum; ?></strong></td>
					<td style="width: 5%;">&nbsp;</td>
				</tr>
				<tr>
					<td style="width: 5%;">&nbsp;</td>
					<td style="width: 55%; font-size: 80%; color: #012E4F;">
						<br/><br/>SARL au capital de 500 000,00 &euro;<br/>
						Siret: 50363776100035<br/>
						Code APE: 7022Z<br/>
						N&deg; TVA: FR70503637761<br/><br/>
						892 rue Yves Kermen<br/>
						92650 Boulogne-Billancourt Cedex<br/>
						Tel: +33(0)1 47 79 47 30 - Fax: +33(0)1 46 21 06 88<br/>
						www.keneo.fr<br/><br/>
						Votre contact chez Keneo: <?php echo utf8_encode($contact); ?></td>
					<td style="width: 35%; font-size: 80%; color: #012E4F;">
						<strong><?php echo utf8_encode($client); ?></strong><br/>
						<?php echo utf8_encode($adresse); ?><br/>
						<?php echo utf8_encode($cp).' '.utf8_encode($ville); ?><br/>
						<?php echo utf8_encode($pays); ?></td>
					<td style="width: 5%;">&nbsp;</td>
				</tr>
			</table>
			<br/>
			<br/>

			<table cellspacing="0" style="width: 100%; text-align: center; font-size: 12px;">
				<tr>
					<td style="width: 5%;">&nbsp;</td>
					<td style="width: 10%; font-size: 100%; color: #012E4F; border: 1px solid #cccccc; border-collapse: collapse;"><strong>DATE</strong></td>
					<td style="width: 30%; font-size: 100%; color: #012E4F; border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; border-collapse: collapse;"><strong>PROJET</strong></td>
					<td style="width: 36%; font-size: 100%; color: #012E4F; border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; border-collapse: collapse;"><strong>COMP&Eacute;TITION</strong></td>
					<td style="width: 14%; font-size: 100%; color: #012E4F; border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; border-collapse: collapse;"><strong>VERSION</strong></td>
					<td style="width: 5%;">&nbsp;</td>
				</tr>
				<tr>
					<td style="width: 5%;">&nbsp;</td>
					<td style="width: 10%; font-size: 100%; color: #012E4F; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc; border-collapse: collapse;"><?php echo date("d/m/Y",strtotime($dateTransac)); ?></td>
					<td style="width: 30%; font-size: 100%; color: #012E4F; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; border-collapse: collapse;"><?php echo utf8_encode($projet); ?></td>
					<td style="width: 36%; font-size: 100%; color: #012E4F; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; border-collapse: collapse;"><?php echo utf8_encode($competition);?></td>
					<td style="width: 14%; font-size: 100%; color: #012E4F; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; border-collapse: collapse;"><?php echo utf8_encode($devisVersion); ?></td>
					<td style="width: 5%;">&nbsp;</td>
				</tr>
			</table>
			<br/>

			<?php
			//DETAIL
			$req = "SELECT T1.descriptif descriptif, T1.unitaire unitaire, T1.quantite quantite, T1.total total FROM rob_devis T1 
				WHERE T1.devisNum = '$devisNum' AND T1.devisVersion = '$devisVersion'
				ORDER BY T1.ID";
			$reponsea = $bdd->query($req);
			$checkrep=$reponsea->rowCount();
			if ($checkrep != 0)
			{
				echo '<table cellspacing="0" style="width: 100%; border: 1px solid #ffffff; border-collapse: collapse; text-align: left; font-size: 10px">';
				echo '<tr><td style="width: 5%;">&nbsp;</td>';
				echo '<td align="center" style="width: 60%; border-right: 1px solid #ffffff; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">D&eacute;signation</td>';
				echo '<td align="center" style="width: 10%; border-right: 1px solid #ffffff; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Co&ucirc;t<br/>unitaire HT</td>';
				echo '<td align="center" style="width: 10%; border-right: 1px solid #ffffff; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Quantit&eacute;s</td>';
				echo '<td align="center" style="width: 10%; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Total HT</td>';
				echo '<td style="width: 5%;">&nbsp;</td></tr>';
				echo '<tr><td style="width: 5%;">&nbsp;</td><td colspan="3">&nbsp;</td><td style="width: 10%; background-color: #eeeeee;">&nbsp;</td><td style="width: 5%;">&nbsp;</td></tr>';
				$totdevis=0;
				while ($donneea = $reponsea->fetch())
				{
					if ($donneea['descriptif'] == "") { $d = "border-bottom: 1px solid #cccccc; font-weight: bold; "; $e = " color: #012E4F;"; $f = "Total"; }
					echo '<tr><td style="width: 5%;">&nbsp;</td>';
					echo '<td style="width: 60%; color: #012E4F;">- '.utf8_encode($donneea['descriptif']).'</td>';
					echo '<td style="width: 10%; color: #012E4F;" align="right">'.number_format($donneea['unitaire'],2,","," ").'</td>';
					echo '<td style="width: 10%; color: #012E4F;" align="right">'.number_format($donneea['quantite'],2,","," ").'</td>';
					echo '<td style="width: 10%; color: #012E4F; background-color: #eeeeee;" align="right">'.number_format($donneea['total'],2,","," ").'</td>';
					$totdevis = $totdevis + $donneea['total'];
					echo '<td style="width: 5%;">&nbsp;</td></tr>';
				}
				echo '<tr><td style="width: 5%;">&nbsp;</td><td colspan="3">&nbsp;</td><td style="width: 10%; background-color: #eeeeee;">&nbsp;</td><td style="width: 5%;">&nbsp;</td></tr>';
				echo '<tr><td style="width: 5%;">&nbsp;</td>';
				echo '<td style="border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;" colspan="2">Total '.$devisNum.'</td>';
				echo '<td style="width: 10%; border-right: 1px solid #ffffff; background-color: #439DD1;  border-collapse: collapse;">&nbsp;<br/>&nbsp;</td>';
				echo '<td style="width: 10%; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;" align="right">'.number_format($totdevis,2,","," ").'</td>';
				echo '<td style="width: 5%;">&nbsp;</td></tr>';
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
		<page backimgw="100%" backbottom="10mm" backtop="10mm">
		<?php
			include("cgv.php");
		?>
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
			$html2pdf->Output('devis-pdf.pdf');

			//Ajout du flag et validation niveau 1
			$bdd->query("UPDATE rob_devis SET validation=6, userValid='$userValid', dateValid='$jourdhui' WHERE devisNum='$devisNum' AND devisVersion='$devisVersion'");
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