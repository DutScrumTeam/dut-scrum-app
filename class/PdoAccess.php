<?php

class PdoAccess
{
	private static $user = "clement.duval";
	private static $pass = "6A3iMDg5UZeR8jz";
	private static $database = "guillaume.grisolet_db";
	private static $host = "sqletud.u-pem.fr";
	private static $pdo;
	public static function getCurrencySymbolFromCode($string): string
	{
		switch ($string){
			case "EUR":
				return "€";
			case "CAD":
			case "USD":
				return "$";
			case "GBP":
				return "£";
			case "CNY":
			case "JPY":
				return "¥";
			case "CHF":
				return "CHF";
			case "AUD":
				return "A$";
			case "NZD":
				return "NZ$";
			case "SEK":
				return "kr";
			case "PHP":
				return "₱";
			case "RUB":
				return "₽";
			case "ILS":
				return "₪";
			case "AZN":
				return "₼";
		}
		return "";
	}
	public static function getPdo(): PDO
	{
		if (self::$pdo === null) {
			try {
				self::$pdo = new PDO('pgsql:host=' . PdoAccess::$host . ';dbname=' . PdoAccess::$database, self::$user, self::$pass);
			} catch (PDOException $e) {
				echo "ERREUR : La connexion a échouée<br>\n";
				echo $e->getMessage() . "<br>\n";
			}
		}
		return self::$pdo;
	}

	public static function insertAccount($name, $password, $type)
	{
		$pdo = self::getPdo();
		$password = md5($password);
		$sql = "INSERT INTO banque.compte VALUES (:name,:password,:type)";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':type', $type);
		$stmt->execute();
	}

	public static function insertClient($name, $password, $siren, $businessName)
	{
		self::insertAccount($name, $password, "Client");
		$pdo = self::getPdo();
		$sql = "INSERT INTO banque.client VALUES (:num_siren,:raison_social,:id_compte)";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':num_siren', $siren);
		$stmt->bindParam(':raison_social', $businessName);
		$stmt->bindParam(':id_compte', $name);
		$stmt->execute();
	}

	public static function deleteAccount($name)
	{
		$pdo = self::getPdo();
		$sql = "DELETE FROM banque.compte WHERE id = :name";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':name', $name);
		$stmt->execute();
	}

	public static function checkUser($pseudo, $password)
	{
		$pdo = self::getPdo();
		$sql = "SELECT * FROM banque.compte WHERE id = :pseudo";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':pseudo', $pseudo);
		$stmt->execute();
		$result = $stmt->fetch();
		if ($result) {
			if ($result['mdp'] == md5($password)) {
				return $result['type_compte'];
			}
		}
		return null;
	}

	public static function adminAccountTable()
	{
		$pdo = self::getPdo();
		$sql = "SELECT id,num_siren FROM banque.compte JOIN  banque.client ON compte.id = client.id_compte";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach ($result as $row) {
			echo "<tr>";
			echo "<form action='admin.php?id=" . $row['id'] . " method='get'>";
			echo "<td>" . $row['id'] . "</td>";
			echo "<td>" . $row['num_siren'] . "</td>";
			echo "<td><input type='submit' class='btn btn-danger' value='Supprimer' name='delete'></td>";
			echo "</form>";
			echo "</tr>";
		}
	}
	public static function clientRemiseTable($id){
		$pdo = self::getPdo();
		$sql = "SELECT num_remise,traitement_date,type_card,num_carte,num_autorisation,montant,devise from banque.remise where id_client = :id";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach ($result as $row) {
			echo "<tr>";
			echo "<td>" . $row['num_remise'] . "</td>";
			echo "<td>" . $row['traitement_date'] . "</td>";
			echo "<td>" . $row['type_card'] . "</td>";
			echo "<td>" . $row['num_carte'] . "</td>";
			echo "<td>" . $row['num_autorisation'] . "</td>";
			echo "<td>" . $row['montant'].self::getCurrencySymbolFromCode($row['devise'])."</td>";
			echo "</tr>";
		}
	}

	public static function clientUnpaidTable($pseudo)
	{
		$pdo = self::getPdo();
		$sql = "SELECT num_dossier,date_debut,date_fin,montant,devise from banque.di where id_client=:id";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach ($result as $row) {
			echo "<tr>";
			echo "<td>" . $row['num_dossier'] . "</td>";
			echo "<td>" . $row['date_debut'] . "</td>";
			echo "<td>" . $row['date_fin'] . "</td>";
			echo "<td>" . $row['montant'].self::getCurrencySymbolFromCode($row['devise'])."</td>";
			echo "</tr>";
		}
	}

}