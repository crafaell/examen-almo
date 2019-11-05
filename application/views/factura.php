<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Detalle de Factura</title>
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
	      <a class="nav-item nav-link" href="clientes">Clientes</a>
	    </div>
	  </div>
	</nav>
	<h1>Detalle de Factura</h1>
	<hr>
	<a class="btn btn-primary" href="../../">Regresar</a>
	<?php
		if($factura['factura'][0]['factura_estado'] == 1){
			echo '<input type="button" class="btn btn-danger" value="Anular" onclick="FacturaAnular('.$factura['factura'][0]['factura_id'].',\''.$factura['factura'][0]['factura_numero'].'\',\''.$factura['factura'][0]['factura_fecha'].'\');">';
		}
	?>
	
	<div id="body" class="col-12 col-md-11">
		<?php 
			$tabla = '<table id="tbl_facturas" class="table table-stripped">
						<thead>
							<tr>
								<th>No.</th>
								<th>Factura</th>
								<th>Fecha</th>
								<th>Cliente</th>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Precio</th>
								<th>Subtotal</th>
								<th>Estado</th>
							</tr>
						</thead>
						<tbody>';
			$i = 1;
			foreach ($factura['factura'] as $key => $fac) {
				switch ($fac['factura_estado']) {
					case '1':
						$estado = '<div style="color:green;">Vendido</div>';
						break;
					case '2':
						$estado = '<div style="color:red;">Anulada</div>';
						break;
					default:
						# code...
						break;
				}
				$subtotal = floatval($fac['producto_precio'])*intval($fac['factura_detalle_cantidad']);
				$tabla .= '<tr>
							<td>'.$i.'</td>
							<td>'.$fac['factura_numero'].'</td>
							<td>'.$fac['factura_fecha'].'</td>
							<td>'.$fac['cliente_nombres'].' '.$fac['cliente_apellidos'].'</td>
							<td>'.$fac['producto_nombre'].'</td>
							<td>'.$fac['factura_detalle_cantidad'].'</td>
							<td>'.$fac['producto_precio'].'</td>
							<td>'.number_format($subtotal,2).'</td>
							<td>'.$estado.'</td>
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
	    $('#tbl_facturas').DataTable();
	});

	function FacturaAnular(_factura_id, _numero, _fecha){
		var mensaje = '<h4>Confirme la anulación</h4><hr><div>Esta seguro de querer anular la factura numero <b>'+_numero+'</b> de fecha <b>'+_fecha+'</b>?.</div><hr><input type="button" class="btn btn-danger" onclick="FacturaAnularConfirmar('+_factura_id+');" value="Anular">';
		AbrirAlerta(mensaje, 'auto','auto');
	}

	function FacturaAnularConfirmar(_factura_id){
		$.ajax({
            url: "factura_anular",
            type: 'post',
            data: {
                factura_id:_factura_id
            },
            dataType: 'json',
            success: function(respuesta) {
                switch(parseInt(respuesta.res)){
                    case 1:
                        AbrirAlerta('Factura anulada exitosamente.', 'auto', 'auto');
                        setInterval(function(){
                        	window.location.reload();
                        },2000);
                        break;
                    default:
                    	var mensaje_error = '<b>Error al anular la factura. Intente nuevamente </b>';
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