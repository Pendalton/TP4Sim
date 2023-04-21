<?php
// Do something taht I don't understand
require_once ('./mysql/ControleurConnexion.php');
require_once ('./mysql/Requete_Suppression.php');

// Variables declaration
$Nom = isset($_GET['Nom']) ? $_GET['Nom'] : NULL;
$contenu_page = null;
$liste = ! empty($liste) ? $liste : null;
$connexion = new ControleurConnexion();
$suppression = new Requete_Suppression();
$str = file_get_contents('data/laposte_hexasmal.json');
$json = json_decode($str, true); // decode the JSON into an associative array
$code_postal_array = array();
$liste_personnels = $connexion->consulter("Nom,Prenom,Naiss,Adresse,CodePostal,Ville,Telephone,Mail,Secu", "test", "", "", "", "", "", "");
$i = -1;
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
    $liste .= 
<<<EOF
    <tr>
    	<td>$Nom $Prenom</td>
		<td>$Date</td>
		<td>$Adresse, $CodePostal, $Ville</td>
		<td>$Telephone</td>
		<td>$Mail</td>
		<td>$Secu</td>
		<td>
			<div class="btn-group">
				<input type="button" onclick="edit($i)" value="Modif"/>
				<input type="button" onclick="suppr($i)" value="Suppr"/>
			</div>
		</td>
    </tr>
EOF;
}
	$contenu_page = 
<<<EOF
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

// NEW

?>

<script>
	// console.log('Script begins !');
	// Variables declaration
	//const js_code_postal_array = [<?php //echo '"'.implode('","',  array_keys($code_postal_array) ).'"' ?>];
	//const dlOptions = js_code_postal_array.map(o => {return [`<option value="${o}"></option>`, o.toLowerCase()];});
	document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });

	function completeDataList(e, tag, list_tag, max_ch=99999)
	// tag : str, the html tag of the input
	// list_tag : str, the html tag of the completion list
	// max_ch : int, the maximum number of character in the text input
	{
		
		var dlOptions = null;
		document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });
		
		switch (tag)
		{
			case 'code_postal_tag':
				var js_code_postal_array = [<?php echo '"'.implode('","',  array_keys($code_postal_array) ).'"' ?>];
				dlOptions = js_code_postal_array.map(o => {return [`<option value="${o}"></option>`, o.toLowerCase()];});
				break;
			case 'ville_tag':
				console.log("case ville tag");
				var code_postal_widget = document.getElementById('code_postal_tag');
				console.log(code_postal_widget);
				if(code_postal_widget)
				{
					var code_postal = code_postal_widget.value;
					// console.log(code_postal);
					document.cookie = "code_postal = " + String(code_postal);
					var debug_array = [<?php echo '"'.implode('","',  array_keys($_COOKIE) ).'"' ?>];
					console.log(debug_array);
					var js_variable_as_placeholder = <?= json_encode($code_postal_array[$_COOKIE['code_postal']], JSON_HEX_TAG); ?>;
					console.log(js_variable_as_placeholder);
					// console.log($_COOKIE['code_postale'])
					var js_ville_array = [<?php echo '"'.implode('","',  $code_postal_array[$_COOKIE['code_postal']] ).'"' ?>];
					dlOptions = js_ville_array.map(o => {return [`<option value="${o}"></option>`, o.toLowerCase()];});
				}
				break;
			default:
				//dlOptions = null;
		}
		

		var element = document.getElementById(tag);
		if(element && dlOptions)
		{
			var text = element.value;
    		var fill = val => document.getElementById(list_tag).innerHTML = val;
    		if(!e.target.value) 
			{
        		fill(dlOptions.reduce((sum, [html]) => sum + html, ''));
    		} 
			else if(!(e instanceof InputEvent)) 
			{ // OR: else if(!e.inputType)
        		e.target.blur();
    		} 
			else 
			{
        		var inputValue = e.target.value.toLowerCase();
        		let result = '';
				if(text.length < max_ch)
				{
        			for (var [html, valuePattern] of dlOptions) 
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
	function fillDataListIfEmpty(tag, list_tag, max_ch=99998) 
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
	function checkValidState(tag, max_ch=-1)
	// tag : str, the html tag of the input
	// max_ch : int, the maximum number of character in the text input
	{
		document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });
		var js_array;
		switch (tag)
		{
			case 'code_postal_tag':
				js_array = [<?php echo '"'.implode('","',  array_keys($code_postal_array) ).'"' ?>];
				break;
			case 'ville_tag':
				var code_postal_widget = document.getElementById('code_postal_tag');
				if(code_postal_widget)
				{
					var code_postal = code_postal_widget.value;
					console.log(code_postal);
					document.cookie = "code_postal = " + String(code_postal);
					js_array = [<?php echo '"'.implode('","',  $code_postal_array[$_COOKIE['code_postal']] ).'"' ?>];
				}
				break;
			default:
		}

		var text = document.getElementById(tag).value;
		console.log(text.length < max_ch);
		console.log(!js_array.includes(text));
		console.log(max_ch);
		if(text.length < max_ch || !js_array.includes(text))
		{
			document.getElementById(tag).style.backgroundColor = "red";
		}
		else
		{
			document.getElementById(tag).style.backgroundColor = null;
		}
	}

	// Handle the switch between tabs
	function openCity(evt, cityName) 
	// evt : Event, the button that was clicked
	// cityName : str, name of the new tab
	{
	// Declare all variables
	var i, tabcontent, tablinks;

	// Get all elements with class="tabcontent" and hide them
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) 
	{
		tabcontent[i].style.display = "none";
	}

	// Get all elements with class="tablinks" and remove the class "active"
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) 
	{
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}

	// Show the current tab, and add an "active" class to the button that opened the tab
	document.getElementById(cityName).style.display = "block";
	evt.currentTarget.className += " active";
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

        <button class="tablinks" id="modalBtn" style="float:right">Se connecter</button>
    </div>

    <div id="LoginModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Connexion</h2>
            </div>
            <div class="modal-body" style="padding-top:20px">
                <?php if(isset($_GET['error'])){ ?>
                    <p class="error"> <?php echo $_GET['error']; ?> </p> 
                <?php } ?>
                <div style="display:flex; flex-direction:column; align-items:center">
                    <form action="login.php" method="post">
                        <input type="text" name="Login" placeholder="Utilisateur" style="width:300px;margin-bottom:8px" />
                        <input type="password" name="password" placeholder="Mot de passe" style="width: 300px; margin-top: 8px;margin-bottom:16px" />
                        <input name="bouton_valider" type="submit" value="Connexion" style="width:150px" />
                    </form>
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
                    <input type="text" name="Prenom" placeholder="Pr�nom" />
                </div>
                <div>
                    <p>Saisir la date de naissance :</p>
                    <input type="date" name="Naiss" />
                </div>
            </div>
    <div>
    	<p>Saisir l'adresse</p>
    	<input type="text" name="Adresse" placeholder="Adresse"/>
		<input type="text" name="CodePostal" placeholder="Code Postal" minlength="5" maxlength="5" required id="code_postal_tag" list="code_postal_list" oninput="completeDataList(event, 'code_postal_tag', 'code_postal_list', 5)" onfocus="fillDataListIfEmpty('code_postal_tag', 'code_postal_list', 5)" onfocusout="checkValidState('code_postal_tag', 5)"/>
		<datalist id="code_postal_list"></datalist>
		<!-- <input type="text" name="Ville" placeholder="Ville"/> -->
		<input type="text" name="Ville" placeholder="Ville"  required id="ville_tag" list="ville_list" oninput="completeDataList(event, 'ville_tag', 'ville_list')" onfocus="fillDataListIfEmpty('ville_tag', 'ville_list')" onfocusout="checkValidState('ville_tag')"/>
		<datalist id="ville_list"></datalist>
    </div>
    <div style="display:flex">
                <div style="margin-right:10px">
                    <p>Saisir le num�ro de t�l�phone :</p>
                    <input type="tel" name="Telephone" style="width:100%" />
                </div>
                <div style="margin-left:10px">
                    <p>Saisir l'adresse mail :</p>
                    <input type="email" name="Mail" style="width:200px" />
                </div>
            </div>
    <div style="display:flex">
                <div style="margin-right:10px">
                    <p>Saisir le num�ro de s�curit� sociale :</p>
                    <input type="text" name="Secu" style="width:100%"/>
                </div>
                <div style="margin-left:10px">
                    <p>Saisir le mot de passe :</p>
                    <input type="text" name="Password" />
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
        <?php echo $contenu_page; ?>
    </div>
    <br/>
    Application de test SI - Leo QUILLOT & Felix DELESALLE
    <script>
		// Select the first tab
		document.getElementById("defaultOpen").click();




        var modal = document.getElementById("LoginModal");

        // Get the button that opens the modal
        var btn = document.getElementById("modalBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function suppr(id){
            document.getElementById(id).style.display = "none";
			$suppression->query("test","Id ="+id,"","");
        }

        function edit(id){
            alert('Modification pas impl�ment�e');
        }
    </script>
</body>