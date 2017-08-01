function Login()
{
	var usuario = $("#nombre").val();
	var password = $("#password").val();
	var datosLogin={"user":usuario, "pass":password};

	$.post("usuario/login",datosLogin,function(respuesta)	{
		
		if(respuesta)
		{

			window.location.href="operaciones.php";
		}else{
			console.log(respuesta);
			alert("Error de usuario o contraseña");
		}

	});
}
function LlenarBase()
{
	var a=$.ajax({
		type: 'post',
		url: 'nexo.php',
		data:
		{
			queHago: "PrimerUso"
		}
	});
	a.done(function(respuesta)
	{
		alert(respuesta);
	});
}
//***********************************************USUARIOS*******************************************************************************
//***********************************************USUARIOS*******************************************************************************
//***********************************************USUARIOS*******************************************************************************
function DisplayEntre()
{
	if($("#chk1").is(':checked'))
		{
			$("#lbl1").show();
			$("#lbl2").show();
			$("#lbl3").show();
			$("#dia2").show();
			$("#mes2").show();
			$("#anio2").show();		
		}else
		{
			$("#lbl1").hide();
			$("#lbl2").hide();
			$("#lbl3").hide();
			$("#dia2").hide();
			$("#mes2").hide();
			$("#anio2").hide();	
		}
}
function ModalFechaUsuario()
{
	$("#dia1").val("");
	$("#mes1").val("");
	$("#anio1").val("");
	$("#dia2").val("");
	$("#mes2").val("");
	$("#anio2").val("");
	$("#modalPorFecha").modal("show");
}
function ModalAltaUsuario()
{
	$("#alta_nombre").val("");
	$("#alta_apellido").val("");
	$("#alta_password").val("");
	$("#modalAltaUsuario").modal("show");
}
function NuevoUsuario()
{
	var a=$.ajax({
		type:'post',
		url: 'http://localhost:8080/TP2/nuevoUser',
		data:
			{
				nombre: $("#alta_nombre").val(),
				password: $("#alta_password").val(),
				apellido: $("#alta_apellido").val(),
				tipo: $("#alta_tipo").val(),
				turno: $("#alta_turno").val()
			}
	});
	a.done(function(respuesta)
	{
		$("#modalAltaUsuario").modal("hide");
		if(respuesta != false)
		{
			$("#tablausuarios").html(respuesta);
		}else
		{
			alert("Error al ingresar usuario");
		}
	});
}
function EliminarUsuario(id)
{
	var a=$.ajax({
		type: 'delete',
		url: 'http://localhost:8080/TP2/EliminarUsuario',
		data:
			{
				idParaEliminar: id 
			}
	});
	a.done(function(respuesta)
	{
		//alert(respuesta);
		if (respuesta != "error")
		{
			$("#tablausuarios").html(respuesta);
		}else
		{
			alert("Error al eliminar usuario");
		}
	});
}
function SuspenderUsuario(id)
{
	var a=$.ajax({
		type:'put',
		url:'http://localhost:8080/TP2/Deshabilitar',
		data:
			{
				idUser: id
			}
	});
	a.done(function(respuesta)
	{
		if(respuesta != false)
		{
			$("#tablausuarios").html(respuesta);	
		}else
		{
			alert("Error al suspender");
		}
		
	});
}
function HabilitarUsuario(id)
{
		var a=$.ajax({
		type:'put',
		url:'http://localhost:8080/TP2/Habilitar',
		data:
			{
				idUser: id
			}
	});
	a.done(function(respuesta)
	{
		if(respuesta != false)
		{
			$("#tablausuarios").html(respuesta);	
		}else
		{
			alert("Error al suspender");
		}
		
	});
}
function ModificarUser(id)
{
	var a=$.ajax({
		type:'put',
		url:'http://localhost:8080/TP2/ModificarUsuario',
		data:
			{
				idModi : id
			}
	});
	a.done(function(respuesta)
	{
		if(respuesta != false)
		{
			var MiRespuesta = respuesta.split("*");
			$("#id_mod").val(MiRespuesta[0]);
			$("#password_mod").val(MiRespuesta[1]);
			$("#nombre_mod").val(MiRespuesta[2]);
			$("#ape_mod").val(MiRespuesta[3]);
			$("#tipo_mod").val(MiRespuesta[4]);
			$("#turno_mod").val(MiRespuesta[5]);
			$("#habilitado_mod").val(MiRespuesta[6]);
			$("#modalModiUser").modal("show");
		}
		else
		{
			alert("Error al modificar usuario");
		}
	});
}
function ConfirmarModificacion()
{
	var a=$.ajax({
		type:'put',
		url:'http://localhost:8080/TP2/ConfirmaMod',
		data:
			{
				id: $("#id_mod").val(),
				nombre: $("#nombre_mod").val(),
				apellido: $("#ape_mod").val(),
				password: $("#password_mod").val(),
				tipo: $("#tipo_mod").val(),
				turno: $("#turno_mod").val(),
				habilitado: $("#habilitado_mod").val()
			}
	});
	a.done(function(respuesta)
	{
		$("#modalModiUser").modal("hide");
		if(respuesta != false)
		{
			$("#tablausuarios").html(respuesta);
		}
		else
		{
			alert("error al modificar usuario");
		}
	});
}
function HistorialUser(id_usuario)
{
	var a=$.ajax({
		type: 'post',
		url: 'http://localhost:8080/TP2/HistorialUsuario',
		data:
			{
				id: id_usuario
			}
	});
	a.done(function(respuesta){
		if(respuesta != "error")
		{
			$("#tablausuarios").html(respuesta);
		}
		else
		{
			alert("Error al cargar informacion del usuario");
		}
	});
}
function CerrarSesion(id_usuario,login)
{
	var a=$.ajax({
		type: 'post',
		url: 'http://localhost:8080/TP2/cerrarSesion',
		data: 
		{
			cerrarSesion: id_usuario,
			tiempo: login
		}
	});
	a.done(function(respuesta)
	{
		alert(respuesta);
		window.location.href ="index.php";
	});
}
function PorFecha(id)
{
	var a=$.ajax({
		type:'post',
		url: 'http://localhost:8080/TP2/HistoricoPorFecha',
		data:
			{
				idUser: id
			}
	});
	a.done(function(respuesta){
		var datos = respuesta.split("*");
		$("#nombre_fecha").html("Datos por fecha de: "+datos[2]);
		$("#modalPorFecha").modal("show");
	});
}
function Exportar()
{
	$.get('/usuario/exportar',function(respuesta){
		if(respuesta)
		{

		}else
		{
			console.log(respuesta);
		}
	});
}
//***********************************************AUTOS*******************************************************************************
//***********************************************AUTOS*******************************************************************************
//***********************************************AUTOS*******************************************************************************
function ModalHistoricoPatente()
{
	$("#patente_busco").val("");
	$("#historicoPatente").modal("show");
}
function HistoricoPatente()
{
	var a=$.ajax({
		type: 'post',
		url: 'http://localhost:8080/TP2/historicoPatente',
		data:
			{
				patente: $("#patente_busco").val()
			}
	});
	a.done(function(respuesta){
		//alert(respuesta);
		if(respuesta == "error")
		{
			alert("No se encontro registro de la pantete");
		}else
		{
			$("#tablaEstacionados").html(respuesta);
		}
		$("#historicoPatente").modal("hide");
	});
}
function IngresarAuto()
{
	$("#patente").val("");
	$("#marca").val("");
	$("#color").val("");
	$("#myModal").modal("show");
}
function LugaresLibres()
{
	var a=$.ajax({
		type: 'post',
		url: 'http://localhost:8080/TP2/traerLugaresLibres',
		data:
			{
				piso: $("#piso").val()
			}
	});
	a.done(function(respuesta)
	{	
		//alert(respuesta);
		$("#lugaresLibres").html(respuesta);
	});
}
function AutoParaIngresar()
{
	var a=$.ajax({
		type: 'post',
		url: 'http://localhost:8080/TP2/ingresarAuto',
		data:
			{
				patente: $("#patente").val(),
				marca: $("#marca").val(),
				color: $("#color").val(),
				lugar: $("#lugaresLibres").val()
			}
	});
	a.done(function(respuesta)
	{
		//alert(respuesta)
		if(respuesta == "errorpat")
		{
			alert("Patente invalida");
		}
		if (respuesta != false && respuesta != "errorpat")
		{
			alert("Auto ingresado correctamente");
			$("#tablaEstacionados").html(respuesta);
		}
		if(respuesta == false)
		{
			alert("Error al ingresar");
		}
		$("#myModal").modal("hide");
	});
}
function RetirarAuto()
{
	$("#patente_a_buscar").val("");
	$("#modalRetiroPatente").modal("show");
}
function RetirarVehiculo(idUsuario,patente,hora,monto,lugar)
{
	var entrada = new Date(parseInt(hora));
	var salida = new Date();
	$("#patente_retiro").val(patente);
	$("#hora_entrada").val(entrada);
	$("#monto_salida").val(monto);
	$("#hora_salida").val(salida);
	$("#idUser").val(idUsuario);
	$("#idLugar").val(lugar);
	$("#hora").val(hora);
	$("#modalRetiro").modal("show");
}
function ConfirmarRetiro()
{
	var a=$.ajax({
		type: 'delete',
		url: 'http://localhost:8080/TP2/RetirarAuto',
		data:
			{
				pat: $("#patente_retiro").val(),
				iduser: $("#idUser").val(),
				idlugar:$("#idLugar").val(),
				hora: $("#hora").val(),
				monto:$("#monto_salida").val()
			}
	});
	a.done(function(respuesta)
	{
		//alert(respuesta);
		$("#modalRetiro").modal("hide");
		$("#tablaEstacionados").html(respuesta);
		alert("Retiro Exitoso");
	});
}
function RetirarPorPatente()
{
	var a=$.ajax({
		type: 'post',
		url: 'http://localhost:8080/TP2/retiroPorPatente',
		data:
			{
				retiroPorPatente: $("#patente_a_buscar").val()
			}
	});
	a.done(function(respuesta)
	{
		if(respuesta != "error")
		{
			$("#modalRetiroPatente").modal("hide");
			var miRespuesta = respuesta.split('*');
			var entrada = new Date(parseInt(miRespuesta[3]));
			var salida = new Date();
			$("#patente_retiro").val(miRespuesta[1]);
			$("#hora_entrada").val(entrada);
			$("#monto_salida").val(miRespuesta[4]);
			$("#hora_salida").val(salida);
			$("#idUser").val(miRespuesta[0]);
			$("#idLugar").val(miRespuesta[2]);
			$("#hora").val(miRespuesta[3]);
			$("#modalRetiro").modal("show");			
		}else
		{
			alert("Patente no encontrada");
			$("#modalRetiroPatente").modal("hide");
		}

	});
}
//************************************************TABLAS********************************************************************************
//************************************************TABLAS********************************************************************************
//************************************************TABLAS********************************************************************************
function TablaRegistros()
{
	var a=$.ajax({
		type: 'get',
		url: 'http://localhost:8080/TP2/traerTablaRegistros',
	});
	a.done(function(respuesta){
		
		$("#tablausuarios").html(respuesta);
		$("#titulo").html("Registros");
	});
}
function TopUsuarios()
{
	var a=$.ajax({
		type:'get',
		url: 'http://localhost:8080/TP2/TopUsuarios'
	});
	a.done(function(respuesta){
		$("#tablausuarios").html(respuesta);
		$("#titulo").html("TOP'S USUARIOS");
	});
}
function TablaFiltros()
{
	var a=$.ajax({
		type: 'get',
		url: 'http://localhost:8080/TP2/traerFiltros',
	});
	a.done(function(respuesta){
		
		$("#tablaEstacionados").html(respuesta);
		$("#titulo").html("Filtros");
	});
}
function TablaFiltrada()
{
	var a=$.ajax({
		type: 'post',
		url: 'http://localhost:8080/TP2/datosFiltrados',
		data:
			{
				filtro: $("#selectFiltro").val()
			}
	});
	a.done(function(respuesta){
			//alert(respuesta);
			$("#resultadoFiltro").html(respuesta);
	});
}
function TablaEstacionados()
{
	var a=$.ajax({
		type:'get',
		url: 'http://localhost:8080/TP2/traertablaestacionados'
	});
	a.done(function(respuesta)
	{	
		//console.log(respuesta);
		$("#tablaEstacionados").html(respuesta);
		$("#titulo").html("Autos Estacionados");
	});
}
function TablaUsuarios()
{
	var a=$.ajax({
		type:'get',
		url: 'http://localhost:8080/TP2/traerTablaUsuarios'
	});
	a.done(function(respuesta)
	{
		$("#tablausuarios").html(respuesta);
		$("#titulo").html("Usuarios");
	});
}
function GrillaLugares(piso)
{
	var a=$.ajax({
		type: 'post',
		url: 'http://localhost:8080/TP2/grillaLugares',
		data:
			{
				mapaLugares: piso
			}
	});
	a.done(function(respuesta)
	{
		$("#lugaresLibres"+piso).html(respuesta);
	});
}
function datos()
{
	var a=$.ajax({
		type:'get',
		url: 'http://localhost:8080/TP2/test'
	});
	a.done(function(respuesta)
	{
		$("#derecha").html(respuesta);
	});
}
function ProcesarFecha()
{
	var a=$.ajax({
		type:'post',
		url: 'http://localhost:8080/TP2/traerOpPorFecha',
		data:
			{
				anio: $("#anio1").val(),
				mes: $("#mes1").val(),
				dia: $("#dia1").val(),
				anio2: $("#anio2").val(),
				mes2: $("#mes2").val(),
				dia2: $("#dia2").val()
			}
	});
	a.done(function(respuesta){
		if(respuesta != "error")
		{
			$("#tablausuarios").html(respuesta);
		}
		else
		{
			alert("No hay registros a la fecha");
		}
		$("#modalPorFecha").modal("hide");
	});
}