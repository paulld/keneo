<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND ($_SESSION['id_lev_tms'] >= 4 OR $_SESSION['id_lev_exp'] >= 4))
{
	include("headerlight.php");
	?>

	<div class="background-reporting background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container">
		<div class="section-title">
			<h1>Financial Accounts</h1>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-financial">
				<thead>
					<tr>
						<th class="table-currency">Figures in Euros</th>
						<th>Label 1</th>
						<th>Label 2</th>
						<th>Label 3</th>
						<th>Label 4</th>
						<th>TOTAL</th>
					</tr>
				</thead>
				<tbody>
						<tr>
							<td>Income</td>
							<td>1000</td>
							<td>500</td>
							<td>800</td>
							<td>1200</td>
							<td>3500</td>
						</tr>
						<tr>
							<td>Expenses</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
						</tr>
						<tr class="table-subtotal">
							<td>EBIT</td>
							<td>1000</td>
							<td>500</td>
							<td>800</td>
							<td>1200</td>
							<td>3500</td>
						</tr>
						<tr>
							<td>Financial Income</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
						</tr>
						<tr>
							<td>Financial Expenses</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
						</tr>
						<tr>
							<td>Tax</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
						</tr>
						<tr class="table-total">
							<td>Net income</td>
							<td>1000</td>
							<td>500</td>
							<td>800</td>
							<td>1200</td>
							<td>3500</td>
						</tr>
						<tr class="table-label">
							<td>% change</td>
							<td>-5%</td>
							<td>+2%</td>
							<td>+1%</td>
							<td>+10%</td>
							<td>+4%</td>
						</tr>
				</tbody>
			</table>
		</div>
	
	</section>

	<?php
	include("footer.php");
}
else
{
	header("location:accueil.php");
}
?>