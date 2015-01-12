<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	date_default_timezone_set('Europe/Paris');
	if (isset($_GET['month']) AND isset($_GET['year']) AND isset($_GET['matricule']) AND $_SESSION['ID']==$_GET['matricule'])
	{
		// get the HTML
		ob_start();

		?>
		<page backimg="images/HeaderPDF.png" backimgx="center" backimgy="top" backimgw="100%" backbottom="0" backtop="30mm" footer="date;heure;page">
			<br/>
			<table cellspacing="0" style="width: 100%;" align="center">
				<tr>
					<td style="color: #012E4F; font-size: 150%;" align="center">TIMESHEET</td>
				</tr>
			</table>
			<br/>

			<table cellspacing="0" style="width: 100%; text-align: left; font-size: 12px">
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">NOM Pr&eacute;nom :</td>
					<td style="width: 70%; font-size: 100%; color: #012E4F;"><strong><?php echo htmlentities($_SESSION['nom']).' '.htmlentities($_SESSION['prenom']);?></strong></td>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
				</tr>
				<tr>
					<td style="width: 5%; font-size: 100%;">&nbsp;</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;">P&eacute;riode :</td>
					<td style="width: 20%; font-size: 100%; color: #012E4F;"><strong><?php echo $_GET['year'].'.'.$_GET['month'];?></strong></td>
					<td style="width: 55%; font-size: 100%;">&nbsp;</td>
				</tr>
			</table>

			<?php
			$month = $_GET['month'];
			$year = $_GET['year'];
			$matricule = $_GET['matricule'];
			$startdate = $year.'-'.$month.'-01';
			$tmpmonth = $month + 1;
			$enddate = $year.'-'.$tmpmonth.'-01';
			$req1 = "SELECT sum(valeur) FROM rob_temps WHERE userID='".$matricule."' AND datejour >= '".$startdate."' AND datejour < '".$enddate."'";
			$reponse1 = $bdd->query($req1);
			$checkrep1 = $reponse1->rowCount();
			if ($checkrep1 != 0)
			{
				$donnee1 = $reponse1->fetch();
				echo '<table cellspacing="0" style="width: 100%; border: 1px solid #cccccc; border-collapse: collapse; text-align: left; font-size: 10px">';
				echo '<tr><td style="width: 5%;">&nbsp;</td><td style="width: 90%; font-size: 100%; color: #012E4F;; font-weight: bold;" align="right">Total des temps pour '.$month.'.'.$year.': '.number_format($donnee1[0],2,".","").' jours</td><td style="width: 5%;">&nbsp;</td></tr>';
				$req = "SELECT T3.description, sum(T1.valeur) FROM rob_temps T1 
					INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
					WHERE T1.userID='".$matricule."' AND T1.datejour >= '".$startdate."' AND T1.datejour < '".$enddate."'
					GROUP BY T3.description
					ORDER BY T3.description";
				$reponse = $bdd->query($req);
				$checkrep = $reponse->rowCount();
				if ($checkrep != 0)
				{
					while ($donnee = $reponse->fetch())
					{
						echo '<tr><td style="width: 5%;">&nbsp;</td><td style="width: 90%; font-size: 100%; color: #012E4F;" align="right">Dont '.$donnee[0].': '.number_format($donnee[1],2,".","").' jours</td><td style="width: 5%;">&nbsp;</td></tr>';
					}
				}
				$reponse->closeCursor();
				echo '</table><br/>';
			}
			$reponse1->closeCursor();
		
			?>
			<table cellspacing="0" style="width: 100%; text-align: left; font-size: 10px;">
				<tr>
					<td style="width: 5%;" rowspan="2">&nbsp;</td>
					<td style="width: 90%;" colspan="6">D&eacute;tail des temps pour <?php echo $month.'.'.$year; ?></td>
					<td style="width: 5%;" rowspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" style="width: 10%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold;">Date</td>
					<td align="center" style="width: 20%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold;">Client</td>
					<td align="center" style="width: 15%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold;">Projet</td>
					<td align="center" style="width: 15%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold;">Mission</td>
					<td align="center" style="width: 10%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold;">Description</td>
					<td align="center" style="width: 10%; border: 1px solid #cccccc; border-collapse: collapse; font-weight: bold;">Jours</td>
				</tr>
				<?php
				$req = "SELECT T2.matricule, T1.datejour, T3.description, T4.description, T5.description, T1.info, T1.valeur, T1.valtot FROM rob_temps T1 
					INNER JOIN rob_user T2 ON T2.ID = T1.userID
					INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
					INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
					INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
					WHERE T1.userID='$matricule' AND datejour >= '$startdate' AND datejour < '$enddate'
					ORDER BY T1.datejour, T3.description, T4.description, T5.description";
				$reponsea = $bdd->query($req);
				$checkrep=$reponsea->rowCount();
				if ($checkrep != 0)
				{
					while ($donneea = $reponsea->fetch())
					{
						echo '<tr>';
						echo '<td style="width: 5%;">&nbsp;</td>';
						echo '<td style="width: 10%; border: 1px solid #cccccc;">'.date("d/m/Y",strtotime($donneea[1])).'</td>';
						echo '<td style="width: 20%; border: 1px solid #cccccc;">'.$donneea[2].'</td>';
						echo '<td style="width: 15%; border: 1px solid #cccccc;">'.$donneea[3].'</td>';
						echo '<td style="width: 15%; border: 1px solid #cccccc;">'.$donneea[4].'</td>';
						echo '<td style="width: 10%; border: 1px solid #cccccc;">'.$donneea[5].'</td>';
						echo '<td style="width: 10%; width: 10%; border: 1px solid #cccccc;" align="right">'.number_format($donneea[6],2,".","").'</td>';
						echo '<td style="width: 5%;">&nbsp;</td>';
						echo '</tr>';
					}
				}
				?>
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
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->Output('temps-pdf.pdf');
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