<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND $_SESSION['id_lev_exp'] >= 4)
{
	date_default_timezone_set('Europe/Paris');
	if (isset($_POST['datejourstrt']) AND $_POST['datejourend'])
	{
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');

		/** Include PHPExcel */
		include_once('Classes/PHPExcel.php');


		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Barthe R. [Arimor C.]")
									 ->setLastModifiedBy("Barthe R. [Arimor C.]")
									 ->setTitle("Export des frais")
									 ->setSubject("Export des frais")
									 ->setDescription("Extraction des frais de l'intranet sous Excel")
									 ->setKeywords("Frais web")
									 ->setCategory("Frais web");
		$startdate = date('Y-m-d',mktime(0,0,0,substr($_POST['datejourstrt'],3,2),substr($_POST['datejourstrt'],0,2),substr($_POST['datejourstrt'],6,4)));
		$enddate = date('Y-m-d',mktime(0,0,0,substr($_POST['datejourend'],3,2),substr($_POST['datejourend'],0,2),substr($_POST['datejourend'],6,4)));
		$validation = $_POST['validation'];

		// Header
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Collaborateur')
					->setCellValue('B1', 'Date')
					->setCellValue('C1', 'Client')
					->setCellValue('D1', 'Projet')
					->setCellValue('E1', 'Mission')
					->setCellValue('F1', 'Categorie')
					->setCellValue('G1', 'Type de competition')
					->setCellValue('H1', 'Competition')
					->setCellValue('I1', 'Evenement')
					->setCellValue('J1', 'Compte')
					->setCellValue('K1', 'Description')
					->setCellValue('L1', 'Note de frais')
					->setCellValue('M1', 'Validation')
					->setCellValue('N1', 'TotalHT')
					->setCellValue('O1', 'TotalTVA')
					->setCellValue('P1', 'TotalTTC')
					->setCellValue('Q1', 'TauxTVA');

		if ($_SESSION['id_lev_exp'] == 6)
		{
				$req = "SELECT T2.matricule trig, T1.datejour datejour, T3.description client, T4.description projet, T5.description mission, T9.description categorie, T10.description typecomp, T11.description competition, T12.description evenement, T6.Compte compte, T1.info info, T1.noteNum note, T1.validation validation, T1.totalHT totalHT, T1.totalTVA totalTVA, T1.totalTTC totalTTC, T8.taux taux FROM rob_frais T1 
					INNER JOIN rob_user T2 ON T2.ID = T1.userID
					INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
					INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
					INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
					INNER JOIN rob_imputl4 T9 ON T9.ID = T1.imputIDl4 
					INNER JOIN rob_user_rights T7 ON T7.ID = T1.userID
					INNER JOIN rob_nature2 T6 ON T6.ID = T1.nature2ID 
					INNER JOIN rob_tva T8 ON T8.ID = T1.tauxTVA 
					INNER JOIN rob_compl1 T10 ON T10.ID = T1.compID 
					INNER JOIN rob_compl2 T11 ON T11.ID = T1.compID2 
					INNER JOIN rob_compl3 T12 ON T12.ID = T1.compID3 
					WHERE datejour >= '$startdate' AND datejour < '$enddate'
					ORDER BY T2.matricule, T1.datejour, T3.description, T4.description, T5.description, T6.Compte";
		} else {
			if ($_SESSION['id_lev_exp'] == 4)
			{
				$req = "SELECT T2.matricule trig, T1.datejour datejour, T3.description client, T4.description projet, T5.description mission, T9.description categorie, T10.description typecomp, T11.description competition, T12.description evenement, T6.Compte compte, T1.info info, T1.noteNum note, T1.validation validation, T1.totalHT totalHT, T1.totalTVA totalTVA, T1.totalTTC totalTTC, T8.taux taux FROM rob_frais T1 
					INNER JOIN rob_user T2 ON T2.ID = T1.userID
					INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
					INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
					INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
					INNER JOIN rob_imputl4 T9 ON T9.ID = T1.imputIDl4 
					INNER JOIN rob_user_rights T7 ON T7.ID = T1.userID
					INNER JOIN rob_nature2 T6 ON T6.ID = T1.nature2ID 
					INNER JOIN rob_tva T8 ON T8.ID = T1.tauxTVA 
					INNER JOIN rob_compl1 T10 ON T10.ID = T1.compID 
					INNER JOIN rob_compl2 T11 ON T11.ID = T1.compID2 
					INNER JOIN rob_compl3 T12 ON T12.ID = T1.compID3 
					WHERE (T7.id_hier ='".$_SESSION['ID']."' OR T1.userID ='".$_SESSION['ID']."') AND datejour >= '$startdate' AND datejour < '$enddate'
					ORDER BY T2.matricule, T1.datejour, T3.description, T4.description, T5.description, T6.Compte";
			}
		}
		$reponsea = $bdd->query($req);
		$checkrep=$reponsea->rowCount();
		if ($checkrep != 0)
		{
			$row = 2;
			while ($donnee = $reponsea->fetch())
			{
				for ($i=0; $i < 17; $i++)
				{
					$col = '';
					if ($i == 0) { $col = 'A'; $datatmp = $donnee[$i]; }
					if ($i == 1) { $col = 'B'; $datatmp = date ("d/m/Y", strtotime($donnee[$i])); }
					if ($i == 2) { $col = 'C'; $datatmp = $donnee[$i]; }
					if ($i == 3) { $col = 'D'; $datatmp = $donnee[$i]; }
					if ($i == 4) { $col = 'E'; $datatmp = $donnee[$i]; }
					if ($i == 5) { $col = 'F'; $datatmp = $donnee[$i]; }
					if ($i == 6) { $col = 'G'; $datatmp = $donnee[$i]; }
					if ($i == 7) { $col = 'H'; $datatmp = $donnee[$i]; }
					if ($i == 8) { $col = 'I'; $datatmp = $donnee[$i]; }
					if ($i == 9) { $col = 'J'; $datatmp = $donnee[$i]; }
					if ($i == 10) { $col = 'K'; $datatmp = $donnee[$i]; }
					if ($i == 11) { $col = 'L'; $datatmp = $donnee[$i]; }
					if ($i == 12) { $col = 'M'; if ($donnee[$i] == 0) { $datatmp = ''; } else { if ($donnee[$i] == 1) { $datatmp = 'En attente'; } else { $datatmp = 'Valide'; } } }
					if ($i == 13) { $col = 'N'; $datatmp = $donnee[$i]; }
					if ($i == 14) { $col = 'O'; $datatmp = $donnee[$i]; }
					if ($i == 15) { $col = 'P'; $datatmp = $donnee[$i]; }
					if ($i == 16) { $col = 'Q'; $datatmp = $donnee[$i]; }
					
					// Add some data
					if ($col != '')
					{
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue($col.$row, utf8_encode($datatmp));
					}
				}
				$row = $row + 1;
			}
		}
		$reponsea->closeCursor();

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Export');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="ExportTimesheet.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

	}
}
else
{
	include("index.php");
}