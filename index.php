<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="bower_components/bootstrapvalidator/dist/css/bootstrapValidator.css"> 
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js" ></script>
  <script src="bower_components/bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>
  <!-- <script src="validator.js"></script> -->
  <script src="funciones.js"></script>
    <title style="color: white">Estacionamiento "El aleman loco"</title>
</head>
<body background="fotos/germany.jpg" style="background-size: 100% 110% ">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <form id="login" method="POST" class="form-horizontal" role="form">
                    <fieldset>
                    <legend style="color: white">El aleman loco</legend>
                    <div class="form-group">
                        <center><img src="fotos/alemania.png" style="width:127px;height:150px;"></center>
                    </div>
                    <div class="form-group">
                        <label for="usuario" class="control-label" style="color: white">Usuario</label>
                        <input type="Mail" class="form-control col-sm-9" placeholder="Usuario" name="txtusuario" id="user"><br>
                    </div>
                    <div class="form-group row">
                        <label for="usuario" class="control-label" style="color: white">Password</label>
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password"><br>
                    </div>                  
                    <div class="form-group row">
                    <input type="button" class="btn btn-primary col-sm-12" value = "Sig In" onclick="Login()" id="enviar">
                    </div>
                    
                    </fieldset>
                </form>
            </div>
            
                    
        </div>
    </div>
</body>
</html>