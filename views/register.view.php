<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
<title>Registrarse</title>
<link rel="stylesheet" href="/css/bootstrap.min.css">
<script src="js/jquery-1.12.4-jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>

</head>
<body class="text-center" style="background-color:#D7E1D6">

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
function redirectToDashboard($role) {
  switch ($role) {
      case "admin":
          header("Location: /sitrad/panel/");
          exit();
      case "user":
          header("Location: /sitrad/reportes/");
          exit();
      default:
          die("Rol no reconocido");
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
/* if (isset($_REQUEST['btn_register'])) { */
	$username	= $_POST['txt_username'];
	$email		= $_POST['txt_email'];
	$password	= $_POST['txt_password'];
	$role		= "user";

	if(empty($username)){
		$errorMsg[]="Ingrese nombre de usuario";	//Compruebe input nombre de usuario no vacío
	}
	else if(empty($email)){
		$errorMsg[]="Ingrese email";	//Revisar email input no vacio
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errorMsg[]="Ingrese email valido";	//Verificar formato de email
	}
	else if(empty($password)){
		$errorMsg[]="Ingrese password";	//Revisar password vacio o nulo
	}
	else if(strlen($password) < 6){
		$errorMsg[] = "Password minimo 6 caracteres";	//Revisar password 6 caracteres
	}
	else if(empty($role)){
		$errorMsg[]="Seleccione rol";	//Revisar etiqueta select vacio
	}
	else
	{	
		try
		{	
			$select_stmt=$db->prepare("SELECT username, email FROM mainlogin 
										WHERE username=:uname OR email=:uemail"); // consulta sql
			$select_stmt->bindParam(":uname",$username);   
			$select_stmt->bindParam(":uemail",$email);      //parámetros de enlace
			$select_stmt->execute();
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);	
			if($row["username"]==$username){
				$errorMsg[]="Usuario ya existe";	//Verificar usuario existente
			}
			else if($row["email"]==$email){
				$errorMsg[]="Email ya existe";	//Verificar email existente
			}
			
			else if(!isset($errorMsg))
			{
				$insert_stmt=$db->prepare("INSERT INTO mainlogin(username,email,password,role) VALUES(:uname,:uemail,:upassword,:urole)"); //Consulta sql de insertar			
				$insert_stmt->bindParam(":uname",$username);	
				$insert_stmt->bindParam(":uemail",$email);	  		//parámetros de enlace 
				$insert_stmt->bindParam(":upassword",$password);
				$insert_stmt->bindParam(":urole",$role);
				
				if($insert_stmt->execute())
				{
					$registerMsg="Registro exitoso: Esperar página de inicio de sesión"; //Ejecuta consultas 
					header("refresh:2;index.php"); //Actualizar despues de 2 segundo a la portada
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}

?>
	
	<center> 
	<div class="form-floating justify-content-md-center">
			
		<div class="abs-center">
		
	     <div class="col-6">
		
		<?php
		if(isset($errorMsg))
		{
			foreach($errorMsg as $error)
			{
			?>
				<div class="alert alert-danger">
					<strong>INCORRECTO ! <?php echo $error; ?></strong>
				</div>
            <?php
			}
		}
		if(isset($registerMsg))
		{
		?>
			<div class="alert alert-success">
				<strong>EXITO ! <?php echo $registerMsg; ?></strong>
			</div>
        <?php
		}
		?> 
<div class="form-group">  
<center><h2 style="color: white">Registro de nuevo usuario</h2></center>
<form method="post" class="form-horizontal">
    
<div class="form-group">

<div class="col-sm-12">
<input type="text" name="txt_username" class="form-control" placeholder="Ingrese usuario" />
</div>
</div>

<div class="form-group">

<div class="col-sm-12">
<input type="text" name="txt_email" class="form-control" placeholder="Ingrese email" />
</div>
</div>
    
<div class="form-group">

<div class="col-sm-12">
<input type="password" name="txt_password" class="form-control" placeholder="Ingrese contraseña" />
</div>
</div>
    
<div class="form-group">
  
    <div class="col-sm-12">
    
    </div>
</div>

<div class="form-group">
<div class="col-sm-12">
-
<center><input type="submit" name="btn_register" class="btn btn-outline-light" value="¡Crear cuenta!"></center>
<!--<a href="index.php" class="btn btn-danger">Cancel</a>-->
</div>
</div>

<div class="form-group">
<div class="col-sm-12">
<h5 style="color: white">¿Tenés una cuenta?</h5> <a href="index.php"><p class="text-info">Inicio de sesión</p></a>		
</div>
</div>
    
</form>
</div><!--Cierra div login-->
		</div>
		
	</div>
			
	</div>
										
	</body>
	<footer class="text-center" >
	<img class="img-fluid" src="/img/2.png" style="width:290px !important; height:50px !important" alt="" >
	
	</footer>
</html>