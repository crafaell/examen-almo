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
	public function index()
	{
		$this->load->view('facturas');
	}

	public function factura_nueva()
	{
		$this->load->model('FacturaModel');
		$datos['clientes'] = $this->FacturaModel->ClientesObtener();
		$datos['productos'] = $this->FacturaModel->ProductosObtener();
		$this->load->view('factura_nueva', $datos);
	}

	public function factura_agregar()
	{
		$this->load->model('FacturaModel');
		$factura_agregar = $this->FacturaModel->FacturaAgregar();
		if($factura_agregar['res'] == 1){
			return redirect()->to('facturas');
		}
		else{
			return redirect()->to('factura_nueva?e=1');
		}
	}
}
