<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>Agregar Factura</h1>

	<div id="body">
		<form id="frm_productos" method="post" action="index.php/FacturaController/factura_agregar">
			<div>
				<label>Ingrese el numero de factura</label>
				<input type="number" name="numero" id="txt_numero">
			</div>
			<div>
				<label>Seleccione la fecha de la factura</label>
				<input type="date" name="fecha" id="txt_fecha">
			</div>
			<div>
				<label>Seleccione el cliente</label>
				<select id="slc_cliente" name="cliente">
					<?php
						if($clientes['res'] == 1){ 
							foreach ($clientes['clientes'] as $key => $cliente) {
								echo '<option value="'.$cliente['id'].'">'.$cliente['nombre'].'</option>';
							}
						}
					?>
				</select>
			</div>
			<div>
				<label>Seleccione el producto</label>
				<select id="slc_producto" name="producto">
					<?php 
						if($productos['res'] == 1){
							foreach ($productos['productos'] as $key => $producto) {
								echo '<option value="'.$producto['id'].'" data-precio="'.$producto['precio'].'">'.$producto['nombre'].' - '.$producto['precio'].'</option>';
							}
						}
					?>
				</select>
				<input type="hidden" id="hdd_productos" name="hdd_productos">
				<input type="number" id="txt_cantidad">
				<input type="button" class="btn" value="Agregar Producto" onclick="ProductoAgregar();">
			</div>
			<div id="tbl_productos"></div>
			<div>
				<input type="button" value="Guardar" onclick="FacturaEnviar();">
			</div>
		</form>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
<script type="text/javascript" href="includes/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
	var productos_agregados = [];
	function ProductoAgregar(){
		var cantidad = parseInt($('#txt_cantidad').val());
		if(cantidad > 0){
			productos_agregados.push([$('#slc_producto').val(), $("#slc_producto option:selected").text(), cantidad, $("#slc_producto").attr('data-precio')]);
			var i = 0;
			var tabla = `<table>
							<thead>
								<tr>
									<th>No.</th>
									<th>Producto</th>
									<th>Cantidad</th>
									<th>Precio</th>
									<th>Opciones</th>
								</tr>
							</thead>
							<tbody>`;
			$.each(productos_agregados, function(producto, indice){
				tabla += `<tr>
							<td>${i}</td>
							<td>${producto[1]}</td>
							<td>${producto[2]}</td>
							<td>${producto[3]}</td>
							<td><input type="button" value="Eliminar" onclick="ProductoEliminar(this, ${producto[0]});"></td>
						</tr>`;
			});
			tabla += '</tbody></table>';
			$('#tbl_productos').html(tabla);
		}
		else{
			alert('Ingrese una cantidad mayor a 0');
		}
	}

	function ProductoEliminar(_elemento, _producto_id){
		$(_elemento).parent().remove();
		$.each(productos_agregados, function(producto, indice){
			if(producto[0] == _producto_id){
				productos_agregados.splice(productos_agregados[indice], 1);
			}
		});
		alert('Producto Removido de la factura.');
	}

	function FacturaEnviar(){
		var numero = $('#txt_numero').val();
		var fecha = $('#txt_fecha').val();
		if(numero != '' && fecha !='' && productos_agregados.length > 0){
			$('#hdd_productos').val(productos_agregados);
			$('#frm_productos').submit();
		}
		else{
			alert('Ingrese el numero de factura y la fecha. Debe seleccionar al menos un producto.');
		}
	}
</script>
</body>
</html>