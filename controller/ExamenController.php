
<?php 

include "../model/ExamenModel.php";


Class ExamenControler{
	private $mdlJuego;

	function __construct(){
		$this->mdlExamen = new ExamenModel();
	}
	function InserSerie(){
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
		return $insert;
	}
	function getListado(){
		return $getlistado = $this->mdlExamen->getListado();
	}
	function getReporte(){
		return $getReporte = $this->mdlExamen->getReporte();
	}
	function setImagen($imagen){
		$Imagen = $this->mdlExamen->setImagen($imagen);
		if ($Imagen) {
			return $this->mdlExamen->getImagen();
		}
	}

}