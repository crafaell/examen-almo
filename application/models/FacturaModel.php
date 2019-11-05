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
    public function ClientesObtenerActivos(){
        $query = 'SELECT * 
                    FROM cliente 
                    WHERE estado = 1';
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $respuesta['res'] = 1;
            $respuesta['clientes'] = $result->result_array();
        }else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Funcion que obtiene todos los clientes*/
    public function ClientesObtenerTodos(){
        $query = 'SELECT * 
                    FROM cliente 
                    WHERE estado <> 0';
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $respuesta['res'] = 1;
            $respuesta['clientes'] = $result->result_array();
        }else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Funcion que obtiene todos los productos activos*/
    public function ProductosObtenerActivos(){
        $query = 'SELECT * 
                    FROM producto 
                    WHERE estado = 1
                    AND cantidad > 0';
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $respuesta['res'] = 1;
            $respuesta['productos'] = $result->result_array();
        }else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Funcion que obtiene todos los productos*/
    public function ProductosObtenerTodos(){
        $query = 'SELECT * 
                    FROM producto WHERE estado <> 0';
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $respuesta['res'] = 1;
            $respuesta['productos'] = $result->result_array();
        }else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Funcion que genera una nueva factura e inserta todos los detalles de factura*/
    public function FacturaAgregar(){
        //Se verifica la existencia de los productos antes de crear la factura y su detalle
        $inexistencias = 0;
        $errores = [];
        $productos = json_decode($_POST['productos']);
        $total = 0;
        foreach ($productos as $key => $producto) {
            $total += floatval($producto[3])*intval($producto[2]);
            $query = 'SELECT cantidad FROM producto WHERE id = '.$producto[0];
            $result = $this->db->query($query);
            if ($result->num_rows() > 0) {
                $existencia = $result->result_array();
                if($producto[2] > $existencia[0]['cantidad']){
                    $inexistencias++;
                    array_push($errores, 'El producto '.$producto[1].' tiene existencia '.$existencia[0]['cantidad'].' y se necesitan '.$producto[2].'.');
                }
            }
        }
        if($inexistencias == 0){
            $hora = date('Y-m-d');
            $fecha = $this->_Sanitizar($_POST['fecha']);
            $cliente = $this->_Sanitizar($_POST['cliente']);
            $numero = $this->FacturasTotales();
            $numero = intval($numero['facturas_totales'][0]['total'])+1;
            $query = 'INSERT INTO factura (fecha, hora, numero, total, estado, cliente_id) VALUES ("'.$fecha.'","'.$hora.'","'.$numero.'","'.$total.'",1,"'.$cliente.'")';
            $result = $this->db->query($query);
            if ($result == True) {
                $factura_id = $this->db->insert_id();
                foreach ($productos as $key => $producto) {
                    $producto_id = $producto[0];
                    $cantidad = intval($producto[2]);
                    //Insertar nuevo detalle de factura
                    $query = 'INSERT INTO factura_detalle (cantidad, estado, factura_id, producto_id) VALUES ('.$cantidad.',1,'.$factura_id.','.$producto_id.')';
                    $result = $this->db->query($query);
                    //Obtener la existencia de cada producto
                    $query = 'SELECT cantidad FROM producto WHERE id = '.$producto_id;
                    $disponible = $this->db->query($query);
                    $disponible = $disponible->result_array();
                    $resta = intval($disponible[0]['cantidad']) - $cantidad;
                    //Actualizar el producto con la nueva cantidad
                    $query = 'UPDATE producto SET cantidad = '.$resta.' WHERE id = '.$producto_id;
                    $result = $this->db->query($query);
                    if($result == True) {
                        $respuesta['res'] = 1;
                    }
                }
            }else{
                $respuesta['res'] = 0;
            }
        }
        else{
            $respuesta['res'] = 2;
            $respuesta['errores'] = $errores;
        }
        return $respuesta;
    }

    /*Obtiene todos los detalles de factura con los identificadores de llaves primarias*/
    public function FacturasObtener(){
        $query = 'SELECT F.id as factura_id, 
                        F.numero as factura_numero,
                        F.fecha as factura_fecha, 
                        F.hora as factura_hora,
                        F.total as factura_total,
                        F.estado as factura_estado,
                        SUM(FD.cantidad) as cantidad_comprada,
                        SUM(P.precio) as precios,
                        C.nombres as cliente_nombres, 
                        C.apellidos as cliente_apellidos 
                    FROM factura F, factura_detalle FD, producto P, cliente C
                    WHERE FD.factura_id = F.id
                    AND FD.producto_id = P.id
                    AND F.cliente_id = C.id
                    GROUP BY F.id';
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $respuesta['facturas'] = $result->result_array();
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;   
        }
        return $respuesta;
    }

    /*Obtiene todos los detalles de factura con los identificadores de llaves primarias*/
    public function DetalleFacturasObtener(){
        $query = 'SELECT F.id as factura_id, 
                        F.numero as factura_numero,
                        F.fecha as factura_fecha, 
                        F.estado as factura_estado,
                        FD.cantidad as factura_detalle_cantidad,
                        P.nombre as producto_nombre, 
                        P.precio as producto_precio, 
                        C.nombres as cliente_nombres, 
                        C.apellidos as cliente_apellidos 
                    FROM factura F, factura_detalle FD, producto P, cliente C
                    WHERE FD.factura_id = F.id
                    AND FD.producto_id = P.id
                    AND F.cliente_id = C.id
                    AND F.id = '.$this->_Sanitizar($_GET['id']);
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $respuesta['factura'] = $result->result_array();
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;   
        }
        return $respuesta;
    }

    /*Obtiene el conteo de facturas existentes*/
    public function FacturasTotales(){
        $query = 'SELECT COUNT(*) as total from factura';
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $respuesta['facturas_totales'] = $result->result_array();
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Anula una factura cambiandole de estado*/
    public function FacturaAnular(){
        $query = 'UPDATE factura
                    SET estado = 2
                    WHERE id = '.$this->_Sanitizar($_POST['factura_id']);
        $result = $this->db->query($query);
        if ($result == TRUE) {
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Obtiene toda la informacion de un producto segun su id*/
    public function ProductoObtener(){
        $query = 'SELECT * FROM producto WHERE id = '.$this->_Sanitizar($_POST['producto_id']);
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $respuesta['producto'] = $result->result_array()[0];
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Funcion que recibe todos los datos del producto, los sanitiza y almacen a en la base de datos*/
    public function ProductoAgregar(){
        $query = 'INSERT INTO producto (nombre, precio, cantidad, estado) VALUES(
                    "'.$this->_Sanitizar($_POST['nombre']).'",
                    "'.$this->_Sanitizar($_POST['precio']).'",
                    '.$this->_Sanitizar($_POST['cantidad']).',
                    '.$this->_Sanitizar($_POST['estado']).')';
        $result = $this->db->query($query);
        if ($result == TRUE) {
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Modifica la informacion de un producto segun su id*/
    public function ProductoEditar(){
        $query = 'UPDATE producto
                    SET nombre = "'.$this->_Sanitizar($_POST['nombre']).'",
                    precio = "'.$this->_Sanitizar($_POST['precio']).'",
                    cantidad = '.$this->_Sanitizar($_POST['cantidad']).',
                    estado = '.$this->_Sanitizar($_POST['estado']).'
                    WHERE id = '.$this->_Sanitizar($_POST['producto_id']);
        $result = $this->db->query($query);
        if ($result == TRUE) {
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Elimina de manera logica un producto cambiandole el estado*/
    public function ProductoEliminar(){
        $query = 'UPDATE producto
                    SET estado = 0
                    WHERE id = '.$this->_Sanitizar($_POST['producto_id']);
        $result = $this->db->query($query);
        if ($result == TRUE) {
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Obtiene toda la informacion de un cliente segun su id*/
    public function ClienteObtener(){
        $query = 'SELECT * FROM cliente WHERE id = '.$this->_Sanitizar($_POST['cliente_id']);
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $respuesta['cliente'] = $result->result_array()[0];
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

   /*Funcion que recibe todos los datos del cliente, los sanitiza y almacen a en la base de datos*/
    public function ClienteAgregar(){
        $query = 'INSERT INTO cliente (nombres, apellidos, nit, estado) VALUES(
                    "'.$this->_Sanitizar($_POST['nombres']).'",
                    "'.$this->_Sanitizar($_POST['apellidos']).'",
                    '.$this->_Sanitizar($_POST['nit']).',
                    '.$this->_Sanitizar($_POST['estado']).')';
        $result = $this->db->query($query);
        if ($result == TRUE) {
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Modifica la informacion de un cliente segun su id*/
    public function ClienteEditar(){
        $query = 'UPDATE cliente
                    SET nombres = "'.$this->_Sanitizar($_POST['nombres']).'",
                    apellidos = "'.$this->_Sanitizar($_POST['apellidos']).'",
                    nit = '.$this->_Sanitizar($_POST['nit']).',
                    estado = '.$this->_Sanitizar($_POST['estado']).'
                    WHERE id = '.$this->_Sanitizar($_POST['cliente_id']);
        $result = $this->db->query($query);
        if ($result == TRUE) {
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }

    /*Elimina de manera logica un producto cambiandole el estado*/
    public function ClienteEliminar(){
        $query = 'UPDATE cliente
                    SET estado = 0
                    WHERE id = '.$this->_Sanitizar($_POST['cliente_id']);
        $result = $this->db->query($query);
        if ($result == TRUE) {
            $respuesta['res'] = 1;
        }
        else{
            $respuesta['res'] = 0;
        }
        return $respuesta;
    }
}