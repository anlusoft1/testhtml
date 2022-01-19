<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
   <!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<!--Custom styles
	<link rel="stylesheet" type="text/css" href="styles.css">-->
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php
// 1. Conexion con el servidor y la base de datos
//$conexion = new mysqli('localhost', 'root', '', 'bd_autenticacion');

// 2. isset() del boton login
if(isset($_POST['login'])){

$conexion = new mysqli('localhost', '335668', 'cenizoR1', '335668');
if ($conexion->connect_errno) {
    echo "ERROR al conectar con la DB.";
    exit;
}
	
	if ($_POST['g-recaptcha-response'] == '') {
		echo "Captcha invalido vacio";
	} else {
		$obj = new stdClass();
		$obj->secret = "6Leka98dAAAAALhmDxwHaz2OT9ncTABKytyKj0Q2";
		$obj->response = $_POST['g-recaptcha-response'];
		$obj->remoteip = $_SERVER['REMOTE_ADDR'];
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		
		$options = array(
		'http' => array(
		'header' => "Content-type: application/x-www-form-urlencoded\r\n",
		'method' => 'POST',
		'content' => http_build_query($obj)
		)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		$validar = json_decode($result);
		echo json_encode($validar);
		
		$fcha = date("Y-m-d");
        $sql = "INSERT INTO tarea(titulo,contenido,fecharegistro,fechavencimiento,prioridad,idusuario,estado) ".
		"values ('".$_POST['g-recaptcha-response']."' ,'".$validar."','".$fcha."','".$fcha."','1.Alto',1,'Pendiente');";
		if(!$consulta = $conexion->query($sql)){
            echo "ERROR: no se pudo ejecutar insert!";
        }else{
            echo "<script>alert('Se inserto la tarea !!');window.location.href = 'ListarTareas.php';</script>";
        }
		
		
		/* FIN DE CAPTCHA */

		if ($validar->success) {
			/*
			$u = $_POST['usuario'];
			$c = $_POST['clave']; 

			if($u == "" || $_POST['clave'] == ""){ 
				echo "<script>alert('Error: usuario y/o clave vacios!!');</script>";
			}else{
				$sql = "SELECT * FROM usuarios WHERE usunombre = '$u' AND usuclave = '$c'";
				if(!$consulta = $conexion->query($sql)){
					echo "ERROR: no se pudo ejecutar la consulta!";
				}else{
					$filas = mysqli_num_rows($consulta);
					if($filas == 0){
						echo "<script>alert('Error: usuario y/o clave incorrectos!!');</script>";
					}else{
						echo "<script>alert('Usuario correcto !!');</script>";
					}

				}
			}*/
			echo "Captcha valido";
		} else {
		echo "Captcha invalido";
		}
	}
}

?>
	
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3>Login</h3>
				<div class="d-flex justify-content-end social_icon">
					<span><i class="fab fa-facebook-square"></i></span>
					<span><i class="fab fa-google-plus-square"></i></span>
					<span><i class="fab fa-twitter-square"></i></span>
				</div>
			</div>
			<div class="card-body">
				<form action="login.php" method="post" id="login_form">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" name="usuario" class="form-control" placeholder="username"/>
						
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" name="clave" class="form-control" placeholder="password"/>
					</div>
					<div class="row align-items-center remember">
						<input type="checkbox">Recuerdame
					</div>
					<div class="form-group">
						<input type="submit" name="login" value="Login" class="btn float-right login_btn"/>
												<input type="button" name="informa" value="Valida" class="btn float-right login_btn" onclick="valida();" />
												<input type="button" name="selecion" value="Posicion" class="btn float-right login_btn" onclick="posicion();" />

					</div>
					<div class="html_captcha">
					
					<div class="g-recaptcha" data-sitekey="6Leka98dAAAAAPfKMUH2Mmx3CSL1_Dmb7Vz2fH21"></div>
						</div>
				</form>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-center links">
					No tienes una cuenta?<a href="#">Registrarse</a>
				</div>
				<div class="d-flex justify-content-center">
					<a href="#">Recordar Contraseña?</a>
				</div>
			</div>
		</div>
	</div>
</div>

 <script>
 function posicion(){
 
 document.getElementById("g-recaptcha-response").innerHTML="otrovez";
 document.getElementById("rc-anchor-container").click();
 
 
};

function valida(){
  var response = grecaptcha.getResponse();
  if(response.length == 0) 
  { 
    alert("Erres humano???"); 
  }else{
	  alert(response);
	  alert("Si eres humano"); 
  
	}  
  
};
 </script>

</body>
</html>