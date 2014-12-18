<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND $_SESSION['id_lev_tms'] >= 4)
{
	date_default_timezone_set('Europe/Paris');
	if (isset($_POST['datejourdeb']) AND $_POST['datejourfin'])
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
									 ->setTitle("Export des temps")
									 ->setSubject("Export des temps")
									 ->setDescription("Extraction des temps de l'intranet sous Excel")
									 ->setKeywords("Timesheet web")
									 ->setCategory("Timesheet web");
		$startdate = date('Y-m-d',mktime(0,0,0,substr($_POST['datejourdeb'],3,2),substr($_POST['datejourdeb'],0,2),substr($_POST['datejourdeb'],6,4)));
		$enddate = date('Y-m-d',mktime(0,0,0,substr($_POST['datejourfin'],3,2),substr($_POST['datejourfin'],0,2),substr($_POST['datejourfin'],6,4)));

		// Header
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Collaborateur')
					->setCellValue('B1', 'Date')
					->setCellValue('C1', 'Activité')
					->setCellValue('D1', 'Client')
					->setCellValue('E1', 'Projet')
					->setCellValue('F1', 'Mission')
					->setCellValue('G1', 'Information')
					->setCellValue('H1', 'Jour');

		if ($_SESSION['id_lev_tms'] == 6)
		{
			$req = "SELECT T2.matricule, T1.datejour, T6.Description, T3.description, T4.description, T5.description, T1.info, T1.valeur FROM rob_temps T1 
				INNER JOIN rob_user T2 ON T2.ID = T1.userID
				INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
				INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
				INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
				INNER JOIN rob_activite T6 ON T6.ID = T1.activID 
				WHERE datejour >= '$startdate' AND datejour < '$enddate'
				ORDER BY T1.datejour, T3.description, T4.description, T5.description";
		} else {
			if ($_SESSION['id_lev_tms'] == 4)
			{
				$req = "SELECT T2.matricule, T1.datejour, T6.Description, T3.description, T4.description, T5.description, T1.info, T1.valeur FROM rob_temps T1 
					INNER JOIN rob_user T2 ON T2.ID = T1.userID
					INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID 
					INNER JOIN rob_imputl2 T4 ON T4.ID = T1.imputIDl2 
					INNER JOIN rob_imputl3 T5 ON T5.ID = T1.imputIDl3 
					INNER JOIN rob_activite T6 ON T6.ID = T1.activID 
					INNER JOIN rob_user_rights T7 ON T7.ID = T1.userID
					WHERE T7.id_hier ='".$_SESSION['ID']."' AND datejour >= '$startdate' AND datejour < '$enddate'
					ORDER BY T1.datejour, T3.description, T4.description, T5.description";
			}
		}
		$reponsea = $bdd->query($req);
		$checkrep=$reponsea->rowCount();
		if ($checkrep != 0)
		{
			$row = 2;
			while ($donnee = $reponsea->fetch())
			{
				for ($i=0; $i < 10; $i++)
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