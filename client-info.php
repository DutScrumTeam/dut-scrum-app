<?php include("class/page-builder.inc.php"); ?>
<?php include("class/highchart-builder.inc.php"); ?>
<script src="js/page-manager.js"></script>
<script src="js/highchart.js"></script>

<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Banccord | (Nom du client) - informations</title>
	<?php include("class/head.inc.php"); ?>
</head>

<body>
	<?php
		echoHeader(
			"Accueil", "client.php",
			"Impayés", "client-unpaid.php"
		);
	?>

	<div class="container">
		<div class="row">
			<!-- Les différents titres -->
			<div class="col titles">
				<h1 class="div-center-v">Liste des remises</h1>
			</div>
			
		</div>
		<!-- Le graphique -->
		<?php echoPaymentChart(); ?>

		<!-- Champ de recherche -->
		<script src="js/form-edit.js"></script>
		<form action="" method="get">
			<div class="row action-option">

				<!-- Type de recherche -->
				<div class="col-auto">
					<div class="form-group">
						<select class="form-control" name="search-mod" id="search-mod">
							<option 
									value="siren" selected 
									onclick="changeInput('search-value', 'Entrez le numéro de Siren', 'number');">
								Recherche par Siren
							</option>
							<option 
									value="name" 
									onclick="changeInput('search-value', 'Entrez la raison sociale', 'text');">
								Recherche par raison sociale
							</option>
						</select>
					</div>
				</div>

				<!-- Valeur de recherche -->
				<div class="col">
					<div class="form-group">
						<input type="number" class="form-control" id="search-value" name="search-value" placeholder="Entrez le numéro de Siren">
					</div>
				</div>

				<!-- Bouton de confirmation -->
				<div class="col-auto">
					<button class="btn btn-primary div-center-v">Rechercher</button>
				</div>
			</div>
		</form>

		<!-- Bouton d'export -->
		<?php echoExportButton(); ?>

		<!-- Tableau des remises -->
		<table class="table table-striped">
			<?php 
				echoTableHead(
					"Date de paiement",
					"Type de carte",
					"Numéro de carte",
					"Code d'autorisation",
					"Montant");
			?>
			<tbody id="table-result">
				<tr>
					<th scope="row">2021-03-03</th>
					<td>Visa</td>
					<td>6942 6942 6942 6942</td>
					<td>48</td>
					<td>1.00€</td>
				</tr>
				<tr>
					<th scope="row">2021-08-03</th>
					<td>Visa</td>
					<td>6942 6942 6942 6942</td>
					<td>29</td>
					<td>1.00€</td>
				</tr>
				<tr>
					<th scope="row">2021-05-03</th>
					<td>Paypal</td>
					<td>6942 6942 6942 6942</td>
					<td>35</td>
					<td>1.00€</td>
				</tr>
			</tbody>
		</table>

		<!-- Choix de la page des résultats -->
		<?php echoPageChoice() ?>

		<script>initPageManager()</script>
		<script>
			// Cas par défaut
			let dateEnd = new Date();
			let dateStart = new Date();
			dateStart.setFullYear(dateEnd.getFullYear()-1);

			// Cas avec les dates de début et de fin définis
			// let dateStart = new Date(sqlDateToJsDate("2020-06-01"));
			// let dateEnd = new Date(sqlDateToJsDate("2021-06-01"));

			createPaymentChart(lines, dateStart, dateEnd);
		</script>

	</div>
</body>
</html>