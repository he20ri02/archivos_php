<?php

class Users{

    private $conn;

    public $main;

    public $err_bd;

    public $userData = array(
        'id' => 0,
        'nombre' => '',
        'rol' =>'',
        'contrasena' => '',
        'email' =>''
    );

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function getUsers()
    {
        $query = "SELECT * FROM usuario"; // Corregir error de sintaxis, falta un punto y coma al final

        $stmt = $this->conn->prepare($query);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener resultados
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

}



?>
