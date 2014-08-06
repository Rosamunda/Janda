<?php	
/*
* Este archivo contene la conexión a la base de datos, alojada en la variable
* $conexion
* Para conectarse solamente hay que incluír este archivo y usar $conexion.
*/
	@$conectar=mysqli_connect('localhost','jandaAdmin','123456','janda')
	OR die('Upa! Algo salió mal y no se pudo conectar a la base de datos para loguearte. Error '.mysqli_connect_errno());
	/* aunque la base esté seteada en UTF8, no andaba, por lo que hay que 
	* setearlo especialmente usando mysqli_set_charset().
	* @ See http://php.net/manual/en/mysqli.set-charset.php mysqli_set_charset()
	*/
	mysqli_set_charset($conectar, 'utf8');
