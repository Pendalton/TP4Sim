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

Application de test SI - Leo QUILLOT & Felix DELESALLE
<form action="index.php" method="GET" autocomplete="off" enctype="application/x-www-form-urlencoded">
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
	<p>Saisir adresse</p>
	<p><input type="text" name="Adresse"  /></p>
	<input type="text" name="CodePostal" minlength="5" maxlength="5" required id="code_postal_tag" list="code_postal_list" oninput="completeDataList(event, 'code_postal_tag', 'code_postal_list', 5)" onfocus="fillDataListIfEmpty('code_postal_tag', 'code_postal_list', 5)" onfocusout="checkValidState('code_postal_tag', 5)">
	<datalist id="code_postal_list"></datalist>
    <p><input type="text" name="Ville"  /></p>
</div>
<div>
	<p>Saisir le numero de telephone</p>
	<p><input type="tel" name="Telephone"  /></p>
</div>
<div>
	<p>Saisir adresse mail</p>
	<p><input type="email" name="Mail"  /></p>
</div>
<div>
	<p>Saisir le numero de securite social</p>
	<p><input type="text" name="Secu"  /></p>
</div>
<div>
	<p><input name="bouton_valider" type="submit" value="Valider" /></p>
</div>
</form>
<div>


<?php
echo $contenu_page;
?>

