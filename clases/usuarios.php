<?php
/**
* 
*/
class Usuario
{
	#ATRIBUTOS-----------------------------------------------------------------------------------
	private $_id;
	private $_password;
	private $_nombre;
	private $_apellido;
	private $_tipo;
	private $_turno;
	private $_habilitado;
	//private $_fondo;
	//private $_foto;

	#CONSTRUCTOR---------------------------------------------------------------------------------
	function __construct($nombre,$password,$apellido,$tipo,$turno,$id=null,$habilitado=1)
	{
		$this->_id = $id;
		$this->_nombre = $nombre;
		$this->_password = $password;
		$this->_apellido = $apellido;
		$this->_tipo = $tipo;
		$this->_turno = $turno;
		$this->_habilitado = $habilitado;
		//$this->_fondo = $fondo;
		//$this->_foto = $foto;
	}

	#GETTERS Y SETTERS----------------------------------------------------------------------------
	public function GetId()
	{
		return $this->_id;
	}
	#---------------------------------------------------------------------------------------------
	public function GetPassword()
	{
		return $this->_password;
	}
	#---------------------------------------------------------------------------------------------
	public function GetNombre()
	{
		return $this->_nombre;
	}
	public function SetNombre($nombre)
	{
		$this->_nombre = $nombre;
	}
	#---------------------------------------------------------------------------------------------
	public function GetApellido()
	{
		return $this->_apellido;
	}
	public function SetApellido($apellido)
	{
		$this->_apellido = $apellido;
	}
	#---------------------------------------------------------------------------------------------
	public function GetTipo()
	{
		return $this->_tipo;
	}
	public function SetTipo($tipo)
	{
		$this->_tipo = $tipo;
	}
	#---------------------------------------------------------------------------------------------
	public function GetTurno()
	{
		return $this->_turno;
	}
	public function SetTurno($turno)
	{
		$this->_turno = $turno;
	}
	#---------------------------------------------------------------------------------------------
	public function GetHabilitado()
	{
		return $this->_habilitado;
	}
	public function SetHabilitado($habilito)
	{
		$this->_habilitado = $habilito;
	}
	#---------------------------------------------------------------------------------------------
	/*public function GetFondo()
	{
		return $this->_fondo;
	}
	public function SetFondo($fondo)
	{
		$this->_fondo = $fondo;
	}
	#---------------------------------------------------------------------------------------------
	public function GetFoto()
	{
		return $this->_foto;
	}
	public function SetFoto($foto)
	{
		$this->_foto = $foto;
	}*/
	#---------------------------------------------------------------------------------------------

	#ALTA DE USUARIO------------------------------------------------------------------------------
	public static function AltaUsuario($obj)
	{
		$nombre = Usuario::FormatoString($obj->GetNombre());
		$apellido = Usuario::FormatoString($obj->GetApellido());
		
		$resultado = FALSE;
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare("INSERT INTO usuarios (nombre,password,apellido,tipo,turno,habilitado)VALUES(:nombre,:password,:apellido,:tipo,:turno,:habilitado)");
		$db->bindValue(':nombre',$nombre);
		$db->bindValue(':password',$obj->GetPassword());
		$db->bindValue(':apellido',$apellido);
		$db->bindValue(':tipo',$obj->GetTipo());
		$db->bindValue(':turno',$obj->GetTurno());
		$db->bindValue(':habilitado',$obj->GetHabilitado());
		if($db->execute())
		{
			$resultado = Usuario::TablaUsuarios();
		}
		return $resultado;	
	}
	#BAJA DE USUARIO------------------------------------------------------------------------------
	public static function BajaUsuario($id)
	{
		$resultado = FALSE;
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
		$db->bindValue(':id',$id);
		if ($db->execute())
		{
			$resultado = Usuario::TablaUsuarios();
		}
		return $resultado;
	}
	#MODIFICACION DE USUARIO-----------------------------------------------------------------------
	public static function ModiUsuario($obj)
	{
		$resultado = FALSE;
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare("UPDATE usuarios SET nombre=:nombre, apellido=:apellido, tipo=:tipo, turno=:turno,password=:password WHERE id=:id");
		$db->bindValue(':nombre',$obj->GetNombre());
		$db->bindValue(':apellido',$obj->GetApellido());
		$db->bindValue(':tipo',$obj->GetTipo());
		$db->bindValue(':turno',$obj->GetTurno());
		$db->bindValue(':id',$obj->GetId());
		$db->bindValue(':password',$obj->GetPassword());
		if($db->execute())
		{
			$resultado = Usuario::TablaUsuarios();
		}
		return $resultado;
	}
	#SUSPENCION DE USUARIO-----------------------------------------------------------------------
	public static function SuspenderUsuario($id)
	{
		$resultado = FALSE;
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare("UPDATE usuarios SET habilitado=:habilitado WHERE id=:id");
		$db->bindValue(':id',$id);
		$db->bindValue(':habilitado',0);
		if($db->execute())
		{
			$resultado = Usuario::TablaUsuarios();
		}
		return $resultado;
	}
	public static function HabilitarUsuario($id)
	{
		$resultado = FALSE;
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare("UPDATE usuarios SET habilitado=:habilitado WHERE id=:id");
		$db->bindValue(':id',$id);
		$db->bindValue(':habilitado',1);
		if($db->execute())
		{
			$resultado = Usuario::TablaUsuarios();
		}
		return $resultado;
	}	
	#TRAER ARRAY USUARIOS---------------------------------------------------------------------------
	public static function TraerUsuarios()
	{
		$ListaDeUsuarios = array();
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$contenido = $pdo->query("SELECT * FROM usuarios");
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
			{
				$unUsuario = new Usuario($linea["nombre"],$linea["password"],$linea["apellido"],$linea["tipo"],$linea["turno"],$linea["id"],$linea["habilitado"]);
				array_push($ListaDeUsuarios, $unUsuario);
			}				
		return $ListaDeUsuarios;
	}
	#TRAER SOLO UN USUARIO---------------------------------------------------------------------------
	public static function TraerUnUsuario($id)
	{
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare("SELECT * FROM usuarios WHERE id=:id");
		$db->bindValue(':id',$id);
		$db->execute();
		while($linea = $db->fetch(PDO::FETCH_ASSOC))
		{
			$miUsuario = new Usuario($linea["nombre"],$linea["password"],$linea["apellido"],$linea["tipo"],$linea["turno"],$linea["id"],$linea["habilitado"]);
		}
		$respuesta=$miUsuario->GetId()."*";
		$respuesta.=$miUsuario->GetPassword()."*";
		$respuesta.=$miUsuario->GetNombre()."*";
		$respuesta.=$miUsuario->GetApellido()."*";
		$respuesta.=$miUsuario->GetTipo()."*";
		$respuesta.=$miUsuario->GetTurno()."*";
		$respuesta.=$miUsuario->GetHabilitado();

		return $respuesta;

	}
	# LOGIN ============================================================================================================================================
	public static function LoginUsuario($usuario,$password)
	{
		$rta = false;
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare("SELECT * FROM usuarios WHERE nombre=:nombre AND password=:password AND habilitado != 0");
		$db->bindValue(':nombre',$usuario);
		$db->bindValue(':password',$password);
		$db->execute();

		while($linea = $db->fetch(PDO::FETCH_ASSOC))
		{
			$unUsuario = new Usuario($linea["nombre"],$linea["password"],$linea["apellido"],$linea["tipo"],$linea["turno"],$linea["id"]);
		}
		if(isset($unUsuario))
		{

			$_SESSION["id"]= $unUsuario->GetId();
			$_SESSION["nombre"] = $unUsuario->GetNombre();
			$_SESSION["turno"] = $unUsuario->GetTurno();
			$_SESSION["tipo"] = $unUsuario->GetTipo();
			$_SESSION["login"] = time();
			$_SESSION["apellido"] = $unUsuario->GetApellido();
			var_dump($_SESSION);
			$rta =true;			
		}

		return $rta;
	}
	# CERRAR SESION E INGRESAR A LA BASE DE REGISTRO DE USUARIOS ========================================================================================
	public static function CerrarSesion($id,$login)
	{
		$rta = FALSE;
		$inicio = Usuario::GenerarDate($login);
		$fin = Usuario::GenerarDate(time());
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare("INSERT INTO registro_usuarios (id_usuario,login,logout)VALUES(:id,:login,:logout)");
		$db->bindValue(':id',$id);
		$db->bindValue(':login',$inicio);
		$db->bindValue(':logout',$fin);
		if($db->execute())
		{
			$rta = TRUE;
		}
		return $rta;
	}
	# GENERAR FECHA PARA INGRESO A BASE ===================================================================================================================
	public static function GenerarDate($timestamp)
	{
		$fecha = getdate($timestamp);
		$dia = $fecha["mday"];
		$mes = $fecha["mon"];
		$anio = $fecha["year"];
		$hora = $fecha["hours"];
		$minuto = $fecha["minutes"];
		$fechaFormateada = date($anio."-".$mes."-".$dia."  ".$hora.":".$minuto);
		return $fechaFormateada;
	}
	# TABLA DE USUARIOS ====================================================================================================================================
	public static function TablaUsuarios()
	{
		$inicio = "<table class='table table-hover'>
						<thead>
							<tr class='info'>
								<th>Id</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Turno</th>
								<th>Tipo</th>";
								if($_SESSION["tipo"] == "admin")
								{
									$inicio.="<th>Accion</th>
												</tr>
											</thead>";	
								}else
									{
										$inicio.="</tr>
													</thead>";
									}
		$fin = "</table>";
		$datos = "";
		$misUsuarios = array();
		$misUsuarios = Usuario::TraerUsuarios();
		foreach ($misUsuarios as $users)
		{
			$datos.="<tr>
						<td>".$users->GetId()."</td>
						<td>".$users->GetNombre()."</td>
						<td>".$users->GetApellido()."</td>
						<td>".$users->GetTurno()."</td>
						<td>".$users->GetTipo()."</td>";
						if($_SESSION["tipo"] == "admin")
						{
							$datos.="<td>
										<button class='btn btn-danger btn-xs' onclick='EliminarUsuario(\"".$users->GetId()."\")'>Eliminar</button>
										<button class='btn btn-info btn-xs' onclick='ModificarUser(\"".$users->GetId()."\")'>Modificar</button>
										<button class='btn btn-xs' onclick='HistorialUser(\"".$users->GetId()."\")'>Historial</button>
										";
							if($users->GetHabilitado()==1)
								{
									$datos.="<button class='btn btn-warning btn-xs' onclick='SuspenderUsuario(\"".$users->GetId()."\")'>Suspender</button>";	
								}else
								{
									$datos.="<button class='btn btn-success btn-xs' onclick='HabilitarUsuario(\"".$users->GetId()."\")'>Habilitar</button>";	
								}
							$datos.="</td>";
						}
						else
							{
								$datos.="</tr>";
							}
		}
		echo $inicio.$datos.$fin;
	}
	# FORMATEO DE NOMBRE DE USUARIO ==================================================================================================================
	public static function FormatoString($string)
	{
		$validado = strtolower($string);
		if (strpos($validado,' ')=== FALSE)
		{
			$validado = ucfirst($validado);
		}
		else
		{
			$nombreCompuesto = explode(" ",$validado);
			$nombreCompuesto[0]=ucfirst($nombreCompuesto[0]);
			$nombreCompuesto[1]=ucfirst($nombreCompuesto[1]);
			$validado = $nombreCompuesto[0]." ".$nombreCompuesto[1];
		}
		return $validado;
	}
	# ULTIMOS 5 LOGINS ================================================================================================================================
	public static function UltimosLogueos($id)
	{
		$fin = "		</table>
					</div>
				</div>";
		$datos="";
		$consulta="SELECT u.nombre, r.login, r.logout FROM registro_usuarios AS r, usuarios AS u WHERE id_usuario = :id AND id_usuario = u.id ORDER BY r.id DESC LIMIT 5";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare($consulta);
		$db->bindValue(':id',$id);
		$db->execute();
		while($linea = $db->fetch(PDO::FETCH_ASSOC))
		{
			$datos.="	<tr>
							<td>".$linea["login"]."</td>
							<td>".$linea["logout"]."</td>
						</tr>";
			$usuario = $linea["nombre"];
		}
		$titulos = "<div class='col-xs-6'>
						<h2 class='sub-header miLabel'>Ultimos logins de ".$usuario."</h2>
							<div class='table-responsive'>
								<table class='table table-striped'>
									<thead>
										<tr class='success'>
											<th>Nombre</th>
											<th>Apellido</th>
										</tr>
									</thead>";
		return $titulos.$datos.$fin;
	}
	# SUMA DE OPERACIONES POR USUARIO ==================================================================================================================
	public static function DatosPorUsuario($id)
	{
		$consulta= "SELECT U.nombre, COUNT(*) as operaciones,SUM(monto) AS facturado FROM usuarios as U, registros WHERE id_usuario = :id AND id_usuario = u.id";
		$fin = "		</table>
					</div>
				  <button class='boton1' onclick='Exportar()'>Exportar Excel</button>
				</div>";
		$datos="";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare($consulta);
		$db->bindValue(':id',$id);
		$db->execute();
		while($linea = $db->fetch(PDO::FETCH_ASSOC))
		{
			$datos.="	<tr>
							<td>".$linea["operaciones"]."</td>
							<td>".$linea["facturado"]."</td>
						</tr>";
			$usuario = $linea["nombre"];
		}
		$titulos = "<div class='col-xs-6'>
						<h2 class='sub-header miLabel'>Registros del usuario ".$usuario."</h2>
							<div class='table-responsive'>
								<table class='table table-striped'>
									<thead>
										<tr class='success'>
											<th>Cantidad de operaciones</th>
											<th>Monto facturado</th>
										</tr>
									</thead>";
		return $titulos.$datos.$fin;
	}
	public static function MostrarRegistrosPorUnaFecha($anio,$mes,$dia,$anio2,$mes2,$dia2)
	{
		if($anio2 === "")
		{
			$anio2 = $anio;
			$mes2 = $mes;
			$dia2 = $dia;
		}
		$mes = Usuario::FormatoDate($mes);
		$dia = Usuario::FormatoDate($dia);
		$mes2 = Usuario::FormatoDate($mes2);
		$dia2 = Usuario::FormatoDate($dia2);

		$fecha1 = $anio."-".$mes."-".$dia;
		$fecha2 = $anio2."-".$mes2."-".$dia2;

		$consulta= "SELECT  monto,u.nombre,FROM_UNIXTIME(hora_inicio) AS inicio,
					FROM_UNIXTIME(hora_fin) AS fin FROM `registros`, usuarios AS u 
					WHERE FROM_UNIXTIME(hora_fin) BETWEEN :fecha1 AND ADDDATE(:fecha2,1)
					AND id_usuario = u.id";
		$inicio = "<table class='table table-hover'>
						<thead>
							<tr class='success'>
								<th>Usuario</th>
								<th>Inicio</th>
								<th>Fin</th>
								<th>Monto</th>
						</thead>";
		$datos = "";
		$fin ="</table>";

		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare($consulta);
		$db->bindValue(':fecha1',$fecha1);
		$db->bindValue(':fecha2',$fecha2);
		$db->execute();
		while($linea = $db->fetch(PDO::FETCH_ASSOC))
		{
			$datos.= "	<tr>
							<td>".$linea["nombre"]."</td>
							<td>".$linea["inicio"]."</td>
							<td>".$linea["fin"]."</td>
							<td>".$linea["monto"]."</td>
						</tr>";
		}
		if($datos === "")
		{
			$respuesta = "error";
		}
		else
		{
			$respuesta = $inicio.$datos.$fin;
		}
		return $respuesta;
	}
	public static function FormatoDate($fecha)
	{
		if (strlen($fecha) < 2)
		{
			$fecha="0".$fecha;
		}
		return $fecha;
	}
}
?>