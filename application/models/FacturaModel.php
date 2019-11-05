<?php

class FacturaModel extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('FacturaModel');
    }

    /*Funcion para sanitizar variables y evitar SQL Injection*/
    public function _Sanitizar( $var, $type = false ){
        $sanitize = new stdClass();
        if ( $type ){
            foreach ($var as $key => $value) {
                $sanitize->$key = $this->_clearString( $value );
            }
            return $sanitize;
        } else {
            return $this->_clearString( $var );
        }
    }

    /*Funcion para eliminar diagonales y caracteres especiales como las comillas y convetirlas a su equivalente en html*/
    private function _clearString( $string ){
        $string = strip_tags($string);
        $string = htmlspecialchars($string);
        $string = addslashes($string);
        return $string;
    }

    /*Funcion que obtiene todos los clientes activos*/
    public function ClientesObtener(){
        $query = 'SELECT * 
                    FROM cliente 
                    WHERE estado = 1';
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $eventos = $result->result_array();
            $respuesta['res'] = 1;
            $respuesta['clientes'] = $eventos;
        }else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Funcion que obtiene todos los productos activos*/
    public function ProductosObtener(){
        $query = 'SELECT * 
                    FROM producto 
                    WHERE estado = 1';
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $eventos = $result->result_array();
            $respuesta['res'] = 1;
            $respuesta['productos'] = $eventos;
        }else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Funcion que genera una nueva factura e inserta todos los detalles de factura*/
    public function FacturaAgregar(){
        print_r('<pre>');
        var_dump($_POST);
        print_r('</pre>');
        die;
        $hora = date('Y-m-d');
        $fecha = $_POST['fecha'];
        $cliente = $_POST['cliente'];
        $query = 'INSERT INTO factura (fecha, hora, numero, cliente_id) VALUES ("'.$fecha.'","'.$hora.'","'.$numero.'","'.$cliente.'",)';
        $result = $this->db->query($query);
        $factura_id = $this->db->insert_id();
        if ($result->num_rows() > 0) {
            $producto_id = $_POST['producto_id'];
            $query = 'INSERT INTO factura_detalle (cantidad, estado, factura_id, product_id) VALUES (1,1,"'.$factura_id.'","'.$producto_id.'",)';
            $result = $this->db->query($query);
            if ($result->num_rows() > 0) {
                $respuesta['res'] = 1;
            }
        }else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    public function get_last_ten_entries()
    {
            $query = $this->db->get('entries', 10);
            return $query->result();
    }

    public function insert_entry()
    {
            $this->title    = $_POST['title']; // please read the below note
            $this->content  = $_POST['content'];
            $this->date     = time();

            $this->db->insert('entries', $this);
    }

    public function update_entry()
    {
            $this->title    = $_POST['title'];
            $this->content  = $_POST['content'];
            $this->date     = time();

            $this->db->update('entries', $this, array('id' => $_POST['id']));
    }
}