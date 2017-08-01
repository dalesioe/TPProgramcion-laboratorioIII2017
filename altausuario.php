<?php
session_start();
?>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600' rel='stylesheet' type='text/css'>
<link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="estiloalta.css">
<div class="testbox">
  <h1>Alta Usuario</h1>

  <form action="/">
  <hr>
  <label id="icon" for="name"><i class="icon-envelope "></i></label>
  <input type="text" name="name" id="name" placeholder="Mail" required/>
  <label id="icon" for="name"><i class="icon-user"></i></label>
  <input type="text" name="name" id="name" placeholder="Nombre" required/>
  <label id="icon" for="name"><i class="icon-user"></i></label>
  <input type="text" name="name" id="name" placeholder="Apellido" required/>
  <label id="icon" for="name"><i class="icon-shield"></i></label>
  <input type="password" name="name" id="name" placeholder="Password" required/>
  <center>Turnos</center>
 <div>
 <center>
 	<select id="turno">Turno
 		<option>Manana</option>
 		<option>Tarde</option>
 		<option>Noche</option>
 	</select>
 </center>
 </div>
  <?php
  if (isset($_SESSION["tipo"]))
  	{
  		if($_SESSION["tipo"] == "admin")
  			{
			  	echo '<center>Tipo de Usuario
			  			<div id="tipo">
						 	<select>
						 		<option>user</option>
						 		<option>admin</option>
						 	</select>
					  	</div>
					  </center>';
			}
  	}
   ?> 
   <right><a href="#" class="button">Registrar</a></right>
   <left><a href="index.php" class="button">Volver</a></left>
  </form>
</div>