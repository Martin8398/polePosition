<?php

require_once 'app/models/user.model.php';
require_once 'app/views/auth.view.php';

class AuthController
{
    private $model;
    private $view;
    

    function __construct()
    {
        $this->model = new UserModel();
        $this->view = new AuthView();
    }

    function showLogin()
    {
        $this->view->showLogin();
    }

    function login()
    {
        //script para hashear contraseñas si necesitas crear un user nuevo

        //  $password = 'admin'; 
        // $hash = password_hash($password, PASSWORD_DEFAULT);
        // echo $hash;die();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['username']) || empty($_POST['username'])) {
                return $this->view->showLogin("El usuario es obligatorio");
            }

            if (!isset($_POST['pass']) || empty($_POST['pass'])) {
                return $this->view->showLogin('Falta completar la contraseña');
            }

            $usuario = htmlspecialchars($_POST['username']);
            $contraseña = htmlspecialchars($_POST['pass']);

            // Verificar que el usuario está en la base de datos

            $userFromDB = $this->model->getUser($usuario);


            if ($userFromDB && password_verify($contraseña, $userFromDB->pass)) {

                session_regenerate_id(true); // Regenera el Id de la sesion para evitar ataques de fijacion de sesion
                $_SESSION['usuario_id'] = $userFromDB->usuario_id;
                $_SESSION['usuario'] = $userFromDB->username;
                $_SESSION['last_activity'] = time();  // Guardar la hora de la última actividad

                header('Location: ' . BASE_URL . './admin'); //Redirigirlo al home
            } else {
                return $this->view->showLogin("Credenciales incorrectas");
            }
        }
        return $this->view->showLogin();
    }

    function logout()
    {
        session_unset(); //Borra las variables de session
        session_destroy(); // Borra la cookie que tiene el navegador
        header('Location: ' . BASE_URL . 'login');
        exit();
    }

    //consultar con agus si es necesario aca o en la view de auth

    public function showAdmin()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "login");
            exit;
        }
        require './templates/admin.phtml';
    }
}
