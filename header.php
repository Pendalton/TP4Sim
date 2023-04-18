

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
	//$code = substr($json[$i]['fields']['code_postal'], 0, -1);
	$code = $json[$i]['fields']['code_postal'];
	$town = $json[$i]['fields']['nom_de_la_commune'];
	//echo " <div style=\"color:#ff00ff;\"> GET<pre>", print_r ( $json[$i]['fields'] ), "</pre></div>";
	if(!in_array($code, $code_postal_array))
	{
		$code_postal_array[$code] = array();
	}
	array_push($code_postal_array[$code], $town);

}

ksort($code_postal_array);

foreach (array_keys($code_postal_array) as $option) {
	echo " <div style=\"color:#ff00ff;\"> GET<pre>", print_r ( $option ), print_r($code_postal_array[$option]), "</pre></div>";
}

$Nom = isset($_GET['Nom']) ? $_GET['Nom'] : NULL;
if (! empty($Nom)) {
      $connexion->inserer("test", "Id,Nom", "NULL,'$Nom'");
}
//$liste_personnels = $connexion->consulter("Nom,Prenom,Naiss,Adresse,CodePostal,Ville,Telephone,Mail,Secu", "test", "", "", "", "", "", "");
//$i = 0;
//foreach ($liste_personnels as $data) {
	//$i ++;
	/*
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
}*/

?>
//Application de test SI - Leo QUILLOT & Felix DELESALLE
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
	<script>
		const js_code_postal_array = [<?php echo '"'.implode('","',  array_keys($code_postal_array) ).'"' ?>]
	const dlOptions = js_code_postal_array.map(o => {
    return [`<option value="${o}"></option>`, o.toLowerCase()];
	});

	function completeDataList(e, tag, list_tag, max_ch=99999) {
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

	function fillDataListIfEmpty(tag, list_tag, max_ch=99999) {
		var text = document.getElementById(tag).value;
    	if(!document.getElementById(list_tag).innverHTML && text.length < max_ch) 
		{
        	completeDataList({ target: {} });
    	}
	}

	function checkValidState(tag, max_ch=99999) {
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

