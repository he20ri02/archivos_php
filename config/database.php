<?php

//variables que guardara la conexion a la bd

class ConexionBD {
    private $conn_BD;

    //metodo que crea la conexion y devuelve la variable para usarla en otrs clases

    public function BD() {
        $this-> conn_BD = null;

        $options = array (
            //fechas en español
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET lc_time_names = 'es_ES'",
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO ::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        );

        try {
            //conexion a la base de datos
            $this-> conn_BD = new PDO("mysql:host=localhost:3306;dbname=controlinformacion","root","",$options);
            $this-> conn_BD -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            $this-> conn_BD -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo 'Error al conectar a la BD: '.$e-> getMessage();
        }
        return $this->conn_BD;
    }
}


?>

