<?php
require_once("config.php");
class ControleurConnexion
{
	private $host,$bdd,$login,$pass;
	public function __construct()
	{
		$this->host = serveur_mysql;
		$this->bdd = 'test';
		$this->login = login_mysql;
		$this->pass = passwd_mysql;
	}
		
	public function consulter($select,$from,$join,$where,$groupby,$having,$orderby,$limit)
	{
		require_once('Requete_Consultation.php');
		$data = new Requete_Consultation($this->host,$this->bdd,$this->login,$this->pass);
		$s=!empty($select)?$select:Null;
		$f=!empty($from)?$from:Null;
		$j=!empty($join)?$join:Null;
		$w=!empty($where)?$where:Null;
		$g=!empty($groupby)?$groupby:Null;
		$h=!empty($having)?$having:Null;
		$o=!empty($orderby)?$orderby:Null;
		$l=!empty($limit)?$limit:Null;
		$return_query = $data->query($s,$f,$j,$w,$g,$h,$o,$l);
		$data->destruction();
		return $return_query;
	}
	public function modifier($table,$set,$where,$orderby,$limit)
	{
		//echo "<br />aff=",$table,$set,$where,$orderby,$limit;
		require_once('Requete_Modification.php');
		$data = new Requete_Modification($this->host,$this->bdd,$this->login,$this->pass);
		$t=!empty($table)?$table:Null;
		$s=!empty($set)?$set:Null;
		$w=!empty($where)?$where:Null;
		$o=!empty($orderby)?$orderby:Null;
		$l=!empty($limit)?$limit:Null;
		$return_query = $data->query($t,$s,$w,$o,$l);
		$data->destruction();
		return $return_query;
	}
	
	public function supprimer($from,$where,$orderby,$limit)
	{
		require_once('Requete_Suppression.php');
		$data = new Requete_Suppression($this->host,$this->bdd,$this->login,$this->pass);
		$f=!empty($from)?$from:Null;
		$w=!empty($where)?$where:Null;
		$o=!empty($orderby)?$orderby:Null;
		$l=!empty($limit)?$limit:Null;
		$return_query = $data->query($f,$w,$o,$l);
		$data->destruction();
		return $return_query;
	}
	
	public function inserer($insert,$tbl_name,$values)
	{
		require_once('Requete_Insertion.php');
		$data = new Requete_Insertion($this->host,$this->bdd,$this->login,$this->pass);
		$i=!empty($insert)?$insert:Null;
		$t=!empty($tbl_name)?$tbl_name:Null;
		$v=!empty($values)?$values:Null;
		
		//echo " <div style=\"color:#ff00ff;\"> v=<pre>", print_r ( $v ), "</pre></div>";
		
		
		$return_query = $data->query($i,$t,$v);
		$data->destruction();
		return $return_query;
	}
		
	public function destruction()
	{
		unset($this->host);
		unset($this->bdd);
		unset($this->login);
		unset($this->pass);
	}
}
?>
