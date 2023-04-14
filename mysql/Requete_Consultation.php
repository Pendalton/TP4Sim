<?php
class Requete_Consultation
{
	private $connexion;
	private $host;
	private $bdd;
	private $login;
	private $pass;
	public function __construct($host,$bdd,$login,$pass)
	{
		$this->host = $host;
		$this->bdd = $bdd;
		$this->login = $login;
		$this->pass = $pass;
	}
	public function query($select,$from,$join,$where,$groupby,$having,$orderby,$limit)
	{
		try {
			$dns = 'mysql:host='.$this->host .';dbname='.$this->bdd.';port=3306';
			$connexion = new PDO($dns, $this->login, $this->pass);
			$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch ( Exception $e ) {die ( "(consultation)Impossible de se connecter: " . $e->getMessage () );}
		$req = "SELECT ".$select." FROM ".$from;
		if(!empty($join)) $req .= " ".$join;
		if(!empty($where)) $req .= " WHERE ".$where;
		if(!empty($groupby)) $req .= " GROUP BY ".$groupby;
		if(!empty($having)) $req .= " HAVING ".$having;
		if(!empty($orderby)) $req .= " ORDER BY ".$orderby;
		if(!empty($limit)) $req .= " LIMIT ".$limit;
//echo "<div style=\"color:#ff00ff;\">24 Requete_Consultation<pre>",print_r($req),"</pre></div>";
		$sth=$connexion->prepare("$req");
		$sth->execute();
		$resultat = $sth->fetchAll();
return $resultat; 

	}
	public function destruction()
	{$connection=NULL;}
}
?>
