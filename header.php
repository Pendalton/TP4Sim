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
		<td>$Adresse, $CodePostal, $Ville</td>
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
        <table class="center">
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

    <div class="tab">
        <button class="tablinks" onclick="openCity(event, 'formulaire')" id="defaultOpen">Formulaire</button>
        <button class="tablinks" onclick="openCity(event, 'donnees')">Données</button>

        <button class="tablinks" id="modalBtn" style="float:right">Se connecter</button>
    </div>

    <div id="LoginModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Connexion</h2>
            </div>
            <div class="modal-body" style="padding-top:20px">
                <div style="display:flex; flex-direction:column; align-items:center">
                    <input type="text" name="Login" placeholder="Utilisateur" style="width:300px;margin-bottom:8px" />
                    <input type="password" name="password" placeholder="Mot de passe" style="width: 300px; margin-top: 8px;margin-bottom:16px" />
                    <input name="bouton_valider" type="submit" value="Connexion" style="width:150px" />
                </div>
            </div>
            <div class="modal-footer">
                <h3>InkPlant</h3>
            </div>
        </div>
    </div>

	<div id="formulaire" class="tabcontent">
	
<form action="index.php" method="GET" enctype="application/x-www-form-urlencoded">
    <div style="display:flex">
                <div style="margin-right:10px">
                    <p>Saisir le nom :</p>
                    <input type="text" name="Nom" placeholder="NOM" />
                    <input type="text" name="Prenom" placeholder="Prénom" />
                </div>
                <div>
                    <p>Saisir la date de naissance :</p>
                    <input type="date" name="Naiss" />
                </div>
            </div>
    <div>
    	<p>Saisir l'adresse</p>
    	<input type="text" name="Adresse" placeholder="Adresse"/>
    	<input type="text" name="CodePostal" placeholder="Code Postal"/>
    	<input type="text" name="Ville" placeholder="Ville"/>
    </div>
    <div style="display:flex">
                <div style="margin-right:10px">
                    <p>Saisir le numéro de téléphone :</p>
                    <input type="tel" name="Telephone" style="width:100%" />
                </div>
                <div style="margin-left:10px">
                    <p>Saisir l'adresse mail :</p>
                    <input type="email" name="Mail" style="width:200px" />
                </div>
            </div>
    <div style="display:flex">
                <div style="margin-right:10px">
                    <p>Saisir le numéro de sécurité sociale :</p>
                    <input type="text" name="Secu" style="width:100%"/>
                </div>
                <div style="margin-left:10px">
                    <p>Saisir le mot de passe :</p>
                    <input type="text" name="Password" />
                </div>
            </div>
    <div>
	    <input name="bouton_valider" type="submit" value="Valider" />
    </div>
</div>
    </form>
    <div class="tabcontent" id="donnees">
        $contenu_page
    </div>
    <br/>
    Application de test SI - Leo QUILLOT & Felix DELESALLE
        <script>
        document.getElementById("defaultOpen").click();

        function openCity(evt, cityName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>

EOF;