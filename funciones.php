<?php

/* 
* Función que guarda en base los datos que cargamos en el formulario
*/
function guardarDatos() {

	// variables para los cuatro sets de campos.
	// Si... esto debería estar mejor.
	$fecha = $_POST['fecha'];
	$monto = $_POST['monto'];
	$rubro = $_POST['rubro'];
	$tipoGasto = $_POST['tipoGasto'];
	$extraordinario = $_POST['extraordinario'];

	$fecha2 = $_POST['fecha2'];
	$monto2 = $_POST['monto2'];
	$rubro2 = $_POST['rubro2'];
	$tipoGasto2 = $_POST['tipoGasto2'];
	$extraordinario2 = $_POST['extraordinario2'];

	$fecha3 = $_POST['fecha3'];
	$monto3 = $_POST['monto3'];
	$rubro3 = $_POST['rubro3'];
	$tipoGasto3 = $_POST['tipoGasto3'];	
	$extraordinario3 = $_POST['extraordinario3'];

	$fecha4 = $_POST['fecha4'];
	$monto4 = $_POST['monto4'];
	$rubro4 = $_POST['rubro4'];
	$tipoGasto4 = $_POST['tipoGasto4'];	
	$extraordinario4 = $_POST['extraordinario4'];

	$inicial = $_POST['montoInicial'];
	$diasTotales = $_POST['diasTotales'];	

	include 'conectar.php';	//nos conectamos a la base de datos 
	
	if ($monto) {
	mysqli_query($conectar,"INSERT INTO gasto
							(fecha, monto, rubro, tipoGasto, extraordinario)
							VALUES 
							('$fecha', '$monto', '$rubro', '$tipoGasto', '$extraordinario')
				"); }
	if ($monto2) {
	mysqli_query($conectar,"INSERT INTO gasto
							(fecha, monto, rubro, tipoGasto, extraordinario)
							VALUES 
							('$fecha2', '$monto2', '$rubro2', '$tipoGasto2', '$extraordinario2')
				"); }
	if ($monto3) {
	mysqli_query($conectar,"INSERT INTO gasto
							(fecha, monto, rubro, tipoGasto, extraordinario)
							VALUES 
							('$fecha3', '$monto3', '$rubro3', '$tipoGasto3', '$extraordinario3')
				"); }
	if ($monto4) {
	mysqli_query($conectar,"INSERT INTO gasto
							(fecha, monto, rubro, tipoGasto, extraordinario)
							VALUES 
							('$fecha4', '$monto4', '$rubro4', '$tipoGasto4', '$extraordinario4')
				"); }
	//modificamos el valor de montoinicial acorde al campo del form
	mysqli_query($conectar,"UPDATE settings
							SET config='montoInicial', valor='$inicial'
							WHERE config = 'montoInicial';
				");
	//modificamos el valor de diasTotales acorde al campo del form
	mysqli_query($conectar,"UPDATE settings
							SET config='diasTotales', valor='$diasTotales'
							WHERE config = 'diasTotales';
				");

	mysqli_close($conectar);
	header('Location: index.php');

}

/* 
* Función que muestra todos los datos de la base, ordenados por fecha
*/
function mostrarDatos(){

include 'conectar.php';	//nos conectamos a la base de datos 
$consulta="  SELECT id, fecha, monto, rubro, tipoGasto, extraordinario
			 FROM gasto 
			 ORDER BY fecha DESC
			 ";

$resultado=mysqli_query($conectar,$consulta);	

	echo '<br><table border="1"><tr><td>Fecha</td><td>Monto</td><td>Rubro</td><td>Tipo</td><td></td></tr>';
	foreach ($resultado as $key => $value) {
			echo '<tr>';
			echo '<td>'.$value['fecha'].'</td>';
			echo '<td>'.$value['monto'].'</td>';
			echo '<td>'.$value['rubro'].'</td>';
			echo '<td>'.$value['tipoGasto'].'</td>';
			echo '<td><span class="resaltar">'.$value['extraordinario'].'</span></td>';
			echo '</tr>';
		}
	echo '</table><br>';

mysqli_close($conectar);		 

}

// devuelve la cifra total gastada hasta el momento
function calcularGastoTotal(){
	include 'conectar.php';	//nos conectamos a la base de datos 
	$consulta="  SELECT monto 
				 FROM gasto 
				 ";
	$resultado=mysqli_query($conectar,$consulta);
	global $totalGeneral;
	$totalGeneral = 0;
	foreach ($resultado as $value) {
		$item = (int)$value['monto'];
		$totalGeneral += $item;
	} return $totalGeneral;

	// NOTA: Necesitamos hacer global la data de la variable $totalGlobal, para 
	// poder usarla fuera de la función http://stackoverflow.com/questions/13530465/how-to-declare-a-global-variable-in-php

mysqli_close($conectar);
}

//generamos una consulta agrupada por día.
//
function gastoDiario(){
	include 'conectar.php';	//nos conectamos a la base de datos 
	$consulta="  SELECT fecha, sum(monto) as totalDiario
				 FROM gasto 
				 GROUP BY fecha;
				 ";
	$resultado=mysqli_query($conectar,$consulta);
	//nos sirve para obtener la cantidad de iteraciones del foreach loop: 
	//http://stackoverflow.com/questions/6220546/count-number-of-iterations-in-a-foreach-loop
	$cantDias = 0;
	foreach ($resultado as $key => $value) {
		global $cantDias;
		$cantDias ++;
		echo $value['fecha'].' - <b>'.$value['totalDiario'].'</b><br>';
	} 
	global $cantDias;
	echo '<br>Ya son '.$cantDias.' días.<br>';
}

/* obtenemos el promedio diario
http://stackoverflow.com/questions/2035798/math-average-with-php
http://stackoverflow.com/questions/25127361/php-global-variable-wont-work-as-expected-when-used-with-the-global-keyword-in#25127411
*/

function promedioDiario(){
	include 'conectar.php';	//nos conectamos a la base de datos 
	$consulta="  SELECT monto, extraordinario
				 FROM gasto 
				 WHERE extraordinario IS NOT NULL
				 ";
	$resultado=mysqli_query($conectar,$consulta);

	$totalGeneralOrdinario = 0;
	foreach ($resultado as $value) {
		$item = (int)$value['monto'];
		$totalGeneralOrdinario += $item;
		} 
		global $cantDias;
		global $totalGeneral;
		global $promedio;
	$promedio = $totalGeneralOrdinario / $cantDias;
	echo '<br>Promedio <b>diario</b> de gastos <b>ordinarios</b>: <br>$ '.round($promedio, 2).'<br><br>';
	echo 'Total ordinario: $<b>'.$totalGeneralOrdinario.'</b><br>';	
	$totalExtraordinario = $totalGeneral - $totalGeneralOrdinario;
	echo 'Total Extraordinario: $'.$totalExtraordinario.'<br>';

}


function gastoTarjeta(){
	include 'conectar.php';	//nos conectamos a la base de datos 
	$consulta="  SELECT fecha, monto
				 FROM gasto 
				 WHERE tipoGasto = 'tarjeta'
				 ";
	$resultado=mysqli_query($conectar,$consulta);
	$total = 0;
	foreach ($resultado as $value) {
		$item = (int)$value['monto'];
		$total += $item;
	} return $total;
	
mysqli_close($conectar);
}

function gastoEfectivo(){
	include 'conectar.php';	//nos conectamos a la base de datos 
	$consulta="  SELECT fecha, monto
				 FROM gasto 
				 WHERE tipoGasto = 'efectivo'
				 ";
	$resultado=mysqli_query($conectar,$consulta);
	$total = 0;
	foreach ($resultado as $value) {
		$item = (int)$value['monto'];
		$total += $item;
	} return $total;

mysqli_close($conectar);
}

function obtenerMontoInicial() {

	/* Consultamos a base para que nos devuelva los valores guardados.
	* la primera vez que los muestre será lo que esté por default 
	* ver database.sql
	*/

	include 'conectar.php';
	$data="  SELECT config, valor 
			 FROM settings
			 WHERE config = 'montoInicial'
		  ";
	$resultado=mysqli_query($conectar,$data);

	/*
	* Una vez generada la consulta, obtenemos la información del array asociativa
	* mediante un foreach.
	* Dentro de ese foreach, preguntamos si la $key es exactamente igual al 
	* número de orden.
	*/

	foreach ($resultado as $key => $value) {
			$montoInicial = $value['valor'];
			return $montoInicial; //obtenemos el valor del montoInicial que hay en la base
	}

	mysqli_close($conectar);
}

//obtiene los días totales de la estadía
function obtenerDiasTotales() {

	include 'conectar.php';
	$data="  SELECT config, valor 
			 FROM settings
			 WHERE config = 'diasTotales'
		  ";
	$resultado=mysqli_query($conectar,$data);

	/*
	* Una vez generada la consulta, obtenemos la información del array asociativa
	* mediante un foreach.
	* Dentro de ese foreach, preguntamos si la $key es exactamente igual al 
	* número de orden.
	*/

	foreach ($resultado as $key => $value) {
			$diasTotales = $value['valor'];
			return $diasTotales; //obtenemos el valor de los días totales del viaje para calcular cuánto $ nos queda
	}

	mysqli_close($conectar);
}
