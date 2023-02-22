<?php 

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
	function getListado(){
		$stmt=Conexion::conectar()->prepare("SELECT 
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
			) pedido ON pedido.pedido_id = facturas.pedido_id");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);

		$stmt -> close();
		$stmt = null;
	}
	function getReporte(){
		$stmt=Conexion::conectar()->prepare("SELECT 
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
			) cliente");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);

		$stmt -> close();
		$stmt = null;
	}

	function setImagen($imagen){
		$stmt = Conexion::conectar()->prepare("INSERT INTO imagen (img) VALUES (:img)");
		$stmt->bindParam(":img",  $imagen, PDO::PARAM_STR);

		if($stmt->execute()){
			return true;
		}else{
			return false;
		}

		$stmt->close();
		$stmt = null;
	}

	function getImagen(){
		$stmt=Conexion::conectar()->prepare("SELECT img FROM imagen");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);

		$stmt -> close();
		$stmt = null;
	}

}