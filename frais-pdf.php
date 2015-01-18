<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include_once('Langues.class.php');
	date_default_timezone_set('Europe/Paris');
	$page = 'coeur';
	include("langue.php");
	if (isset($_POST['matricule']) AND $_SESSION['ID']==$_POST['matricule'])
	{
		$flag = $_SESSION['matricule'].'-'.date("ymd").'-'.date("H").date("i");
		$pseudo = $_POST['matricule'];
		$jourdhui = date("d/m/Y");
		
		// get the HTML
		ob_start();

		?>
		<page backimg="images/HeaderPDFfrais.png" backimgx="center" backimgy="top" backimgw="100%" backbottom="0" backtop="30mm" footer="date;heure;page">
			<table cellspacing="0" style="width: 100%;" align="center">
				<tr>
					<td style="color: #439DD1; font-size: 200%;" align="center">NOTE DE FRAIS</td>
				</tr>
			</table>
			<br/>

			<table cellspacing="0" style="width: 100%; text-align: left; font-size: 12px">
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">NOM Pr&eacute;nom :</td>
					<td style="width: 70%; font-size: 100%; color: #439DD1;"><strong><?php echo htmlentities($_SESSION['nom']).' '.htmlentities($_SESSION['prenom']);?></strong></td>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
				</tr>
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">Num&eacute;ro :</td>
					<td style="width: 20%; font-size: 100%; color: #439DD1;"><strong><?php echo $flag; ?></strong></td>
					<td style="width: 55%; font-size: 100%;">&nbsp;</td>
				</tr>
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">&Eacute;mission de la note :</td>
					<td style="width: 20%; font-size: 100%; color: #439DD1;"><strong><?php echo $jourdhui; ?></strong></td>
					<td style="width: 55%; font-size: 100%;">&nbsp;</td>
				</tr>
			</table>
			<br/>

			<?php
			//DETAIL
			$req = "SELECT T11.Compte compte, sum(T1.totalHT) HT, sum(T1.totalTVA) TVA, sum(T1.totalTTC) TTC FROM rob_frais T1 
				INNER JOIN rob_nature2 T11 ON T11.ID = T1.nature2ID
				WHERE T1.userID='$pseudo' AND T1.noteNum = '' AND T1.validation < 2
				GROUP BY T11.Compte WITH ROLLUP";
			$reponsea = $bdd->query($req);
			$checkrep=$reponsea->rowCount();
			if ($checkrep != 0)
			{
				echo '<table cellspacing="0" style="width: 100%; border: 1px solid #cccccc; border-collapse: collapse; text-align: left; font-size: 10px">';
				echo '<tr><td style="width: 5%;">&nbsp;</td>';
				echo '<td align="center" style="width: 62%;">&nbsp;</td>';
				echo '<td align="center" style="width: 10%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Compte</td>';
				echo '<td align="center" style="width: 6%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">HT</td>';
				echo '<td align="center" style="width: 6%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">TVA</td>';
				echo '<td align="center" style="width: 6%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">TTC</td>';
				echo '<td style="width: 5%;">&nbsp;</td></tr>';
				$totht=0;
				$tottva=0;
				$totttc=0;
				while ($donneea = $reponsea->fetch())
				{
					echo '<tr><td style="width: 5%;">&nbsp;</td>';
					echo '<td style="width: 62%;">&nbsp;</td>';
					//info
					echo '<td style="width: 10%; border: 1px solid #cccccc;">'.$donneea['compte'].'</td>';
					//valeurs
					echo '<td style="width: 6%; border: 1px solid #cccccc;" align="right">'.number_format($donneea['HT'],2,".","").'</td>';
					echo '<td style="width: 6%; border: 1px solid #cccccc;" align="right">'.number_format($donneea['TVA'],2,".","").'</td>';
					echo '<td style="width: 6%; border: 1px solid #cccccc;" align="right">'.number_format($donneea['TTC'],2,".","").'</td>';
					echo '<td style="width: 5%;">&nbsp;</td></tr>';
				}
				echo '</table>';
			}
			?>
			<br/>
			
			<?php
			//DETAIL
			$req = "SELECT T1.ID, T2.matricule, T1.datejour, 
				T3.Description, T4.Description, T5.Description, T6.Description, 
				T7.Description, T8.Description, T9.Description, 
				T1.info, T1.totalHT, T1.totalTVA, T1.totalTTC, T1.refact, T11.Description,
				T4.ID, T5.ID, T6.ID, T8.ID, T9.ID, T12.Description, T1.noteNum FROM rob_frais T1 
				INNER JOIN rob_user T2 ON T2.ID = T1.userID
				INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
				INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
				INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
				INNER JOIN rob_imputl4 T6 ON T6.ID = T1.imputIDl4 
				INNER JOIN rob_compl1 T7 ON T7.ID = T1.compID 
				INNER JOIN rob_compl2 T8 ON T8.ID = T1.compID2 
				INNER JOIN rob_compl3 T9 ON T9.ID = T1.compID3 
				INNER JOIN rob_nature2 T11 ON T11.ID = T1.nature2ID
				INNER JOIN rob_activite T12 ON T12.ID = T1.activID
				WHERE T1.userID='$pseudo' AND T1.noteNum = '' AND T1.validation < 2
				ORDER BY T11.Description, T1.datejour, T3.code, T4.code";
			$reponsea = $bdd->query($req);
			$checkrep=$reponsea->rowCount();
			if ($checkrep != 0)
			{
				echo '<table cellspacing="0" style="width: 100%; border: 1px solid #cccccc; border-collapse: collapse; text-align: left; font-size: 10px">';
				echo '<tr><td style="width: 5%;">&nbsp;</td>';
				echo '<td align="center" style="width: 7%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Date</td>';
				echo '<td align="center" style="width: 7%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Nature</td>';
				echo '<td align="center" style="width: 18%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Client/Projet/Mission/Cat&eacute;gorie</td>';
				echo '<td align="center" style="width: 18%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Comp&eacute;tition/Type/&Eacute;v&eacute;nement</td>';
				echo '<td align="center" style="width: 9%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Activit&eacute;</td>';
				echo '<td align="center" style="width: 9%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Description</td>';
				echo '<td align="center" style="width: 6%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Total<br/>HT</td>';
				echo '<td align="center" style="width: 5%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Total<br/>TVA</td>';
				echo '<td align="center" style="width: 6%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Total<br/>TTC</td>';
				echo '<td align="center" style="width: 5%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold; background-color: #439DD1; color: #ffffff;">Refact.</td>';
				echo '<td style="width: 5%;">&nbsp;</td></tr>';
				$totht=0;
				$tottva=0;
				$totttc=0;
				while ($donneea = $reponsea->fetch())
				{
					echo '<tr><td style="width: 5%;">&nbsp;</td>';
					//date
					echo '<td style="width: 7%; border: 1px solid #cccccc;">'.date("d/m/Y",strtotime($donneea[2])).'</td>';
					//Nature
					echo '<td style="width: 7%; border: 1px solid #cccccc;">'.utf8_encode($donneea[15]).'</td>';
					//clients
					echo '<td style="width: 18%; border: 1px solid #cccccc;">'.utf8_encode($donneea[3]);
						if ($donneea[16] != 0) { echo '<br/>| '.utf8_encode($donneea[4]);
							if ($donneea[17] != 0) { echo '<br/>|| '.utf8_encode($donneea[5]);
								if ($donneea[18] != 0) { echo '<BR/>||| '.utf8_encode($donneea[6]); } } }
					echo '</td>';
					//Compétition
					echo '<td style="width: 18%; border: 1px solid #cccccc;">'.utf8_encode($donneea[7]);
						if ($donneea[19] != 0) { echo '<br/>| '.utf8_encode($donneea[8]);
							if ($donneea[20] != 0) { echo '<br/>|| '.utf8_encode($donneea[9]); } }
					echo '</td>';
					//Activité
					echo '<td style="width: 9%; border: 1px solid #cccccc;">'.utf8_encode($donneea[21]).'</td>';
					//info
					echo '<td style="width: 9%; border: 1px solid #cccccc;">'.utf8_encode($donneea[10]).'</td>';
					//valeurs
					echo '<td style="width: 6%; border: 1px solid #cccccc;" align="right">'.number_format($donneea[11],2,".","").'</td>';
					echo '<td style="width: 5%; border: 1px solid #cccccc;" align="right">'.number_format($donneea[12],2,".","").'</td>';
					echo '<td style="width: 6%; border: 1px solid #cccccc;" align="right">'.number_format($donneea[13],2,".","").'</td>';
					$totht = $totht + $donneea[11];
					$tottva = $tottva + $donneea[12];
					$totttc = $totttc + $donneea[13];
					//refacturation
					echo '<td style="width: 5%; border: 1px solid #cccccc;" align="center">';
					if ($donneea[14] == 1)
					{ echo 'oui'; } else
					{ echo 'non'; }
					echo '</td>';
					echo '<td style="width: 5%;">&nbsp;</td></tr>';
				}
				echo '<tr><td style="width: 5%;">&nbsp;</td><td style="border: 1px solid #cccccc; font-weight: bold; background-color: #439DD1; color: #ffffff;" colspan="6" align="right">TOTAL</td>';
				echo '<td style="width: 6%; border: 1px solid #cccccc; font-weight: bold; background-color: #439DD1; color: #ffffff;" align="right">'.number_format($totht,2,".","").'</td>';
				echo '<td style="width: 5%; border: 1px solid #cccccc; font-weight: bold; background-color: #439DD1; color: #ffffff;" align="right">'.number_format($tottva,2,".","").'</td>';
				echo '<td style="width: 6%; border: 1px solid #cccccc; font-weight: bold; background-color: #439DD1; color: #ffffff;" align="right">'.number_format($totttc,2,".","").'</td>';
				echo '<td style="width: 5%; border: 1px solid #cccccc; font-weight: bold; background-color: #439DD1; color: #ffffff;" align="center">&nbsp;</td><td style="width: 5%;">&nbsp;</td></tr>';
				echo '</table>';
			}
			?>
			<br/>

			<table cellspacing="0" style="width: 100%; border: 1px solid #439DD1; border-collapse: collapse; text-align: left; font-size: 12px">
				<tr>
					<td style="width: 5%;">&nbsp;</td>
					<td align="center" style="width: 45%; border: 1px solid #439DD1; border-collapse: collapse; font-weight: bold; color: #439DD1;">Signature &eacute;metteur<br/><br/><br/><br/>&nbsp;</td>
					<td align="center" style="width: 45%; border: 1px solid #439DD1; border-collapse: collapse; font-weight: bold; color: #439DD1;">Signature directeur<br/><br/><br/><br/>&nbsp;</td>
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
			$html2pdf = new HTML2PDF('L', 'A4', 'fr');
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