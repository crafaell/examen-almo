<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FacturaController extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct()
    {
    	parent::__construct();
        $this->load->helper('url');
        $this->load->model('FacturaModel');
    }


	public function index()
	{
		$datos['facturas'] = $this->FacturaModel->FacturasObtener();
		$this->load->view('facturas', $datos);
	}

	public function factura()
	{
		$datos['factura'] = $this->FacturaModel->DetalleFacturasObtener();
		$this->load->view('factura', $datos);
	}

	public function productos()
	{
		$datos['productos'] = $this->FacturaModel->ProductosObtenerTodos();
		$this->load->view('productos', $datos);
	}

	public function producto_obtener()
	{
		$producto = $this->FacturaModel->ProductoObtener();
		echo json_encode($producto);
	}

	public function producto_agregar()
	{
		$producto = $this->FacturaModel->ProductoAgregar();
		echo json_encode($producto);
	}

	public function producto_editar()
	{
		$producto = $this->FacturaModel->ProductoEditar();
		echo json_encode($producto);
	}

	public function producto_eliminar()
	{
		$producto = $this->FacturaModel->ProductoEliminar();
		echo json_encode($producto);
	}

	public function clientes()
	{
		$datos['clientes'] = $this->FacturaModel->ClientesObtenerTodos();
		$this->load->view('clientes', $datos);
	}

	public function cliente_obtener()
	{
		$cliente = $this->FacturaModel->ClienteObtener();
		echo json_encode($cliente);
	}

	public function cliente_agregar()
	{
		$cliente = $this->FacturaModel->ClienteAgregar();
		echo json_encode($cliente);
	}

	public function cliente_editar()
	{
		$cliente = $this->FacturaModel->ClienteEditar();
		echo json_encode($cliente);
	}

	public function cliente_eliminar()
	{
		$cliente = $this->FacturaModel->ClienteEliminar();
		echo json_encode($cliente);
	}

	public function factura_nueva()
	{
		$datos['clientes'] = $this->FacturaModel->ClientesObtenerActivos();
		$datos['productos'] = $this->FacturaModel->ProductosObtenerActivos();
		$datos['facturas_totales'] = $this->FacturaModel->FacturasTotales();
		$this->load->view('factura_nueva', $datos);
	}

	public function factura_agregar()
	{
		$factura_agregar = $this->FacturaModel->FacturaAgregar();
		echo json_encode($factura_agregar);
	}

	public function factura_anular()
	{
		$factura = $this->FacturaModel->FacturaAnular();
		echo json_encode($factura);
	}
}
