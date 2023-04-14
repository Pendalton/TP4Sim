<?php
class Requete_Insertion 
{
	private $connexion;
	public function __construct($host, $bdd, $login, $pass) 
	{
		$this->host = $host;
		$this->bdd = $bdd;
		$this->login = $login;
		$this->pass = $pass;
		// echo "<br />insertion",$host,$bdd,$login,$pass;
	}
	public function query($insert, $tbl_name, $values) 
	{
		try {
			$dns = 'mysql:host=' . $this->host . ';dbname=' . $this->bdd . ';port=3306';
			$connexion = new PDO ( $dns, $this->login, $this->pass );
		} catch ( Exception $e ) {
			die ( "(insertion)Impossible de se connecter: " . $e->getMessage () );
		}
		$req = "INSERT INTO " . $insert . " (" . $tbl_name . ") VALUES(" . $values . ")";
//echo "<div style=\"color:#ff8855;\">18 Requete_Insertion  ",print_r($req),"</div>";
		$sth = $connexion->prepare ( "$req" );
		$sth->execute ();
	}
	public function destruction() {
		$connexion = NULL;
	}
}
?>
