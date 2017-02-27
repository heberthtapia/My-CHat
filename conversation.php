<?php
	$bd = "chateje";
	$server = "localhost";
	$user = "root";
	$password = "mysql";

	$conexion = @mysqli_connect($server, $user, $password,$bd);
	if( !conexion ) die("Error de coexion ".mysqli_connect_error());

	$sql = "SELECT usuario, mensaje FROM conversation order by idConversation ASC";
	$result = mysqli_query($conexion, $sql);

	while($data = mysqli_fetch_assoc($result)){
		echo "<p><b>".$data["usuario"]."</b> dice: ".$data["mensaje"]."</p>";
	}
?>