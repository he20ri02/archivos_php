<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../../config/database.php';
require_once '../models/actividadModel.php';
require_once '../models/routesModel.php';

class ActividadController {

    private $db;
    private $actividad;
    private $data;
    private $req;

    public function __construct()
    {
        $database = new ConexionBD();
        $this->db = $database->BD();
        $this->actividad = new Actividad($this->db);
    }

    public function handleRequest(){
        $this->data = json_decode(file_get_contents("php://input"));
        $request = ($this->data !== null) ? $this->data->request : null;
        $this->req = ($request !== null && array_key_exists($request, ROUTER::ROUTES())) ? ROUTER::ROUTES()[$request] : 'defaultAction';

        // Manejar la solicitud
        switch ($this->req) {
            case 'putActividad':
                $this->putActividad();
                break;
            default:
                // Acción predeterminada o error
                http_response_code(404);
                echo json_encode(array('error' => 'Solicitud no válida'));
                break;
        }
    }

    private function putActividad() {
        // Obtener datos de la solicitud
        $this->actividad->actividadData['id_usuario'] = $_POST['id_usuario'] ?? 0;
        $this->actividad->actividadData['id_departamento'] = $_POST['id_departamento'] ?? 0;
        $this->actividad->actividadData['t_actividad'] = $_POST['t_actividad'] ?? 'NA';
        $this->actividad->actividadData['prioridad'] = $_POST['prioridad'] ?? 'NA';
        $this->actividad->actividadData['descripcion'] = $_POST['descripcion'] ?? 'NA';
        $this->actividad->actividadData['comentarios'] = $_POST['comentarios'] ?? 'NA';
        $this->actividad->actividadData['fecha_limite'] = $_POST['fecha_limite'] ?? 'NA';
        $this->actividad->actividadData['estado'] = $_POST['estado'] ?? 'NA';

        // Subida de archivos adjuntos
            $uploadDirectory = "../assets/adjuntos/";
            $uploadedFiles = $_FILES['files'];
            $result_array = array();

            foreach ($$_FILES['files']['tmp_name'] as $index => $tempFilePath) {
                $originalFileName = $_FILES['files']['name'][$index];
                //obtener la extensión del archivo
                $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                //construir el nombre de archivo completo
                $nombreArchivoCompleto = $originalFileName. '.' . $extension;

                $targetFilePath = $uploadDirectory . $originalFileName;

                if(move_uploaded_file($tempFilePath, $targetFilePath)) {
                    $archivo[$index] = array(
                        'nom_archivo' => $originalFileName,
                        'ruta_archivo' => $targetFilePath,//[]
                        'extension' => $extension
                    );
                    $this -> actividad -> actividadData ['archivos'] = $archivo;
                    $result_array[] = array ('result' => true);
                } else {
                    // Manejar errores de subida de archivos
                    $error = error_get_last()['message'];
                    $result_array[] = array('result' => false, 'error' => $error);
                }
            }

        // Insertar actividad y archivos adjuntos
        $result = $this->actividad->insertActividad($this->actividad-> actividadData);

        if($result){
            http_response_code(200);
            echo json_encode(array('message' => 'Actividad insertada correctamente'));
        } else {
            $result_array[] = array('result' => false, 'erroe_bd' => $this->actividad->error_bd);
            http_response_code(500);
        }
        //Devolver datos como json
        echo json_encode($result_array);
    }
}



// Ejemplo de uso:
$actividadController = new actividadController();
$actividadController->handleRequest();
?>
