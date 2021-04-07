<?php

class Usuario {
    private $idusuario;
    private $usuario;
    private $clave;
    private $nombre;
    private $apellido;
    private $correo;

    public function __construct(){

    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function encriptarClave($clave){
        $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);
        return $claveEncriptada;
    }

    public function verificarClave($claveIngresada, $claveEnBBDD){
        return password_verify($claveIngresada, $claveEnBBDD);
    }

    public function cargarFormulario($request){
        $this->idusuario = isset($request["id"])? $request["id"] : "";
        $this->usuario = isset($request["txtUsuario"])? $request["txtUsuario"] : "";
        $this->clave = isset($request["txtClave"])? $request["txtClave"]: "";
        $this->nombre = isset($request["txtNombre"])? $request["txtNombre"]: "";
        $this->apellido = isset($request["txtApellido"])? $request["txtApellido"] : "";
        $this->correo = isset($request["txtCorreo"])? $request["txtCorreo"] : "";
    }

    public function insertar(){
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        //Arma la query
        $sql = "INSERT INTO usuarios (
                    usuario, 
                    clave, 
                    nombre, 
                    apellido, 
                    correo
                ) VALUES (
                    '" . $this->usuario ."', 
                    '" . $this->clave ."', 
                    '" . $this->nombre ."', 
                    '" . $this->apellido ."', 
                    '" . $this->correo ."'
                );";
               // print_r($sql);exit;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        //Obtiene el id generado por la inserción
        $this->idusuario = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }

    public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE usuarios SET
                usuario = '".$this->usuario."',
                clave = '".$this->clave."',
                nombre = '".$this->nombre."',
                apellido = '".$this->apellido."',
                correo =  '".$this->correo."'
                WHERE idusuario = " . $this->idusuario;
          
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM usuarios WHERE idusuario = " . $this->idusuario;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idusuario, 
                        usuario, 
                        clave, 
                        nombre, 
                        apellido, 
                        correo 
                FROM usuarios 
                WHERE idusuario = $this->idusuario";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if($fila = $resultado->fetch_assoc()){
            $this->idusuario = $fila["idusuario"];
            $this->usuario = $fila["usuario"];
            $this->clave = $fila["clave"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
        }  
        $mysqli->close();

    }

    public function obtenerPorUsuario($usuario){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idusuario, 
                        usuario, 
                        clave,
                        nombre,
                        apellido, 
                        correo
                FROM usuarios 
                WHERE usuario = '$usuario'";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if($fila = $resultado->fetch_assoc()){
            $this->idusuario = $fila["idusuario"];
            $this->usuario = $fila["usuario"];
            $this->clave = $fila["clave"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
        }
        $mysqli->close();
    }

    public function obtenerTodos(){
        $aUsuarios = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
    A.idusuario,
    A.usuario,
    A.clave,
    A.nombre,
    A.apellido,
    A.correo,
    (SELECT GROUP_CONCAT('(', C.nombre, ') ', B.domicilio, ', ', D.nombre, ', ', E.nombre SEPARATOR '<br>') 
        FROM domicilios B 
        INNER JOIN tipo_domicilios C ON C.idtipo = B.fk_tipo
        INNER JOIN localidades D ON D.idlocalidad = B.fk_idlocalidad
        INNER JOIN provincias E ON E.idprovincia = D.fk_idprovincia
        WHERE B.fk_idcliente = A.idcliente
        ) as domicilio
	FROM
	    usuarios A
	ORDER BY idusuario DESC";

        $resultado = $mysqli->query($sql);

        if($resultado){
            while ($fila = $resultado->fetch_assoc()) {
                $obj = new Usuario();
                $obj->idusuario = $fila["idusuario"];
                $obj->usuario = $fila["usuario"];
                $obj->clave = $fila["clave"];
                $obj->nombre = $fila["nombre"];
                $obj->apellido = $fila["apellido"];
                $obj->correo = $fila["correo"];
                $aUsuarios[] = $obj;

            }
            return $aUsuarios;
        }
    }

}


?>