<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1>Examen</h1>
			</div>
			<div class="row">
				<div class="col-12">
					<p>1.- Diseñar una tabla de base de datos y generar el código PHP, que permita insertar la siguiente serie  1 , 2 , 3 , 5 , 8 , 13 , 21, ..., n. y detener la ejecución al obtener el maximo permitido por el tipo de campo int</p>
<pre>
	$x = 1;
	$a = 0;
	$b = 1;
	$c = 0;

	for ($i=0; $i <= $x+$i; $i++) { 
		$b = $a + $b;
		$a = $c - $a;
		$c = $a + $b;
		if ($c >= 2147483647) {
			break;
		}
		$insert = $this->mdlExamen->InserSerie($c);
	}
</pre>
					<form action="ajax/ajaxExamen.php" method="post" >
						<input type="hidden" name="accion" value="inserSerie">
						<button class="btn btn-success">Probar</button>
						<span><?php if (isset($_GET['message'])) { echo $_GET['message']; } ?></span>
					</form>
				</div>
				<div class="col-12 mt-2">
					<p>2.- ¿Cuál es la función que utilizas para hacer una conexión en PHP a una base de datos en MySQL? Y desarrolla el código de la conexión. <small><b>El codigo se encuentra en esta ruta: examen/model/conexion.php</b></small></p>
<pre>
	class Conexion{
		static public function conectar(){
			$link = new PDO("mysql:host=localhost;dbname=indeplo",
			"root",
			"");
			$link->exec("set names utf8");
			return $link;
		}
	}
</pre>
<hr>
<pre>
	include "conexion.php";

	Class ExamenModel{

		public function InserSerie($Codigo){
			$stmt = Conexion::conectar()->prepare("INSERT INTO serie (Codigo) VALUES (:Codigo)");
			$stmt->bindParam(":Codigo",  $Codigo, PDO::PARAM_INT);

			if($stmt->execute()){
				return true;
			}else{
				return false;
			}

			$stmt->close();
			$stmt = null;
		}
	}
</pre>
				</div>

				<div class="col-12">
					<p>3.- En el siguiente ejemplo el campo de la base de datos de fecha se está guardando de la siguiente forma 0000-00-00, Solucionar este problema. <small><b>El codigo de las funciones esta en la ruta /ajaxa/ajaxExamen.php</b></small></p>
<pre>
	$val_fecha = setOnlyNumbers($fecha);
	if (isValidDate($val_fecha) == 0) {
		$error = 1;
		$message .= " Fecha invalida";
	}
</pre>

					<div class="row">
						<div class="col-6">
							<p>4.- Completar el script anterior y agregar las validaciones necesarias para evitar posibles errores o noticias. <small><b>El codigo de las funciones esta en la ruta /ajax/ajaxExamen.php</b></small></p>
							<form action="ajax/ajaxExamen.php" method="post"  class="p-3" style="border: 1px solid #D9D9D9">
								<input type="hidden" name="accion" value="insertFact">
								<div class="mb-3 row">
									<label  class="col-sm-2 col-form-label">Cliente</label>
									<div class="col-sm-10">
										<input type="text"  name="cliente" class="form-control">
									</div>
								</div>
								<div class="mb-3 row">
									<label  class="col-sm-2 col-form-label">Código</label>
									<div class="col-sm-10">
										<input type="text" name="codigo"  class="form-control">
									</div>
								</div>
								<div class="mb-3 row">
									<label  class="col-sm-2 col-form-label">Monto</label>
									<div class="col-sm-10">
										<input type="text" name="monto"  class="form-control">
									</div>
								</div>
								<div class="mb-3 row">
									<label  class="col-sm-2 col-form-label">Fecha</label>
									<div class="col-sm-10">
										<input type="text" name="fecha" class="form-control">
									</div>
								</div>
								<div class="mb-3 row">
									<button type="submit" class="btn btn-success">probar validación</button>
								</div>
							</form>
						</div>
					</div>
<pre>

if (is_numeric($monto)) {
	$monto = number_format($monto, 2);
}else{
	$error = 1;
	$message .= " Monto invalido ";
}

$val_fecha = setOnlyNumbers($fecha);
if (isValidDate($val_fecha) == 0) {
	$error = 1;
	$message .= " Fecha invalida";
}
if ($cliente == '') {
	$error = 1;
	$message .= " El campo cliente es obligatorio";
}
if ($codigo == '') {
	$error = 1;
	$message .= " El campo codigo es obligatorio";
}

if ($error == 0) {
	echo "Datos correctos para insertar";
}else{
	echo "validacion:".$message;
}
</pre>
				</div>

				<div class="row">
					<div class="col-12">
						<p>5.- De la siguiente base de datos Crear un listado usando PHP y conexión a MYSQL, que liste las facturas, incluyendo fecha de factura, el pedido, al que pertenece, su monto y el cliente correspondiente. <small><b>El codigo de las funciones esta en la ruta /ajax/ajaxExamen.php</b></small></p>
<pre>
SELECT 
	facturas.id,
	facturas.codigo AS factura,
	facturas.fecha,
	(SELECT DISTINCT codigo FROM pedidos WHERE id = pedido.pedido_id ) AS pedido,
	total AS monto,
	(SELECT DISTINCT razon_social FROM clientes WHERE id = pedido.cliente_id) AS razon_social
FROM facturas 
INNER JOIN (
	SELECT cliente_id, pedido_id, SUM(subtotal) AS total FROM (
		SELECT 
			pedidos_items.pedido_id, 
			pedidos.cliente_id,
			(SUM(pedidos_items.cantidad) * SUM(articulos.precio)) AS subtotal
		FROM pedidos_items
		INNER JOIN articulos ON articulos.id = pedidos_items.articulo_id
		INNER JOIN pedidos ON pedidos.id = pedidos_items.pedido_id
		GROUP BY pedidos_items.id
	) subtotal
	GROUP BY pedido_id,cliente_id
) pedido ON pedido.pedido_id = facturas.pedido_id
</pre>
						<form action="ajax/ajaxExamen.php" method="post">
							<input type="hidden" name="accion" value="getListado">
							<button type="submit" class="btn btn-success">Obtener listado</button>
						</form>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<p>6.- Utilizando la misma base de datos, generar un query que nos permita:
						obtener el monto total mensual en formato de moneda 
						número de documentos generados en facturas y pedidos por cliente.
						<small><b>El codigo de las funciones esta en la ruta /model/ExamenModel.php</b></small></p>
<pre>
SELECT 
	CONCAT('MES ',MONTH(facturas.fecha)) val1,
	CONCAT('MONTO ',SUM(total)) as val2
FROM facturas 
INNER JOIN (
	SELECT cliente_id, pedido_id, SUM(subtotal) AS total FROM (
		SELECT 
			pedidos_items.pedido_id, 
			pedidos.cliente_id,
			(SUM(pedidos_items.cantidad) * SUM(articulos.precio)) AS subtotal
		FROM pedidos_items
		INNER JOIN articulos ON articulos.id = pedidos_items.articulo_id
		INNER JOIN pedidos ON pedidos.id = pedidos_items.pedido_id
		GROUP BY pedidos_items.id
	) subtotal
	GROUP BY pedido_id,cliente_id
) pedido ON pedido.pedido_id = facturas.pedido_id
GROUP BY MONTH(facturas.fecha)
UNION ALL

SELECT 'FACTURAS' val1, CONCAT('TOTAL ',COUNT(*)) val2 FROM facturas

UNION ALL 

SELECT 
	(SELECT razon_social FROM clientes WHERE id = cliente.id) val1,
	CONCAT('TOTAL ',pedidos) val2
FROM (
	SELECT 
	clientes.id , COUNT(*) pedidos 
	FROM clientes
	INNER JOIN pedidos  ON pedidos.cliente_id = clientes.id
	GROUP BY clientes.id
) cliente
</pre>
						<form action="ajax/ajaxExamen.php" method="post">
							<input type="hidden" name="accion" value="getReporte">
							<button type="submit" class="btn btn-success">Obtener datos</button>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<p>7.- Enumera los pasos que seguirías para cargar un archivo de Excel  a la tabla de facturas usada en el ejercicio 2 y 3.</p>
						<ul>
							<li>
								1.- Validar los datos del excel (corroborar tipo de dato y columnas)
							</li>
							<li>2.- Quitar formatos</li>
							<li>3.- Convertir excel a .csv (separado por comas)</li>
							<li>4.- Ir a una interfaz de base de datos (phpMydmin por ejemplo) importar archivo .csv</li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<p>8.- Enumera de 1 a 3 páginas web que uses de consulta. </p>
						<ul>
							<li>https://www.w3schools.com/</li>
							<li>https://conclase.net/mysql/curso</li>
							<li>https://css-tricks.com/</li>
							<li>https://laravel.com/docs/8.x</li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<p>9.- Describe para que sirven los siguientes arreglos globales <b> $_POST, $_GET, $_SESSION, $_REQUEST, $_SERVER, $_FILES</b> </p>
						
						<ul>
							<li>$_POST: Sirve para obtener los datos que se envian por el método POST</li>
							<li>$_GET: Sirve para obtener los datos que se envian por el método GET</li>
							<li>$_SESSION: Sirve para guardar datos de sesion, nombre, id, rol del que se logea</li>
							<li>$_REQUEST: sirve para recuperar los datos que se envian por http, incluyendo los cookies</li>
							<li>$_SERVER: esta valiable nos puede mostrar datos especificos como la ip, el protocolo que se esta utilizando, el host, entre otros</li>
							<li>$_FILES: una variable que alamcena los datos de archivos (ficheros), almacena nombre, tamaño de archivo, formato etc</li>
						</ul>
					</div>
				</div>
				<style type="text/css">
					.formImg{
						box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
					}
				</style>
				<div class="row">
					<div class="col-12">
						<p>10.- Crear un formulario usando HTML, para procesar imagenes y guardarla en una BD. </p>
						<div class="row">
							<div class="col-12 p-4">
								<form action="ajax/ajaxExamen.php" method="post" enctype="multipart/form-data" class="formImg p-3">
									<input type="hidden" name="accion" value="setImage">
									<div class="mb-3">
									  <label for="" class="form-label">Subir imagen</label>
									  <input type="file" class="form-control" name="imagen" accept="image/*">
									</div>
									<div class="mb-3">
									    <button type="submit" class="btn btn-success">Subir</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<p>11.- Centrar una imagen tanto horizontal y vertical que se encuentra dentro de las etiquetas. a </p>
						<div class="" style="height: 200px; width: 200px; border: 1px solid gray; display: flex; align-items: center;">
							<a href="" style="display: flex; justify-content:center; align-items: center;" >
									<img src="ajax/img/cocodrilo.PNG" width="50%">
							</a>
						</div>
<pre>
div class="" 
	style="height: 200px; width: 200px; border: 1px solid gray; 
	display: flex; align-items: center;">
	a href="" style="display: flex; justify-content:center; align-items: center;" 
			img src="ajax/img/cocodrilo.PNG" width="50%"
	/a
/div
</pre>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<p>12.- Darle formato al HTML del formulario anterior de la pregunta 10 usando CSS.</p>
<pre>
	.formImg{
		box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
	}
</pre>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<p>13.- Elaborar un CRUD de facturas (usando las tablas anteriores) haciendo uso de Laravel</p>
						<p>Obtener todos las facturas: <br>[GET] http://localhost:8000/getfacturaAll</p>
						<p>Agregar pedidos: <br>[POST]  http://localhost:8000/addPedido</p>
<pre>
	clienteid:2
	codigo:1004
	fecha:2023-02-11
	articulo_id:2
	cantidad:2
</pre>
						<p>Agregar factura: <br>[POST]  http://localhost:8000/addFactura</p>
<pre>
	pedido_id:1
	codigo:78432
	fecha:2023-03-02
</pre>
						<p>buscar factura: <br>[POST]  http://localhost:8000/findfactura</p>
<pre>
	codigo:78431
</pre>
					<h5>Ejemplo de resultado</h5>
					<img src="Captura.jpg" width="60%">
					</div>
				</div>
				<br><br>
			</div>
		</div>
	</div>
	<div class="row mt-4"></div>

</body>
</html>