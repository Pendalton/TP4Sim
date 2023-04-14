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
$liste_personnels = $connexion->consulter("Id,nom", "test", "", "", "", "", "", "");
$i = 0;
foreach ($liste_personnels as $data) {
    $i ++;
    $Id = ! empty($data['Id']) ? utf8_encode($data['Id']) : "";
    $nom = ! empty($data['nom']) ? utf8_encode($data['nom']) : "";
    $liste .= <<<EOF
    <tr>
    	<td>$Id</td><td>$nom</td>
    </tr>
    EOF;
}
$contenu_page = <<<EOF
<div id="div_flow">
<table class="border">
	<tr id="entete_tableau">
		<td>Id</td>
		<td>Nom</td>
	</tr>
	$liste
<!--    <tr>
        <td align="center" >
        <label> <br />
            <input type="submit" name="bouton_valider" value=" Valider " />
        </label>
        <label>
            <input type="reset" name="Nom" value="Annuler" />
        </label></td>
    </tr>  -->
</table>
</div>
</form>
EOF;
echo <<<EOF
 <!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8" />
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
	<p><input type="date" name="Date"  /></p>
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