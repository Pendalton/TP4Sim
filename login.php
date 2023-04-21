<?php

require_once ('./mysql/ControleurConnexion.php');
$connexion = new ControleurConnexion();

if (isset($_POST['Login']) && isset($_POST['password'])) {
	function validate($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	$login = validate($_POST['Login']);
	$password = validate($_POST['password']);

	if(empty($login)){
		header("Location: header.php?error=User name is required");
		exit();
	} else if(empty($password)){
		header("Location: header.php?error=Password is required");
		exit();
	} else{
		$liste_personnels = $connexion->consulter("Prenom,Mail,password,admin", "test", "", "", "", "", "", "");

		foreach ($liste_personnels as $data){
			if($login == $data['Mail'] && $password == $data['password']){
				echo "Trouv�, " . $data['Prenom'];
				break;
			}
		}
	}
}
else{
	header("Location: header.php");
	exit();
}
