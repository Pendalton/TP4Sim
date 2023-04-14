<?php
 //echo " <div style=\"color:#ff00ff;\"> GET<pre>", print_r ( $_GET ), "</pre></div>";
$liste = ! empty($liste) ? $liste : null;
require_once ('./mysql/ControleurConnexion.php');
$connexion = new ControleurConnexion();

$str = file_get_contents('data/laposte_hexasmal.json');
$json = json_decode($str, true); // decode the JSON into an associative array
$code_postal_array = array();

for($i = 0; $i<sizeof($json); $i++)
{
	array_push($code_postal_array, $json[$i]['fields']['code_postal']);
}

$Nom = isset($_GET['Nom']) ? $_GET['Nom'] : NULL;
if (! empty($Nom)) {
      $connexion->inserer("test", "Id,Nom", "NULL,'$Nom'");
}
$liste_personnels = $connexion->consulter("Nom,Prenom,Naiss,Adresse,CodePostal,Ville,Telephone,Mail,Secu", "test", "", "", "", "", "", "");
$i = 0;
foreach ($liste_personnels as $data) {
    $i ++;
    $Nom = ! empty($data['Nom']) ? utf8_encode($data['Nom']) : "";
	$Prenom = ! empty($data['Prenom']) ? utf8_encode($data['Prenom']) : "";
	$Date = ! empty($data['Naiss']) ? utf8_encode($data['Naiss']) : "";
	$Adresse = ! empty($data['Adresse']) ? utf8_encode($data['Adresse']) : "";
	$CodePostal = ! empty($data['CodePostal']) ? utf8_encode($data['CodePostal']) : "";
	$Ville = ! empty($data['Ville']) ? utf8_encode($data['Ville']) : "";
	$Telephone = ! empty($data['Telephone']) ? utf8_encode($data['Telephone']) : "";
	$Mail = ! empty($data['Mail']) ? utf8_encode($data['Mail']) : "";
	$Secu = ! empty($data['Secu']) ? utf8_encode($data['Secu']) : "";
    $liste .= <<<EOF
    <tr>
    	<td>$Nom $Prenom</td>
		<td>$Date</td>
		<td>$Adresse,$CodePostal,$Ville</td>
		<td>$Telephone</td>
		<td>$Mail</td>
		<td>$Secu</td>
		<td>
			<div class="btn-group">
				<input type="button" onclick="edit()" value="Modif"/>
				<input type="button" onclick="suppr()" value="Suppr"/>
			</div>
		</td>
    </tr>
    EOF;
}
$contenu_page = <<<EOF
    <div id="div_flow">
        <table>
            <tr id="entete_tableau">
                <th>Nom</th>
                <th>Date Naissance</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Mail</th>
                <th>N° Sécurité Sociale</th>
                <th>Actions</th>
            </tr>
            $liste
        </table>
    </div>

EOF;
echo <<<EOF
 <!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="apparence.css">
<title>Test SI</title>
</head>
<body>
Application de test SI - Leo QUILLOT & Felix DELESALLE
<form action="index.php" method="GET" enctype="application/x-www-form-urlencoded">
<div>
	<p>Saisir le nom</p>
	<p><input type="text" name="Nom"  /></p>
</div>
<div>
	<p>Saisir le prenom</p>
	<p><input type="text" name="Prenom"  /></p>
</div>
<div>
	<p>Saisir la date</p>
	<p><input type="date" name="Naiss"  /></p>
</div>
<div>
	<p>Saisir l'adresse</p>
	<p><input type="text" name="Adresse"  /></p>
    <p><input type="text" name="CodePostal"  /></p>
    <p><input type="text" name="Ville"  /></p>
</div>
<div>
	<p>Saisir le numero de telephone</p>
	<p><input type="tel" name="Telephone"  /></p>
</div>
<div>
	<p>Saisir l'adresse mail</p>
	<p><input type="email" name="Mail"  /></p>
</div>
<div>
	<p>Saisir le numero de securite social</p>
	<p><input type="text" name="Secu"  /></p>
</div>
<div>
	<p><input name="bouton_valider" type="submit" value="Valider" /></p>
</div>
<div>
$contenu_page
</div>
</form>
</body>

EOF;