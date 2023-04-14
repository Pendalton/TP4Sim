<?php
class Requete_Modification
{
	private $connexion;
	
	public function __construct($host,$bdd,$login,$pass)
	{
		$this->host = $host;
		$this->bdd = $bdd;
		$this->login = $login;
		$this->pass = $pass;
		//echo "<br />constructeur_modification",$host,$bdd,$login,$pass,"<br />";
	}
	public function query($table,$set,$where,$orderby,$limit)
	{
			try {
			$dns = 'mysql:host='.$this->host .';dbname='.$this->bdd.';port=3306';
			$connexion = new PDO($dns, $this->login, $this->pass);
		} catch ( Exception $e ) {die ( "Impossible de se connecter: " . $e->getMessage () );}
		
		$req = "UPDATE ".$table." SET ".$set;
		if(!empty($where)) $req .= " WHERE ".$where;
		if(!empty($orderby)) $req .= " ORDER BY ".$orderby;
		if(!empty($limit)) $req .= " LIMIT ".$limit;
//echo "<div style=\"color:#ff0000;\">24 Requete_Modification=",$req,"</div>";
		$sth=$connexion->prepare("$req");
		$sth->execute();
		
		//enregistre la requete dans sif/log
		require_once ('Enregistreur.php');
		$enregistreur = new Enregistreur ( $req );
//echo "<div style=\"color:#ff0000;\">24 Enregistrement_rq=",$req,"</div>";
		
		$enregistreur->ecrire ();
	}
	public function destruction()
	{$connection=NULL;}
}
?>
