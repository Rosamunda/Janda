<?php
/*
* Aplicación rudimentaria para calcular gastos.
* La funcionalidad incluye determinar los siguientes datos:
* Cuánto se gasta por día
* Promedio de gastos y promedio por rubro (diario, semanal y mensual)
* Si el gasto se hizo en efectivo o con tarjeta
* Totales por fecha, por semana, por mes, por rubro y en efectivo o tarjeta
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Janda App</title>
<link type="text/css" rel="stylesheet" href="estilos.css" />
<style>
body {font-size: 1.3em;}
.extraordinario {width:2%;margin-left:1%;margin-right:1%;border-color:red;}
.resaltar {color:red;font-weight:bold;}
.left {float:left;}
.right {float:right; margin-right:10%;}
</style>
</head>

<body>
<?php
//incluímos el archivo que tiene las funciones que usaremos
include 'funciones.php';


//antes de mostrar la fecha de hoy, le decimos la timezone en la que estamos
//las timezones están en http://php.net/manual/en/timezones.america.php
date_default_timezone_set('America/Argentina/Buenos_Aires');
//luego metemos en una variable el día de hoy, para que lo muestre 
//automáticamente el formulario
$fechaHoy = date('Y/m/d');

//obtenemos el monto inicial de la función obtenerMontoInicial() y 
//lo metemos en una variable.
$inicial = obtenerMontoInicial();
$diasTotales = obtenerDiasTotales();

?>
<h1>Cálculo diario de Gastos :: Control para Viaje</h1>
<form name="calculo" action="" method="POST">
	<b>Día</b> <input type="text" name="fecha" value="<?php echo $fechaHoy;?>" /> 
	<b>$</b> <input type="text" name="monto" /> 
	<b>Rubro</b> <input type="text" name="rubro" /><br>
	supermercado, almuerzo, cafe, limpieza, transporte, etc <br>
	<input type="radio" name="tipoGasto" value="efectivo" checked><b>Efectivo</b>  
	<input type="radio" name="tipoGasto" value="tarjeta"><b>Tarjeta</b>
	<input type="text" name="extraordinario" value="" class="extraordinario"><b>Gasto Extraordinario?</b>
<br><br>
	<b>Día</b> <input type="text" name="fecha2" value="<?php echo $fechaHoy;?>" /> 
	<b>$</b> <input type="text" name="monto2" /> 
	<b>Rubro</b> <input type="text" name="rubro2" /><br>
	supermercado, almuerzo, cafe, limpieza, transporte, etc <br>
	<input type="radio" name="tipoGasto2" value="efectivo" checked><b>Efectivo</b>  
	<input type="radio" name="tipoGasto2" value="tarjeta"><b>Tarjeta</b>
	<input type="text" name="extraordinario2" value="" class="extraordinario"><b>Gasto Extraordinario?</b>
<br><br>
	<b>Día</b> <input type="text" name="fecha3" value="<?php echo $fechaHoy;?>" /> 
	<b>$</b> <input type="text" name="monto3" /> 
	<b>Rubro</b> <input type="text" name="rubro3" /><br>
	supermercado, almuerzo, cafe, limpieza, transporte, etc <br>
	<input type="radio" name="tipoGasto3" value="efectivo" checked><b>Efectivo</b>  
	<input type="radio" name="tipoGasto3" value="tarjeta"><b>Tarjeta</b>
	<input type="text" name="extraordinario3" value="" class="extraordinario"><b>Gasto Extraordinario?</b>
<br><br>
	<b>Día</b> <input type="text" name="fecha4" value="<?php echo $fechaHoy;?>" /> 
	<b>$</b> <input type="text" name="monto4" /> 
	<b>Rubro</b> <input type="text" name="rubro4" /><br>
	supermercado, almuerzo, cafe, limpieza, transporte, etc <br>
	<input type="radio" name="tipoGasto4" value="efectivo" checked><b>Efectivo</b>  
	<input type="radio" name="tipoGasto4" value="tarjeta"><b>Tarjeta</b>	
	<input type="text" name="extraordinario4" value="" class="extraordinario"><b>Gasto Extraordinario?</b>
	<br><br>
	Monto Inicial con el que cuento: $<input type="text" name="montoInicial" value="<?php echo $inicial;?>"><br>
	Días totales del viaje: <input type="text" name="diasTotales" value="<?php echo $diasTotales;?>"> días.
	<br><br>
	<input type="submit" name="submit" value="Cargar">

</form>

<?php


if($_SERVER['REQUEST_METHOD']=='POST'){
// al hacer un submit al form, guardamos los datos en la base lamando 
// a la función guardarDatos() 
	guardarDatos();
	}

	echo '<div class="left">';
	mostrarDatos();
	echo '</div>';


	echo '<div class="right">';
	//obtenemos el efectivo con el que contamos al día de hoy

	echo 'Total gastado hasta el momento: $ <b>'. calcularGastoTotal().'</b><br>';
	echo 'Total gastado en efectivo: $ <b>'. gastoEfectivo().'</b><br>';
	echo 'Total gastado con tarjeta: $ <b>'. gastoTarjeta().'</b><br>';
	echo '<br>Totales por día:<br>';
	echo gastoDiario();
	echo '<br>';
	promedioDiario();

	//obtenemos el dinero que nos resta, si mantenemos el promedio actual.
	$restanteEfectivo = $inicial - gastoEfectivo();
	global $promedio;
	global $cantDias;
	$diasRestantes = $diasTotales - $cantDias;
	$totalGastosPrevisto = $promedio * $diasRestantes;
	$totalRestantePrevisto = $restanteEfectivo - $totalGastosPrevisto;

	echo 'Dinero que resta en efectivo: $ <b>'.$restanteEfectivo.'</b><br><br>';

	echo '<b>Previsiones si se mantiene este promedio:</b><br>';
	echo 'Gasto por los <b>'.$diasRestantes.'</b> días restantes $ <b>'.round($totalGastosPrevisto, 2).'</b><br>';
	echo 'Restarían $ <b>'.round($totalRestantePrevisto, 2).'</b><br>';

	echo '</div>';
