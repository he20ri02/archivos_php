<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../../config/database.php';
require_once '../models/usersModel.php';
require_once '../models/routesModel.php';

class userController {

    private $db;
    private $user;
    private $data;
    private $req;

    public function __construct()
    {
        $database = new ConexionBD();
        $this->db = $database->BD();
        $this->user = new Users($this->db);
    }

    public function handleRequest(){
        $this->data = json_decode(file_get_contents("php://input"));
        $request = ($this->data !== null) ? $this->data->request : null;
        $this->req = ($request !== null && array_key_exists($request, ROUTER::ROUTES())) ? ROUTER::ROUTES()[$request] : 'defaultAction';

        // Manejar la solicitud
        switch ($this->req) {
            case 'getUsers':
                $this->getUsers();
                break;
            default:
                // Acción predeterminada
                break;
        }
    }

    private function getUsers(){
        $result = $this->user->getUsers();

        if ($result) {
            $result_array = array('result' => $result);
            http_response_code(200);
        } else {
            $result_array = array('result' => null);
            http_response_code(500);
        }
        echo json_encode($result_array);
    }
    
}

// Ejemplo de uso:
$userController = new userController();
$userController->handleRequest();
?>
