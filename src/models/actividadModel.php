<?php

class Actividad
{

    private $conn;

    public $main;

    public $err_bd;

    public $error_bd;

    public $actividadData = array(
        'id' => 0,
        'id_usuario' => 0,
        'id_departamento' => 0,
        't_actividad' => '',
        'prioridad' => '',
        'descripcion' => '',
        'comentarios' => '',
        'fecha_asignacion' => '',
        'fecha_limite' => '',
        'estado' => '',
        'nom_archivo' => '',
        'ruta_archivo' => '',
        'fecha_subida' => '',
        'archivos' => array()
    );

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertActividad()
    {
        // 1. Insertar la actividad
        $query = "INSERT INTO actividad(id_usuario, id_departamento, t_actividad, prioridad, descripcion,
        comentarios, fecha_asignacion, fecha_limite, estado)
        VALUES (:id_usuario, :id_departamento, :t_actividad, :prioridad, :descripcion,
        :comentarios, CURRENT_TIMESTAMP(), :fecha_limite, :estado)";
        $stmt_actividad = $this->conn->prepare($query);
        $stmt_actividad->bindParam(':id_usuario', $this->actividadData['id_usuario'], PDO::PARAM_INT);
        $stmt_actividad->bindParam(':id_departamento', $this->actividadData['id_departamento'], PDO::PARAM_INT);
        $stmt_actividad->bindParam(':t_actividad', $this->actividadData['t_actividad'], PDO::PARAM_STR);
        $stmt_actividad->bindParam(':prioridad', $this->actividadData['prioridad'], PDO::PARAM_STR);
        $stmt_actividad->bindParam(':descripcion', $this->actividadData['descripcion'], PDO::PARAM_STR);
        $stmt_actividad->bindParam(':comentarios', $this->actividadData['comentarios'], PDO::PARAM_STR);
        $stmt_actividad->bindParam(':fecha_limite', $this->actividadData['fecha_limite'], PDO::PARAM_STR);
        $stmt_actividad->bindParam(':estado', $this->actividadData['estado'], PDO::PARAM_STR);
        $stmt_actividad->execute();

        // 2. Obtener el ID de la actividad recién insertada
        $id_actividad = $this->conn->lastInsertId();

        // 3. Insertar archivos adjuntos
        foreach ($this->actividadData['archivos'] as $archivo) {
            $query_archivos = "INSERT INTO archivos_adjuntos(nom_archivo, ruta_archivo, fecha_subida, id_actividad)
            VALUES (:nom_archivo, :ruta_archivo, CURRENT_TIMESTAMP(), :id_actividad)";

            $stmt_archivos = $this->conn->prepare($query_archivos);

            $stmt_archivos->bindParam(':nom_archivo', $archivo['nom_archivo'], PDO::PARAM_STR);
            $stmt_archivos->bindParam(':ruta_archivo', $archivo['ruta_archivo'], PDO::PARAM_STR);
            $stmt_archivos->bindParam(':id_actividad', $id_actividad, PDO::PARAM_INT);
            $stmt_archivos->execute();
        }
        return ('la inserción de archivos es corrercta');
    }
}
