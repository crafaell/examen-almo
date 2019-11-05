<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Clientes</title>
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
	      <a class="nav-item nav-link" href="productos">Productos</a>
	      <a class="nav-item nav-link" href="#">Clientes</a>
	    </div>
	  </div>
	</nav>
	<h1>Listado de Clientes</h1>
	<div id="body" class="col-12 col-md-11">
		<input type="button" onclick="ClienteAgregar();" value="Agregar Cliente" class="btn btn-primary">
		<hr>
		<?php 
			$tabla = '<table id="tbl_clientes" class="table table-stripped">
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
			foreach ($clientes['clientes'] as $key => $cliente) {
				switch ($cliente['estado']) {
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
							<td>'.$cliente['nombres'].'</td>
							<td>'.$cliente['apellidos'].'</td>
							<td>'.$cliente['nit'].'</td>
							<td>'.$estado.'</td>
							<td><input type="button" class="btn btn-warning" value="Editar" onclick="ClienteEditar('.$cliente['id'].');"><input type="button" class="btn btn-danger" value="Eliminar" onclick="ClienteEliminar('.$cliente['id'].',\''.$cliente['nombres'].'\');"></td>
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
	    $('#tbl_clientes').DataTable();
	});

	function ClienteAgregar(){
        var form = `<div class="col-12">
        				<h3>Agregar Cliente</h3>
						<div class="col-12">
							<label>Nombres</label>
							<input type="text" class="form-control" id="txt_nombres">
						</div>
						<div class="col-12">
							<label>Apellidos</label>
							<input type="text" id="txt_apellidos" class="form-control">
						</div>
						<div class="col-12">
							<label>NIT</label>
							<input type="text" id="txt_nit" class="form-control">
						</div>
						<div class="col-12">
							<label>Estado</label>
							<select id="slc_estado" class="form-control">
								<option value="1">Activo</option>
								<option value="2">Inactivo</option>
							</select>
						</div>
						<hr>
						<input type="button" class="btn btn-primary" onclick="ClienteAgregarConfirmar();" value="Agregar">
					</div>
        			`;
        AbrirAlerta(form, 'auto', '40%');
	}

	function ClienteAgregarConfirmar(_cliente_id){
		$.ajax({
            url: "cliente_agregar",
            type: 'post',
            data: {
                nombres:$('#txt_nombres').val(),
                apellidos:$('#txt_apellidos').val(),
                nit:$('#txt_nit').val(),
                estado:$('#slc_estado').val(),
            },
            dataType: 'json',
            success: function(respuesta) {
                switch(parseInt(respuesta.res)){
                    case 1:
                        AbrirAlerta('Cliente Agregado exitosamente.', 'auto', 'auto');
                        setInterval(function(){
                        	window.location.reload();
                        },2000);
                        break;
                    default:
                    	var mensaje_error = '<b>Error al obtener el cliente. Intente nuevamente </b>';
                    	AbrirAlerta(mensaje_error, 'auto', 'auto');
                    	break;
                }
            },
            error: function (error){
                //alert(error);
            }
        });
	}

	function ClienteEditar(_cliente_id){
		$.ajax({
            url: "cliente_obtener",
            type: 'post',
            data: {
                cliente_id:_cliente_id,
            },
            dataType: 'json',
            success: function(respuesta) {
                switch(parseInt(respuesta.res)){
                    case 1:
                    	var seleccionado = (respuesta.cliente.estado == 2)?'selected="selected"':'';
                        var form = `<div class="col-12">
                        				<h3>Editar Cliente</h3>
										<div class="col-12">
											<label>Nombres</label>
											<input type="text" value="${respuesta.cliente.nombres}" class="form-control" id="txt_nombres">
										</div>
										<div class="col-12">
											<label>Apellidos</label>
											<input type="text" id="txt_apellidos" class="form-control" value="${respuesta.cliente.apellidos}">
										</div>
										<div class="col-12">
											<label>NIT</label>
											<input type="number" id="txt_nit" class="form-control" value="${respuesta.cliente.nit}">
										</div>
										<div class="col-12">
											<label>Estado</label>
											<select id="slc_estado" class="form-control">
												<option value="1">Activo</option>
												<option value="2" ${seleccionado}>Inactivo</option>
											</select>
										</div>
										<hr>
										<input type="button" class="btn btn-primary" onclick="ClienteEditarConfirmar(${_cliente_id});" value="Editar">
									</div>
                        			`;
                        AbrirAlerta(form, 'auto', '40%');
                        break;
                    default:
                    	var mensaje_error = '<b>Error al obtener el cliente. Intente nuevamente </b>';
                    	AbrirAlerta(mensaje_error, 'auto', 'auto');
                    	break;
                }
            },
            error: function (error){
                //alert(error);
            }
        });
	}

	function ClienteEditarConfirmar(_cliente_id){
		$.ajax({
            url: "cliente_editar",
            type: 'post',
            data: {
                cliente_id:_cliente_id,
                nombres:$('#txt_nombres').val(),
                apellidos:$('#txt_apellidos').val(),
                nit:$('#txt_nit').val(),
                estado:$('#slc_estado').val(),
            },
            dataType: 'json',
            success: function(respuesta) {
                switch(parseInt(respuesta.res)){
                    case 1:
                        AbrirAlerta('Cliente Modificado exitosamente.', 'auto', 'auto');
                        setInterval(function(){
                        	window.location.reload();
                        },2000);
                        break;
                    default:
                    	var mensaje_error = '<b>Error al obtener el cliente. Intente nuevamente </b>';
                    	AbrirAlerta(mensaje_error, 'auto', 'auto');
                    	break;
                }
            },
            error: function (error){
                //alert(error);
            }
        });
	}

	function ClienteEliminar(_cliente_id, _nombre){
		var mensaje = '<div>Esta seguro de querer elminar el cliente '+_nombre+'?.</div><hr><input type="button" class="btn btn-danger" onclick="ClienteEliminarConfirmar('+_cliente_id+');" value="Eliminar">';
		AbrirAlerta(mensaje, 'auto','auto');
	}

	function ClienteEliminarConfirmar(_cliente_id){
		$.ajax({
            url: "cliente_eliminar",
            type: 'post',
            data: {
                cliente_id:_cliente_id
            },
            dataType: 'json',
            success: function(respuesta) {
                switch(parseInt(respuesta.res)){
                    case 1:
                        AbrirAlerta('Cliente eliminado exitosamente.', 'auto', 'auto');
                        setInterval(function(){
                        	window.location.reload();
                        },2000);
                        break;
                    default:
                    	var mensaje_error = '<b>Error al obtener el cliente. Intente nuevamente </b>';
                    	AbrirAlerta(mensaje_error, 'auto', 'auto');
                    	break;
                }
            },
            error: function (error){
                //alert(error);
            }
        });
	}
</script>
</body>
</html>