<?php
session_start();
require_once "vendor/autoload.php";
require_once "clases/usuarios.php";
require_once "clases/registros.php";
require_once "clases/lugares.php";
require_once "clases/vehiculo.php";


$app = new \Slim\App;

//GETS****************************************************************************************************************************
$app->get('/test',function($request,$response)
	{
		$response->getbody()->write(Registros::LugaresQueMasFacturaron().Registros::LugaresMenosUsados());

		return $response;
	});
$app->get('/traertablaestacionados',function($request,$response)
	{
		$response->getbody()->write(Vehiculo::TablaEstacionados());

		return $response;
	});
$app->get('/traerTablaRegistros',function($request,$response)
	{
		$response->getbody()->write(Registros::TraerTablaRegistros());

		return $response;
	});
$app->get('/traerTablaUsuarios',function($request,$response)
	{
		$response->getBody()->write(Usuario::TablaUsuarios());

		return $response;
	});
$app->get('/traerFiltros',function($request,$response)
	{
		$response->getBody()->write(Registros::TablaFiltros());

		return $response;
	});
$app->get('/TopUsuarios',function($request,$response)
	{
		$response->getBody()->write(Registros::TopRegistrosPorUsuario());

		return $response;
	});
$app->get('/display',function($request,$response)
	{
		$response->getBody()->write(Usuario::DisplayEntre());

		return $response;
	});
//PUTS****************************************************************************************************************************
$app->put('/Deshabilitar',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$response->write(Usuario::SuspenderUsuario($datos["idUser"]));

		return $response;
	});

$app->put('/Habilitar',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$response->write(Usuario::HabilitarUsuario($datos["idUser"]));

		return $response;
	});
$app->put('/ModificarUsuario',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$response->write(Usuario::TraerUnUsuario($datos["idModi"]));

		return $response;
	});
$app->put('/ConfirmaMod',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$usuario = new Usuario(
			$datos["nombre"],$datos["password"],$datos["apellido"],$datos["tipo"],$datos["turno"],$datos["id"],$datos["habilitado"]);
		$response->write(Usuario::ModiUsuario($usuario));

		return $response;
	});
//POSTS****************************************************************************************************************************
$app->post('/usuario/login', function($request, $response){
	$datos=$request->getParsedBody();
	$nombre=$datos["user"];
	$pass=$datos["pass"];
	$response->write(Usuario::LoginUsuario($nombre,$pass));
    return $response;
});

$app->post('/traerLugaresLibres',function($request,$response)
	{
		$pisos = $request->getParsedBody();
		$response->write(Lugares::LugaresLibres($pisos["piso"]));

		return $response;
	});
$app->post('/ingresarAuto',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$unAuto = new Vehiculo($datos["patente"],$datos["lugar"],$datos["marca"],$datos["color"],time());
		$response->write(Vehiculo::IngresarAuto($unAuto));

		return $response;
	});
$app->post('/cerrarSesion',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$response->write(Usuario::CerrarSesion($datos["cerrarSesion"],$datos["tiempo"]));

		return $response;
	});
$app->post('/retiroPorPatente',function($request,$response)
	{
		$datos= $request->getParsedBody();
		$response->write(Vehiculo::TraerAutoPorPatente($datos["retiroPorPatente"]));

		return $response;
	});
$app->post('/grillaLugares',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$response->write(Lugares::GrillaLugares($datos["mapaLugares"]));

		return $response;
	});
$app->post('/datosFiltrados',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$filtro = $datos["filtro"];
		$response->write(Registros::RegistrosFiltrados($filtro));

		return $response;
	});
$app->post('/nuevoUser',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$usuario = new Usuario($datos["nombre"],$datos["password"],$datos["apellido"],$datos["tipo"],$datos["turno"]);
		$response->write(Usuario::AltaUsuario($usuario));

		return $response;
	});
$app->post('/HistorialUsuario',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$respuesta = Usuario::UltimosLogueos($datos["id"]);
		$respuesta.= Usuario::DatosPorUsuario($datos["id"]);
		$response->write($respuesta);

		return $response;
	});
$app->post('/historicoPatente',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$respuesta = Registros::HistoricoPatente($datos["patente"]);

		$response->write($respuesta);

		return $response;
	});
$app->post('/HistoricoPorFecha',function ($request,$response)
	{
		$datos = $request->getParsedBody();
		$respuesta = Usuario::TraerUnUsuario($datos["idUser"]);

		$response->write($respuesta);
	});
$app->post('/traerOpPorFecha',function ($request,$response)
	{
		$datos = $request->getParsedBody();

		$response->write(Usuario::MostrarRegistrosPorUnaFecha($datos["anio"],$datos["mes"],$datos["dia"],$datos["anio2"],$datos["mes2"],$datos["dia2"]));

		return $response;
	});
//DELETES***************************************************************************************************************************
$app->delete('/RetirarAuto',function($request,$response)
	{
		$datos = $request->getParsedBody();
		$unRegistro = new Registros($datos["idlugar"],$datos["iduser"],$datos["pat"],$datos["hora"],time(),$datos["monto"]);
		Registros::IngresarRegistro($unRegistro);
		Lugares::LiberarLugar($datos["idlugar"]);
		Vehiculo::RetirarAuto($datos["pat"]);
		//$respuesta = Vehiculo::TablaEstacionados();
		$response->write(Vehiculo::TablaEstacionados());
		
		return $response;
	});
$app->delete('/EliminarUsuario',function($request,$response)
	{
		$datos = $request->getParsedBody();
		if (Usuario::BajaUsuario($datos["idParaEliminar"]))
		{
			$respuesta = Usuario::TablaUsuarios();
		}
		else
		{
			$respuesta = "error";
		}

		$response->write($respuesta);
		return $response;
	});
$app->run();
?>