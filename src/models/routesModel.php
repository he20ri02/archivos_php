<?php
class ROUTER {
    public static function ROUTES(){
        return array (
            //rutas para usuarios 
            'GET_USERS' => 'getUsers',

            //rutas para actividad
            'PUT_ACTIVIDAD' => 'putActividad'
        );
    }
}
?>