<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Facturas</title>
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
	  <a class="navbar-brand" href="#">Facturas</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
	    <div class="navbar-nav">
	      <a class="nav-item nav-link" href="index.php/FacturaController/factura_nueva">Factura Nueva <span class="sr-only">(current)</span></a>
	      <a class="nav-item nav-link" href="index.php/FacturaController/productos">Productos</a>
	      <a class="nav-item nav-link" href="index.php/FacturaController/clientes">Clientes</a>
	    </div>
	  </div>
	</nav>
	<h1>Listado de Facturas</h1>
	<div id="body" class="col-12 col-md-11">
		<?php 
			$tabla = '<table id="tbl_facturas" class="table table-stripped">
						<thead>
							<tr>
								<th>No.</th>
								<th>Factura</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Cliente</th>
								<th>Total</th>
								<th>Estado</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>';
			$i = 1;
			foreach ($facturas['facturas'] as $key => $factura) {
				switch ($factura['factura_estado']) {
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
				$tabla .= '<tr>
							<td>'.$i.'</td>
							<td>'.$factura['factura_numero'].'</td>
							<td>'.$factura['factura_fecha'].'</td>
							<td>'.$factura['factura_hora'].'</td>
							<td>'.$factura['cliente_nombres'].' '.$factura['cliente_apellidos'].'</td>
							<td>'.number_format($factura['factura_total'], 2).'</td>
							<td>'.$estado.'</td>
							<td><a class="btn btn-primary" href="index.php/FacturaController/factura?id='.$factura['factura_id'].'");">Ver</a></td>
						</tr>';
				$i++;
			}
			$tabla .= '</tbody></table>';
			echo $tabla;
		?>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
<script src="includes/js/jquery-1.10.2.min.js"></script>
<script src="includes/js/funciones.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="http://elhijodelblues.net/includes/js/fancybox/source/jquery.fancybox.js"></script>
<script type="text/javascript">
	$( document ).ready(function() {
	    $('#tbl_facturas').DataTable();
	});
</script>
</body>
</html>