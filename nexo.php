<?php
session_start();
include_once 'clases/lugares.php';
include_once 'clases/vehiculo.php';
include_once 'clases/usuarios.php';
include_once 'clases/registros.php';

if (isset($_POST["queHago"]))
{
	if(Lugares::PrimerUso())
	{
		echo "ok";
	}else
	{
		echo "no ok";
	}
}
if(isset($_POST["pat"]) && isset($_POST["marca"]) && isset($_POST["color"]) && isset($_POST["lugar"]))
{
	$miauto = new Vehiculo($_POST["pat"],$_POST["lugar"],$_POST["marca"],$_POST["color"],time());
	if (Vehiculo::IngresarAuto($miauto) && Lugares::OcuparLugar($miauto->GetId()))
	{
		echo "ok";
	}else
	{
		echo "no ok";
	}
}
if(isset($_POST["idParaEliminar"]))
{
	if (Usuario::BajaUsuario($_POST["idParaEliminar"]))
	{
		Usuario::TablaUsuarios();
	}else
	{
		echo "error";
	}
}
if(isset($_POST["cerrarSesion"]))
{
	if(Usuario::CerrarSesion($_POST["cerrarSesion"],$_POST["tiempo"]))
	{
		session_destroy();
		echo "ok";
	}else
	{
		echo "error";
	}
}
if(isset($_POST["piso"]))
{
	Lugares::LugaresLibres($_POST["piso"]);
}
if(isset($_POST["marca"]) && isset($_POST["color"]) && isset($_POST["patente"]) && isset($_POST["lugar"]))
{
	$miauto = new Vehiculo($_POST["patente"],$_POST["lugar"],$_POST["marca"],$_POST["color"],time());
	if(Vehiculo::IngresarAuto($miauto))
	{
		if(Lugares::OcuparLugar($miauto->GetId()))
		{
			Vehiculo::TablaEstacionados();	
		}else
		{
			echo "error";
		}
		
	}else
	{
		echo "error";
	}
}
//CARGAR MAPA DE LUGARES***********************************************************************************************************************
if(isset($_POST["mapaLugares"]))
{
	Lugares::GrillaLugares($_POST["mapaLugares"]);
}
//RETIRAR VEHICULO*****************************************************************************************************************************
if(isset($_POST["pat"]) && isset($_POST["iduser"]) && isset($_POST["idlugar"]) && isset($_POST["hora"]) && isset($_POST["monto"]))
{
	$rta="inicio";
	$miRegistro = new Registros($_POST["idlugar"],$_POST["iduser"],$_POST["pat"],$_POST["hora"],time(),$_POST["monto"]);

	if(Registros::IngresarRegistro($miRegistro))
	{
		if(Lugares::LiberarLugar($_POST["idlugar"]))
		{
			if(Vehiculo::RetirarAuto($_POST["pat"]))
			{
				$rta=Vehiculo::TablaEstacionados();
			}
			else
			{
				$rta = "Error al retirar auto";
			}
		}
		else
		{
			$rta = "Error al liberar lugar";
		}
	}
	else
	{
		$rta = "Error al ingresar registro";
	}
	echo $rta;
}
if(isset($_POST["mostrar"]))
{
	switch ($_POST["mostrar"]) 
	{
		case 'estadisticas':
			Registros::TraerTablaRegistros();
			break;
		case 'vehiculos':
			Vehiculo::TablaEstacionados();
			break;
		case 'usuarios':
			Usuario::TablaUsuarios();
			break;
		default:
			# code...
			break;
	}
	
}
if(isset($_POST["retiroPorPatente"]))
{
	echo Vehiculo::TraerAutoPorPatente($_POST["retiroPorPatente"]);
}
?>