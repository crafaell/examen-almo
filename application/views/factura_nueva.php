<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Factura nueva</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href='http://elhijodelblues.net/includes/js/fancybox/source/jquery.fancybox.css' rel='stylesheet' type='text/css'>
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
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	  <a class="navbar-brand" href="../../">Facturas</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
	    <div class="navbar-nav">
	      <a class="nav-item nav-link active" href="#">Factura Nueva <span class="sr-only">(current)</span></a>
	      <a class="nav-item nav-link" href="productos">Productos</a>
	      <a class="nav-item nav-link" href="clientes">Clientes</a>
	    </div>
	  </div>
	</nav>
	<h1>Agregar Factura</h1>
	<div id="body" class="col-12 col-md-11">
		<div class="row">
			<div class="col-6">
				<label>Factura No.</label>
				<input type="number" value="<?php echo intval($facturas_totales['facturas_totales'][0]['total'])+1; ?>" class="form-control" readonly>
			</div>
			<div class="col-6">
				<label>Seleccione la fecha de la factura</label>
				<input type="date" name="fecha" id="txt_fecha" class="form-control">
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<label>Seleccione el cliente</label>
				<select id="slc_cliente" name="cliente" class="form-control">
					<?php
						if($clientes['res'] == 1){ 
							foreach ($clientes['clientes'] as $key => $cliente) {
								echo '<option value="'.$cliente['id'].'">'.$cliente['nombres'].' '.$cliente['apellidos'].'</option>';
							}
						}
					?>
				</select>
			</div>
			<div class="col-6">
				<label>Seleccione el producto</label>
				<select id="slc_producto" name="producto" class="form-control">
					<?php 
						if($productos['res'] == 1){
							foreach ($productos['productos'] as $key => $producto) {
								echo '<option value="'.$producto['id'].'" data-precio="'.$producto['precio'].'">'.$producto['nombre'].' - '.$producto['precio'].'</option>';
							}
						}
					?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<label>Ingrese la cantidad</label>
				<input type="number" id="txt_cantidad" class="form-control">
			</div>
			<div class="col-6 mt-4">
				<input type="button" class="btn-secundary" onclick="ProductoAgregar();" value="Agregar Producto">
			</div>
		</div>
		<div class="row col-12" id="tbl_productos"></div>
		<div id="div_total" class="mr-2"></div>
		<hr>
		<div class="row">
			<input type="button" class="btn btn-primary" value="Guardar" onclick="FacturaEnviar();">
		</div>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
<script src="../../includes/js/jquery-1.10.2.min.js"></script>
<script src="../../includes/js/funciones.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="http://elhijodelblues.net/includes/js/fancybox/source/jquery.fancybox.js"></script>
<script type="text/javascript">

	var productos_agregados = [];
	var productos_ids = [];
	function ProductoAgregar(){
		var cantidad = parseInt($('#txt_cantidad').val());
		if(cantidad > 0){
			var total = 0;
			var producto_id = $('#slc_producto').val();
			var producto_nombre = $("#slc_producto option:selected").text();
			producto_nombre = producto_nombre.split('-');
			producto_nombre = producto_nombre[0];
			var producto_precio = $("#slc_producto option:selected").attr('data-precio');
			if(productos_ids.indexOf(producto_id) > -1){
				AbrirAlerta('Ese producto ya esta agregado', 'auto', 'auto');
			}
			else{
				productos_ids.push(producto_id);
				productos_agregados.push([producto_id, producto_nombre, cantidad, producto_precio]);
			}
			var i = 1;
			var tabla = `<table class="table table-stripped">
							<thead>
								<tr>
									<th>No.</th>
									<th>Producto</th>
									<th>Cantidad</th>
									<th>Precio</th>
									<th>Subtotal</th>
									<th>Opciones</th>
								</tr>
							</thead>
							<tbody>`;
			$.each(productos_agregados, function(indice, producto){
				var subtotal = parseFloat(producto[3])*parseInt(producto[2]);
				tabla += `<tr>
							<td>${i}</td>
							<td>${producto[1]}</td>
							<td>${producto[2]}</td>
							<td>${producto[3]}</td>
							<td>${subtotal.toFixed(2)}</td>
							<td><input type="button" class="btn btn-danger" value="Eliminar" onclick="ProductoEliminar(this, ${producto[0]});"></td>
						</tr>`;
				total += parseFloat(subtotal);
				i++;
			});
			tabla += '</tbody></table>';
			$('#txt_cantidad').val('');
			$('#tbl_productos').html(tabla);
			$('#div_total').html('<b>Total: '+total.toFixed(2)+'</b>');
		}
		else{
			AbrirAlerta('Ingrese una cantidad mayor a 0', 'auto', 'auto');
		}
	}

	function ProductoEliminar(_elemento, _producto_id){
		$(_elemento).parent().parent().remove();
		var existe = productos_ids.indexOf(_producto_id.toString());
		if(existe > -1){
			productos_agregados.splice(existe, 1);
			productos_ids.splice(existe, 1);
			AbrirAlerta('Producto Removido de la factura', 'auto', 'auto');
			TotalCalcular();
		}
	}

	function FacturaEnviar(){
		var fecha = $('#txt_fecha').val();
		if(fecha !='' && productos_agregados.length > 0){
			$.ajax({
                url: "factura_agregar",
                type: 'post',
                data: {
                    productos:JSON.stringify(productos_agregados),
                    cliente:$('#slc_cliente').val(),
                    fecha:$('#txt_fecha').val()
                },
                dataType: 'json',
                success: function(respuesta) {
                    switch(parseInt(respuesta.res)){
                        case 1:
                            var mensaje = 'Factura Guardada exitosamente.';
                            AbrirAlerta(mensaje, 'auto', 'auto');
                            setInterval(function(){
                            	window.location.href = "../../";
                            },3000);
                            break;
                        case 2:
                        	var mensaje_error = '<b>Error al guardar la factura. </b></br>';
                        	$.each(respuesta.errores, function(indice, error){
                        		mensaje_error += '<div>'+error+'</div>';
                        	});
                            AbrirAlerta(mensaje_error, 'auto', 'auto');
                        	break;
                        default:
                            mensaje = 'Error guardar la factura. Intente nuevamente.';
                            jQuery('#div_mensaje').html(mensaje);
                            break;
                    }
                },
                error: function (error){
                    //alert(error);
                }
            });
		}
		else{
			AbrirAlerta('Ingrese la fecha de la factura. Debe seleccionar al menos un producto.', 'auto', 'auto');
		}
	}

	function TotalCalcular(){
		var total = 0;
		$.each(productos_agregados, function(indice, producto){
			var subtotal = parseFloat(producto[3])*parseInt(producto[2]);
			total += parseFloat(subtotal);
		});
		$('#div_total').html('<b>Total: '+total.toFixed(2)+'</b>');
	}
</script>
</body>
</html>