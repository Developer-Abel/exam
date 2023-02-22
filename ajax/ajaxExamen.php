
<?php 

include '../controller/ExamenController.php';

$view = "http://localhost/devgala/abel/indeplo/";
$Examen = new ExamenControler();

$accion = $_POST['accion'];

if ($accion == "inserSerie") {
	$insert = $Examen->inserSerie();
	header("Location: ".$view."index.php?message=Insertado correctamente");
}elseif($accion == 'insertFact'){
	$error = 0;
	$message = "";

	$cliente  = $_POST['cliente'];
	$codigo   = $_POST['codigo'];
	$monto    = $_POST['monto'];
	$fecha    = $_POST['fecha'];

	if (is_numeric($monto)) {
		$monto = number_format($monto, 2);
	}else{
		$error = 1;
		$message .= " Monto invalido <br> ";
	}

	$val_fecha = setOnlyNumbers($fecha);
	if (isValidDate($val_fecha) == 0) {
		$error = 1;
		$message .= " Fecha invalida <br>";
	}
	if ($cliente == '') {
		$error = 1;
		$message .= " El campo cliente es obligatorio <br>";
	}
	if ($codigo == '') {
		$error = 1;
		$message .= " El campo codigo es obligatorio <br>";
	}

	if ($error == 0) {
		echo "Datos correctos para insertar";
	}else{
		echo "validacion: <br><br>".$message;
	}

}elseif($accion == 'getListado'){
	$getlistado = $Examen->getListado();
	$row = '';
	foreach ($getlistado as $key) {
		$row = $row.'<tr>
		      <td>'.$key->factura.'</td>
		      <td>'.$key->fecha.'</td>
		      <td>'.$key->pedido.'</td>
		      <td>'.$key->monto.'</td>
		      <td>'.$key->razon_social.'</td>
		    </tr>';
	}
	echo '<table class="table">
		  <thead>
		    <tr>
		      <th scope="col">Factura</th>
		      <th scope="col">Fecha</th>
		      <th scope="col">Pedido</th>
		      <th scope="col">Monto</th>
		      <th scope="col">Razon Social Cliente</th>
		    </tr>
		  </thead>
		  <tbody>
		    '.$row.'
		  </tbody>
		</table>';
}elseif($accion == 'getReporte'){
	$row = '';
	$getReporte = $Examen->getReporte();
	foreach ($getReporte as $key) {
		$row = $row.'<tr>
		      <td>'.$key->val1.'</td>
		      <td>'.$key->val2.'</td>
		    </tr>';
	}
	echo '<table class="table">
		  <thead>
		    <tr>
		      <th scope="col">Dato</th>
		      <th scope="col">Valor</th>
		    </tr>
		  </thead>
		  <tbody>
		    '.$row.'
		  </tbody>
		</table>';

	echo "<pre>";
}if ($accion == 'setImage') {
	move_uploaded_file($_FILES['imagen']['tmp_name'], "img/".$_FILES['imagen']['name']);
	$imagen = $_FILES['imagen']['name'];
	$imagen = $Examen->setImagen($imagen);

	$arrimg = '';
	foreach ($imagen as $key) {
		$arrimg = $arrimg.'<img src="img/'.$key->img.'" width="20%">';
	}
	echo "<div>".$arrimg."</div>";
}




// FUNCIONES FLOBALES

function isValidDate($content, $contenttype = "") {

		$functionresult = 0;	// result value handler

		// Regular Expression for funcion
		$regexp = "/^[0-9]+$/";

		// Content Validation
		$content = trim($content);


		if ($contenttype == "dd/mm/yyyy") {
			$content = str_replace("/", "", $content);
			$content = substr($content, 4, 4).substr($content, 2, 2).substr($content, 0, 2);
		}

		if ($contenttype == "mm/dd/yyyy") {
			$content = str_replace("/", "", $content);
			$content = substr($content, 4, 4).substr($content, 0, 2).substr($content, 2, 2);
		}

			// Extraemos los parametros por separado
			$year  = "0000";
			$month = "00";
			$day   = "00";
			if (strlen($content) == 8) {
				$year  = substr($content, 0, 4);
				$month = substr($content, 4, 2);
				$day   = substr($content, 6, 2);
			}

		// Regular Expression Validation
		if (preg_match($regexp, $content)) {
			//$functionresult = 1;

			// Date Validation
			if (checkdate($month, $day, $year )) {
				$functionresult = 1;
			}

		}

			
		return $functionresult;
	}

	function setOnlyNumbers($content) {

		$functionresult = "";	// result value handler

		// Regular Expression for funcion
		$regexp = "/[^0-9]/";

		// Content Validation
		$content = trim($content);

		$content = preg_replace($regexp, "", $content);

		$content = trim($content);

		$functionresult = $content;

		return $functionresult;
	}
