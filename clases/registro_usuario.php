<?php
/**
* 
*/
class RegUsuario
{
	private $_id;
	private $_id_usuario;
	private $_login;
	private $_logout;
	
	function __construct($id,$idUser,$login,$logout)
	{
		$this->_id = $id;
		$this->_id_usuario = $idUser;
		$this->_login = $login;
		$this->_logout = $logout;
	}
	public function GetId()
	{
		return $this->_id;
	}
	public function GetIdUser()
	{
		return $this->_id_usuario;
	}
	public function GetLogin()
	{
		return $this->_login;
	}
	public function GetLogout()
	{
		return $this->_logout;
	}
	public function SetLogin($fecha)
	{
		$this->_login = $fecha;
	}
	public function SetLogout($fecha)
	{
		$this->_logout = $fecha;
	}

	public static function ArrayRegistros()
	{
		$rta="no";
		$consulta = "SELECT * FROM registro_usuarios";
		$ListaDeRegistros = array();
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$contenido = $pdo->query($consulta);
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
			{
				$unRegistro = new RegUsuario($linea["id"],$linea["id_usuario"],$linea["login"],$linea["logout"]);
				array_push($ListaDeRegistros, $unRegistro);
			}				
		return $ListaDeRegistros;
	}
	public static function ModificarUnRegistro($registro)
	{		
			$spliteado = split(" ", $registro);
			$fecha = $spliteado[0];
			$hora = $spliteado[2];
			$fechaspliteada = split("-", $fecha);
			$nuevafecha = $fechaspliteada[2]."-".$fechaspliteada[1]."-".$fechaspliteada[0]." ".$hora;

			return $nuevafecha;
	}
	public static function ModificarBase($obj)
	{
		$consulta = "UPDATE registro_usuarios SET id_usuario=:id_usuario, login=:login, logout=:logout WHERE id=:id";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare($consulta);
		$db->bindValue(':id_usuario',$obj->GetIdUser());
		$db->bindValue(':login',RegUsuario::ModificarUnRegistro($obj->GetLogin()));
		$db->bindValue(':logout',RegUsuario::ModificarUnRegistro($obj->GetLogout()));
		$db->bindValue(':id',$obj->GetId());
		$db->execute();
	}
}
?>