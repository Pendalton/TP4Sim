<?php
class Requete_Suppression
{
	private $connexion;
	
	public function __construct($host,$bdd,$login,$pass)
	{
		$this->host = $host;
		$this->bdd = $bdd;
		$this->login = $login;
		$this->pass = $pass;
		//echo "<br />suppression",$host,$bdd,$login,$pass;
	}
	public function query($from,$where,$orderby,$limit)
	{
			try {
			$dns = 'mysql:host='.$this->host .';dbname='.$this->bdd.';port=3306';
			$connexion = new PDO($dns, $this->login, $this->pass);
		} catch ( Exception $e ) {die ( "Impossible de se connecter: " . $e->getMessage () );}
		
		$req = "DELETE FROM ".$from;
		if(!empty($where))$req .= " WHERE ".$where;
		if(!empty($orderby)) $req .= " ORDER BY ".$orderby;
		if(!empty($limit)) $req .= " LIMIT ".$limit;
//echo "<div style=\"color:#ffff00;\">21 Requete_Suppression",print_r($req),"</div>";
		$sth=$connexion->prepare("$req");
		$sth->execute();
		
		//enregistre la requete dans sif/log
		// require_once ('Enregistreur.php');
		// $enregistreur = new Enregistreur ( $req );
		// $enregistreur->ecrire ();
	}
	public function destruction()
	{$connection=NULL;}
}
?>
