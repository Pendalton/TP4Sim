<?php
// Do something taht I don't understand
require_once ('./mysql/ControleurConnexion.php');

// Variables declaration
$Nom = isset($_GET['Nom']) ? $_GET['Nom'] : NULL;
$contenu_page = null;
$liste = ! empty($liste) ? $liste : null;
$connexion = new ControleurConnexion();
$str = file_get_contents('data/laposte_hexasmal.json');
$json = json_decode($str, true); // decode the JSON into an associative array
$code_postal_array = array();

// Fill the dictionary that associate 'code_postal' and 'town' and sort it by 'code_postal'
for($i = 0; $i<sizeof($json); $i++)
{	
	$code = $json[$i]['fields']['code_postal'];
	$town = $json[$i]['fields']['nom_de_la_commune'];
	if(!in_array($code, $code_postal_array))
	{
		$code_postal_array[$code] = array();
	}
	array_push($code_postal_array[$code], $town);
}
ksort($code_postal_array);

// Do something taht I don't understand
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
                <th>T�l�phone</th>
                <th>Mail</th>
                <th>N� S�curit� Sociale</th>
                <th>Actions</th>
            </tr>
            $liste
        </table>
    </div>
?>

<script>
	// Variables declaration
	const js_code_postal_array = [<?php echo '"'.implode('","',  array_keys($code_postal_array) ).'"' ?>]
	const dlOptions = js_code_postal_array.map(o => {return [`<option value="${o}"></option>`, o.toLowerCase()];});

	function completeDataList(e, tag, list_tag, max_ch=99999)
	// tag : str, the html tag of the input
	// list_tag : str, the html tag of the completion list
	// max_ch : int, the maximum number of character in the text input
	{
		var element =document.getElementById(tag)
		if(element)
		{
			var text = document.getElementById(tag).value;
    		const fill = val => document.getElementById(list_tag).innerHTML = val;
    		if(!e.target.value) 
			{
        		fill(dlOptions.reduce((sum, [html]) => sum + html, ''));
    		} 
			else if(!(e instanceof InputEvent)) { // OR: else if(!e.inputType)
        		e.target.blur();
    		} 
			else 
			{
        		const inputValue = e.target.value.toLowerCase();
        		let result = '';
				if(text.length < max_ch)
				{
        			for (const [html, valuePattern] of dlOptions) 
					{
            			if (!valuePattern.indexOf(inputValue)) 
						{
            	    		result += html;
            			} 
						else if (result) 
						{
            	    		break;
            			}
        			}
				}
        		fill(result);
    		}
		}

	}

	// Function called by text input when focus in, fill the list of possible completion
	function fillDataListIfEmpty(tag, list_tag, max_ch=99999) 
	// tag : str, the html tag of the input
	// list_tag : str, the html tag of the completion list
	// max_ch : int, the maximum number of character in the text input
	{
		var text = document.getElementById(tag).value;
    	if(!document.getElementById(list_tag).innverHTML && text.length < max_ch) 
		{
        	completeDataList({ target: {} });
    	}
	}

	// Function called by text input when focus out, changes the style depending of the validity of the content
	function checkValidState(tag, max_ch=99999)
	// tag : str, the html tag of the input
	// max_ch : int, the maximum number of character in the text input
	{
		var text = document.getElementById(tag).value;
		if(text.length < max_ch || !js_code_postal_array.includes(text))
		{
			document.getElementById(tag).style.backgroundColor = "red";
		}
		else
		{
			document.getElementById(tag).style.backgroundColor = null;
		}
	}
	</script>
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
        <button class="tablinks" onclick="openCity(event, 'donnees')">Donn�es</button>
    </div>

	<div id="formulaire" class="tabcontent">
	
<form action="index.php" method="GET" enctype="application/x-www-form-urlencoded">
    <div style="display:flex">
        <div style="margin-right:5px">
            <p>Saisir le nom :</p>
            <input type="text" name="Nom" placeholder="NOM" />
            <input type="text" name="Prenom" placeholder="Pr�nom" />
        </div>
        <div style="margin-left:5px">
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
        <div style="margin-right:5px">
            <p>Saisir le num�ro de t�l�phone :</p>
            <input type="tel" name="Telephone" style="width:200px" />
        </div>
        <div style="margin-left:5px">
            <p>Saisir l'adresse mail :</p>
            <input type="email" name="Mail" style="width:200px" />
        </div>
    </div>
    <div>
	    <p>Saisir le numero de securite social</p>
	    <input type="text" name="Secu" />
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


<?php
echo $contenu_page;
?>

