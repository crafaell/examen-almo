<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Productos</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
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
	      <a class="nav-item nav-link" href="factura_nueva">Factura Nueva <span class="sr-only">(current)</span></a>
	      <a class="nav-item nav-link" href="#">Productos</a>
	      <a class="nav-item nav-link" href="clientes">Clientes</a>
	    </div>
	  </div>
	</nav>
	<h1>Listado de Productos</h1>
	<div id="body" class="col-12 col-md-11">
		<input type="button" onclick="ProductoAgregar();" value="Agregar Producto" class="btn btn-primary">
		<hr>
		<?php 
			$tabla = '<table id="tbl_productos" class="table table-stripped">
						<thead>
							<tr>
								<th>No.</th>
								<th>Nombre</th>
								<th>Precio</th>
								<th>Cantidad</th>
								<th>Estado</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>';
			$i = 1;
			foreach ($productos['productos'] as $key => $producto) {
				switch ($producto['estado']) {
					case '1':
						$estado = '<label style="color:green;">Activo</label>';
						break;
					case '2':
						$estado = '<label style="color:red;">Inactivo</label>';
						break;
					default:
						# code...
						break;
				}
				$tabla .= '<tr>
							<td>'.$i.'</td>
							<td>'.$producto['nombre'].'</td>
							<td>'.$producto['precio'].'</td>
							<td>'.$producto['cantidad'].'</td>
							<td>'.$estado.'</td>
							<td><input type="button" class="btn btn-warning" value="Editar" onclick="ProductoEditar('.$producto['id'].');"><input type="button" class="btn btn-danger" value="Eliminar" onclick="ProductoEliminar('.$producto['id'].',\''.$producto['nombre'].'\');"></td>
						</tr>';
				$i++;
			}
			$tabla .= '</tbody></table>';
			echo $tabla;
		?>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
<script src="../../includes/js/jquery-1.10.2.min.js"></script>
<script src="../../includes/js/funciones.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="http://elhijodelblues.net/includes/js/fancybox/source/jquery.fancybox.js"></script>
<script type="text/javascript">
	$( document ).ready(function() {
	    $('#tbl_productos').DataTable();
	});

	/*Funcion que muestra un formulario para agregar un nuevo producto con todas las validaciones de tipo de dato*/
	function ProductoAgregar(){
        var form = `<div class="col-12">
        				<h3>Agregar Producto</h3>
						<div class="col-12">
							<label>Nombre</label>
							<input type="text" class="form-control" id="txt_nombre">
						</div>
						<div class="col-12">
							<label>Precio</label>
							<input type="text" maxlength="7" id="txt_precio" class="form-control" onchange="MonedaValidar();" onkeypress="return CaracterValidar(event, 2)">
						</div>
						<div class="col-12">
							<label>Cantidad</label>
							<input type="text" maxlength="5" id="txt_cantidad" class="form-control" onkeypress="return CaracterValidar(event, 1)">
						</div>
						<div class="col-12">
							<label>Estado</label>
							<select id="slc_estado" class="form-control">
								<option value="1">Activo</option>
								<option value="2">Inactivo</option>
							</select>
						</div>
						<hr>
						<input type="button" class="btn btn-primary" onclick="ProductoAgregarConfirmar();" value="Agregar">
					</div>
        			`;
        AbrirAlerta(form, 'auto', '40%');
	}

	/*Funcion que realiza una peticion Ajax con los datos del producto para agregar uno nuevo*/
	function ProductoAgregarConfirmar(_producto_id){
		$.ajax({
            url: "producto_agregar",
            type: 'post',
            data: {
                nombre:$('#txt_nombre').val(),
                precio:$('#txt_precio').val(),
                cantidad:$('#txt_cantidad').val(),
                estado:$('#slc_estado').val(),
            },
            dataType: 'json',
            success: function(respuesta) {
                switch(parseInt(respuesta.res)){
                    case 1:
                        AbrirAlerta('Producto Agregado exitosamente.', 'auto', 'auto');
                        setInterval(function(){
                        	window.location.reload();
                        },2000);
                        break;
                    default:
                    	var mensaje_error = '<b>Error al obtener el producto. Intente nuevamente </b>';
                    	AbrirAlerta(mensaje_error, 'auto', 'auto');
                    	break;
                }
            },
            error: function (error){
                //alert(error);
            }
        });
	}

	/*Funcion que realiza una peticion Ajax con el id del producto para obtener su informacion y permitir modificarla con todas las validaciones segun el tipo de dato*/
	function ProductoEditar(_producto_id){
		$.ajax({
            url: "producto_obtener",
            type: 'post',
            data: {
                producto_id:_producto_id,
            },
            dataType: 'json',
            success: function(respuesta) {
                switch(parseInt(respuesta.res)){
                    case 1:
                    	var seleccionado = (respuesta.producto.estado == 2)?'selected="selected"':'';
                        var form = `<div class="col-12">
                        				<h3>Editar Producto</h3>
										<div class="col-12">
											<label>Nombre</label>
											<input type="text" value="${respuesta.producto.nombre}" class="form-control" id="txt_nombre">
										</div>
										<div class="col-12">
											<label>Precio</label>
											<input type="text" id="txt_precio" class="form-control" value="${respuesta.producto.precio}" onchange="MonedaValidar();" onkeypress="return CaracterValidar(event, 2)">
										</div>
										<div class="col-12">
											<label>Cantidad</label>
											<input type="number" id="txt_cantidad" class="form-control" value="${respuesta.producto.cantidad}" onkeypress="return CaracterValidar(event, 1)">
										</div>
										<div class="col-12">
											<label>Estado</label>
											<select id="slc_estado" class="form-control">
												<option value="1">Activo</option>
												<option value="2" ${seleccionado}>Inactivo</option>
											</select>
										</div>
										<hr>
										<input type="button" class="btn btn-primary" onclick="ProductoEditarConfirmar(${_producto_id});" value="Editar">
									</div>
                        			`;
                        AbrirAlerta(form, 'auto', '40%');
                        break;
                    default:
                    	var mensaje_error = '<b>Error al obtener el producto. Intente nuevamente </b>';
                    	AbrirAlerta(mensaje_error, 'auto', 'auto');
                    	break;
                }
            },
            error: function (error){
                //alert(error);
            }
        });
	}

	/*Funcion que realiza una peticion Ajax con los datos del producto para modificarlos segun su id*/
	function ProductoEditarConfirmar(_producto_id){
		$.ajax({
            url: "producto_editar",
            type: 'post',
            data: {
                producto_id:_producto_id,
                nombre:$('#txt_nombre').val(),
                precio:$('#txt_precio').val(),
                cantidad:$('#txt_cantidad').val(),
                estado:$('#slc_estado').val(),
            },
            dataType: 'json',
            success: function(respuesta) {
                switch(parseInt(respuesta.res)){
                    case 1:
                        AbrirAlerta('Producto Modificado exitosamente.', 'auto', 'auto');
                        setInterval(function(){
                        	window.location.reload();
                        },2000);
                        break;
                    default:
                    	var mensaje_error = '<b>Error al obtener el producto. Intente nuevamente </b>';
                    	AbrirAlerta(mensaje_error, 'auto', 'auto');
                    	break;
                }
            },
            error: function (error){
                //alert(error);
            }
        });
	}

	/*Funcion que muestra un mensaje de advertencia previo a confirmar la eliminacion*/
	function ProductoEliminar(_producto_id, _nombre){
		var mensaje = '<div>Esta seguro de querer elminar el producto '+_nombre+'?.</div><hr><input type="button" class="btn btn-danger" onclick="ProductoEliminarConfirmar('+_producto_id+');" value="Eliminar">';
		AbrirAlerta(mensaje, 'auto','auto');
	}

	/*Funcion que realiza una peticion Ajax con el id del producto para su eliminación logica*/
	function ProductoEliminarConfirmar(_producto_id){
		$.ajax({
            url: "producto_eliminar",
            type: 'post',
            data: {
                producto_id:_producto_id
            },
            dataType: 'json',
            success: function(respuesta) {
                switch(parseInt(respuesta.res)){
                    case 1:
                        AbrirAlerta('Producto eliminado exitosamente.', 'auto', 'auto');
                        setInterval(function(){
                        	window.location.reload();
                        },2000);
                        break;
                    default:
                    	var mensaje_error = '<b>Error al obtener el producto. Intente nuevamente </b>';
                    	AbrirAlerta(mensaje_error, 'auto', 'auto');
                    	break;
                }
            },
            error: function (error){
                //alert(error);
            }
        });
	}

	/*Funcion que verifica que el campo de texto del precio del producto siempre tenga 2 decimales*/
	function MonedaValidar(){
		var precio = parseFloat($('#txt_precio').val());
		$('#txt_precio').val(precio.toFixed(2));
	}
</script>
</body>
</html>