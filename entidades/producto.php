<?php

class Producto {
    private $idproducto;
    private $nombre;
    private $cantidad;
    private $precio;
    private $descripcion;
    private $imagen;
    private $fk_idtipoproducto;

    public function __construct(){

    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarFormulario($request){
        $this->idproducto = isset($request["id"])? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"])? $request["txtNombre"] : "";
        $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"]: "";
        $this->precio = isset($request["txtPrecio"])? $request["txtPrecio"]: "";
        $this->descripcion = isset($request["txtDescripcion"])? $request["txtDescripcion"] : "";
        $this->imagen = "";
        $this->fk_idtipoproducto = isset($request["lstTipoProducto"])? $request["lstTipoProducto"] : "";
    }

    public function insertar(){
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        //Arma la query
        $sql = "INSERT INTO productos (
                    nombre, 
                    cantidad, 
                    precio, 
                    descripcion, 
                    imagen,
                    fk_idtipoproducto
                ) VALUES (
                    '" . $this->nombre ."', 
                    " . $this->cantidad .", 
                    " . $this->precio .", 
                    '" . $this->descripcion ."', 
                    '" . $this->imagen ."',
                    '" . $this->fk_idtipoproducto ."'
                );";
               // print_r($sql);exit;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        //Obtiene el id generado por la inserción
        $this->idproducto = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }

    public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE productos SET
                nombre = '".$this->nombre."',
                cantidad = ".$this->cantidad.",
                precio = ".$this->precio.",
                descripcion = '".$this->descripcion."',
                imagen =  '".$this->imagen."',
                fk_idtipoproducto =  '".$this->fk_idtipoproducto."'
                WHERE idproducto = " . $this->idproducto;
          
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM productos WHERE idproducto = " . $this->idproducto;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idproducto, 
                        nombre, 
                        cantidad, 
                        precio, 
                        descripcion, 
                        imagen,
                        fk_idtipoproducto
                FROM productos 
                WHERE idproducto = $this->idproducto";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if($fila = $resultado->fetch_assoc()){
            $this->idproducto = $fila["idproducto"];
            $this->nombre = $fila["nombre"];
            $this->cantidad = $fila["cantidad"];
            $this->precio = $fila["precio"];
            $this->descripcion = $fila["descripcion"];
            $this->imagen = $fila["imagen"];
            $this->fk_idtipoproducto = $fila["fk_idtipoproducto"];
        }  
        $mysqli->close();

    }

  public function obtenerTodos(){
        $aProductos = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
    idproducto,
    nombre,
    cantidad,
    precio,
    descripcion,
    imagen,
    fk_idtipoproducto
	FROM
	    productos
	ORDER BY idproducto DESC";

        $resultado = $mysqli->query($sql);

        if($resultado){
            while ($fila = $resultado->fetch_assoc()) {
                $obj = new Producto();
                $obj->idproducto = $fila["idproducto"];
                $obj->nombre = $fila["nombre"];
                $obj->cantidad = $fila["cantidad"];
                $obj->precio = $fila["precio"];
                $obj->descripcion = $fila["descripcion"];
                $obj->imagen = $fila["imagen"];
                $obj->fk_idtipoproducto = $fila["fk_idtipoproducto"];
                $aProductos[] = $obj;

            }
            return $aProductos;
        }
    }

}


?>