<?php
/**
* 
*/
class Registros
{
	#ATRIBUTOS--------------------------------------------------------------------------------------------------------------
	private $_idLugar;
	private $_idUsuario;
	private $_patente;
	private $_entrada;
	private $_monto;

	#CONSTRUCTOR-------------------------------------------------------------------------------------------------------------
	function __construct($lugar,$usuario,$patente,$entrada,$salida,$monto)
	{
		$this->_idLugar = $lugar;
		$this->_idUsuario = $usuario;
		$this->_patente = $patente;
		$this->_entrada = $entrada;
		$this->_salida= $salida;
		$this->_monto = $monto;
	}

	#GETTERS Y SETTERS-------------------------------------------------------------------------------------------------------
	public function GetLugar()
	{
		return $this->_idLugar;
	}
	public function GetUsuario()
	{
		return $this->_idUsuario;
	}
	public function GetPatente()
	{
		return $this->_patente;
	}
	public function GetMonto()
	{
		return $this->_monto;
	}
	public function GetEntrada()
	{
		return $this->_entrada;
	}
	public function GetSalida()
	{
		return $this->_salida;
	}
	#INGRESO REGISTRO----------------------------------------------------------------------------------------------------------------------------
	public static function IngresarRegistro($obj)
	{
		$resultado = FALSE;
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$db = $pdo->prepare("INSERT INTO registros (id_lugar,id_usuario,patente,hora_inicio,hora_fin,monto)
								VALUES(:idLugar,:idUsuario,:patente,:inicio,:fin,:monto)");
		$db->bindValue(':idLugar',$obj->GetLugar());
		$db->bindValue(':idUsuario',$obj->GetUsuario());
		$db->bindValue(':patente',$obj->GetPatente());
		$db->bindValue(':inicio',$obj->GetEntrada());
		$db->bindValue(':fin',$obj->GetSalida());
		$db->bindValue(':monto',$obj->GetMonto());
		if ($db->execute())
		{
			$resultado = TRUE;
		}
		return $resultado;
	}
	#ARRAY DE REGISTROS---------------------------------------------------------------------------------------------------------------------------
	public static function TraerRegistros()
	{
		$ListaDeRegistros = array();
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$contenido = $pdo->query("SELECT * FROM registros LIMIT 15");
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
			{
				$unRegistro = new Registros($linea["id_lugar"],$linea["id_usuario"],$linea["patente"],$linea["hora_inicio"],$linea["hora_fin"],$linea["monto"]);
				array_push($ListaDeRegistros, $unRegistro);
			}				
		return $ListaDeRegistros;
	}
	#ARRAY DE REGISTROS POR PATENTE---------------------------------------------------------------------------------------------------------------------------
	public static function TraerRegistrosPorPatente($pat)
	{
		$ListaDeRegistros = array();
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$contenido = $pdo->prepare("SELECT * FROM registros WHERE patente = :pat");
		$contenido->bindValue(':pat',$pat);
		$contenido->execute();
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
			{
				$unRegistro = new Registros($linea["id_lugar"],$linea["id_usuario"],$linea["patente"],$linea["hora_inicio"],$linea["hora_fin"],$linea["monto"]);
				array_push($ListaDeRegistros, $unRegistro);
			}				
		return $ListaDeRegistros;
	}

	#TABLA REGISTROS------------------------------------------------------------------------------------------------------------------------------
	public static function TraerTablaRegistros()
	{
		$inicio = "<center><h2>Ultimos 15 registros</h2></center>
					<table class='table table-hover'>
						<thead>
							<tr class='info'>
								<th>Lugar</th>
								<th>Usuario</th>
								<th>Patente</th>
								<th>Entrada</th>
								<th>Salida</th>
								<th>Monto</th>
							</tr>
						</thead>";
		$fin= "</table>";
		$datos= "";
		$registros = array();
		$registros = Registros::TraerRegistros();
		foreach ($registros as $item)
		{
			$entrada = date("d-m-y H:i:s",$item->GetEntrada());
			$salida = date("d-m-y H:i:s",$item->GetSalida());
			$datos.="<tr>
						<td>".$item->GetLugar()."</td>
						<td>".$item->GetUsuario()."</td>
						<td>".$item->GetPatente()."</td>
						<td>".$entrada."</td>
						<td>".$salida."</td>
						<td>".$item->GetMonto()."</td>
					</tr>";
		}
		echo $inicio.$datos.$fin;
	}
	# COMBO DE OPCIONES PARA OBTENER DATOS DE USUARIO O COCHERA ==================================================================================
	public static function TablaFiltros()
	{
		$datos= "<div>
					<select id='selectFiltro' onchange='TablaFiltrada()'>
						<option>Usuario</option>
						<option selected='selected'>Cocheras</option>
					</select>
				</div>
				<div id='resultadoFiltro'>

				</div>";

		return $datos;
	}
	# FUNCION QUE RESPONSE AL COMBO DE OPCIONES SELECCIONADAS PARA DIBUJAR LAS TABLAS ============================================================
	public static function RegistrosFiltrados($condicion)
	{
		switch ($condicion)
		{
			case 'Usuario':
				$titulo = "Registros de Usuarios";
				//$datos = Registros::LadoIzquierdo();
				$datos = Registros::LogueoDeUsuarios();
				$datos.= Registros::OperacionesMontosPorUsuario();
				break;
			case 'Operaciones':
				$titulo = "Registros de Operaciones";
				$datos = Registros::LadoIzquierdo();
				break;
			case 'Cocheras':
				$titulo = "Registros de Cocheras";
				$datos = Registros::LugaresMasUsados();
				$datos.= Registros::LugaresMenosUsados();
				$datos.= Registros::LugaresQueMasFacturaron();
				$datos.= Registros::LugaresQueNuncaSeUsaron();
				break;
			default:
				$datos = "NULL";
				break;
		}
		$inicio= "<center><h3>".$titulo."</h3></center>";		
		
		$fin ="</table>";	

		echo $inicio.$datos.$fin;
	}
	# DATOS DE LOGUEO DE USUARIOS =================================================================================================================
	public static function InformacionUsuarios()
	{
		$titulos = "<table class='table table-hover'>
					<th>
						<tr class='danger'>
							<td>Nombre</td>
							<td>Apellido</td>
							<td>Log In</td>
							<td>Log Out</td>
						</tr>
					</th>";
		$datos="";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$contenido = $pdo->query("SELECT usu.nombre, usu.apellido, regusu.login, regusu.logout FROM 
								  registro_usuarios AS regusu, usuarios AS usu 
								  WHERE usu.id = regusu.id_usuario");
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
			{
				$datos.="<tr>
							<td>".$linea["nombre"]."</td>
							<td>".$linea["apellido"]."</td>
							<td>".$linea["login"]."</td>
							<td>".$linea["logout"]."</td>
						</tr>";
			}
		return $titulos.$datos;		
	}
	# CANTIDAD DE OPERACIONES POR USUARIO ============================================================================================================
	public static function OperacionesPorUsuario()
	{
		$titulos = "<thead>
						<tr class='success'>
							<th>Nombre</th>
							<th>Apellido</th>
							<th>Operaciones</th>
							<th>Monto</th>
						</tr>
					</thead>";
		$datos="";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento", "root","");
		$contenido = $pdo->query("SELECT usu.nombre,usu.apellido,count(reg.id_usuario),sum(reg.monto) FROM usuarios AS usu, registros AS reg WHERE usu.id = reg.id_usuario");
	}
	# CANTIDAD DE LOGUINS POR USUARIO ==================================================================================================================
	public static function LogueoDeUsuarios()
	{
		$consulta = "SELECT u.nombre, count(*) AS logueos FROM usuarios AS u, registro_usuarios WHERE id_usuario = u.id GROUP BY u.nombre ORDER BY logueos DESC";
		$titulos ="	<div class='col-xs-6'>
						<h2 class='sub-header'>Login de usuarios</h2>
							<div class='table-responsive'>
								<table class='table table-striped'>
									<thead>
										<tr class='danger'>
											<th>Usuario</th>
											<th>Logueos</th>
										</tr>
									</thead>
											";
		$fin = "		</table>
					</div>
				</div>";
		$datos="";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$contenido = $pdo->query($consulta);
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
		{
			$datos.="	<tr>
							<td>".$linea["nombre"]."</td>
							<td>".$linea["logueos"]."</td>
						</tr>";
		}

		return $titulos.$datos.$fin;
	}
	# TABLA DE OPERACIONES Y MONTOS POR USUARIO ORDENADA POR MONTO FACTURADO ==============================================================================
	public static function OperacionesMontosPorUsuario()
	{
		$titulos = "<div class='col-xs-6'>
						<h2 class='sub-header'>Datos Usuarios</h2>
							<div class='table-responsive'>
								<table class='table table-striped'>
									<thead>
										<tr class='success'>
											<th>Nombre</th>
											<th>Apellido</th>
											<th>Cant. Operaciones</th>
											<th>Monto Facturado</th>
										</tr>
									</thead>";
		$fin = "		</table>
					</div>
				</div>";
		$datos="";

		$consulta= "SELECT U.nombre,U.apellido, COUNT(*) as operaciones,SUM(monto) AS facturado FROM usuarios as U, registros WHERE id_usuario = U.id GROUP BY U.nombre ORDER BY facturado DESC";

		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$contenido = $pdo->query($consulta);
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
		{
			$datos.="	<tr>
							<td>".$linea["nombre"]."</td>
							<td>".$linea["apellido"]."</td>
							<td>".$linea["operaciones"]."</td>
							<td>".$linea["facturado"]."</td>
						</tr>";
		}
		return $titulos.$datos.$fin;
	}
	# TABLA DE TOP 5 DE LUGARES MAS UTILIZADOS =============================================================================================================
	public static function LugaresMasUsados()
	{
		$datos="";
		$inicio = "	<div class='col-xs-6'>
						<h2 class='sub-header'>TOP 5 + Usados</h2>
							<div class='table-responsive'>
								<table class='table table-striped'>
									<thead>
										<tr class='warning'>
											<th>Lugar</th>
											<th>Usos</th>
											<th>Facturado</th>
										</tr>
									</thead>";
		$fin = "		</table>
					</div>
				</div>";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$consulta="SELECT id_lugar,COUNT(*) as cantidad,SUM(monto) AS facturado FROM registros GROUP BY id_lugar ORDER BY cantidad DESC LIMIT 5";
		$contenido = $pdo->query($consulta);
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
		{
			$datos.="	<tr>
							<td>".$linea["id_lugar"]."</td>
							<td>".$linea["cantidad"]."</td>
							<td>".$linea["facturado"]."</td>
						</tr>";
		}
		return $inicio.$datos.$fin;
	}
	# TABLA DE TOP 5 DE LUGARES QUE MAS FACTURARON =============================================================================================================
	public static function LugaresQueMasFacturaron()
	{
		$datos="";
		$inicio = "	<div class='col-xs-6'>
						<h2 class='sub-header'>TOP 5 Facturado</h2>
							<div class='table-responsive'>
								<table class='table table-striped'>
									<thead>
										<tr class='info'>
											<th>Lugar</th>
											<th>Usos</th>
											<th>Facturado</th>
										</tr>
									</thead>";
		$fin = "				</table>
							</div>
					</div>";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$consulta="SELECT id_lugar,COUNT(*) as cantidad,SUM(monto) AS facturado FROM registros GROUP BY id_lugar ORDER BY facturado DESC LIMIT 5";
		$contenido = $pdo->query($consulta);
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
		{
			$datos.="	<tr>
							<td>".$linea["id_lugar"]."</td>
							<td>".$linea["cantidad"]."</td>
							<td>".$linea["facturado"]."</td>
						</tr>";
		}
		return $inicio.$datos.$fin;
	}
	public static function LugaresQueNuncaSeUsaron()
	{
		$datos="";
		$inicio = "	<div class='col-xs-6'>
						<h2 class='sub-header'>TOP 5 No Usados</h2>
							<div class='table-responsive'>
								<table class='table table-striped'>
									<thead>
										<tr class='danger'>
											<th>Lugar</th>
											<th>Piso</th>
											<th>Discapacitado</th>
										</tr>
									</thead>";
		$fin = "				</table>
							</div>
					</div>";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$consulta="SELECT * FROM lugares WHERE id_lugar NOT IN (SELECT id_lugar FROM registros) AND ocupado =0 LIMIT 5";
		$contenido = $pdo->query($consulta);
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
		{
			if($linea["discapacitado"] == 1)
			{
				$disc = "Si";
			}
			else
			{
				$disc = "No";
			}
			$datos.="	<tr>
							<td>".$linea["id_lugar"]."</td>
							<td>".$linea["id_piso"]."</td>
							<td>".$disc."</td>
						</tr>";
		}
		return $inicio.$datos.$fin;
	}
	# TABLA DE TOP 5 DE LUGARES MENOS UTILIZADOS =============================================================================================================
	public static function LugaresMenosUsados()
	{
		$datos="";
		$inicio = "	<div class='col-xs-6'>
						<h2 class='sub-header'>TOP 5 - Usados</h2>
							<div class='table-responsive'>
								<table class='table table-striped'>
									<thead>
										<tr class='success'>
											<th>Lugar</th>
											<th>Usos</th>
											<th>Facturado</th>
										</tr>
									</thead>";
		$fin = "				</table>
							</div>
					<div id='push'></div>
				</div>";
		$pdo = new PDO("mysql:host = localhost; dbname=estacionamiento","root","");
		$consulta="SELECT id_lugar,COUNT(*) as cantidad,SUM(monto) AS facturado FROM registros GROUP BY id_lugar ORDER BY cantidad ASC LIMIT 5";
		$contenido = $pdo->query($consulta);
		while($linea = $contenido->fetch(PDO::FETCH_ASSOC))
		{
			$datos.="	<tr>
							<td>".$linea["id_lugar"]."</td>
							<td>".$linea["cantidad"]."</td>
							<td>".$linea["facturado"]."</td>
						</tr>";
		}
		return $inicio.$datos.$fin;
	}
	# HISTORICO POR PATENTE =============================================================================================================
	public static function HistoricoPatente($pat)
	{
		$registros = array();
		$registros = Registros::TraerRegistrosPorPatente($pat);
		if(isset($registros[0]))
		{
			$inicio = "<center><h2>Historico Por Patente</h2></center>
					<table class='table table-hover'>
						<thead>
							<tr class='info'>
								<th>Lugar</th>
								<th>Usuario</th>
								<th>Patente</th>
								<th>Entrada</th>
								<th>Salida</th>
								<th>Monto</th>
							</tr>
						</thead>";
			$fin= "</table>";
			$datos= "";		
			foreach ($registros as $item)
			{
				$entrada = date("d-m-y H:i",$item->GetEntrada());
				$salida = date("d-m-y H:i",$item->GetSalida());
				$datos.="<tr>
							<td>".$item->GetLugar()."</td>
							<td>".$item->GetUsuario()."</td>
							<td>".$item->GetPatente()."</td>
							<td>".$entrada."</td>
							<td>".$salida."</td>
							<td>".$item->GetMonto()."</td>
						</tr>";
			}
			$respuesta = $inicio.$datos.$fin;
		}else
		{
			$respuesta = "error";
		}
		return $respuesta;
		
	}
	public static function LadoIzquierdo()
	{
		$html = "<div class='col-xs-3'>
					<h2 class='sub-header'>Opciones</h2>
						<form>
							<label class='radio-inline'><input type='radio' name='optradio' onchange='datos()'>Cocheras</label>
							<label class='radio-inline'><input type='radio' name='optradio'>Option 2</label>
							<label class='radio-inline'><input type='radio' name='optradio'>Option 3</label>
							<label class='radio-inline'><input type='radio' name='optradio'>Option 4</label>
						</form>
				</div>";
		$derecho = "<div class='col-xs-9' id='derecha'>
					<h2 class='sub-header'>DERECHA</h2>
						<form>
							<label class='radio-inline'><input type='radio' name='optradio'>Option 1</label>
							<label class='radio-inline'><input type='radio' name='optradio'>Option 2</label>
							<label class='radio-inline'><input type='radio' name='optradio'>Option 3</label>
							<label class='radio-inline'><input type='radio' name='optradio'>Option 4</label>
						</form>
				</div>";

		return $html.$derecho;
	}

	
}
?>