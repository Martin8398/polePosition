<?php

include_once './app/models/resultado.model.php';
include_once './app/models/piloto.model.php';
include_once './app/views/resultado.view.php';

class ResultadoController
{
    private $model;
    private $view;
    private $pilotoModel;

    public function __construct()
    {
        $this->model = new ResultadoModel();
        $this->pilotoModel = new PilotoModel();
        $this->view = new ResultadoView();
        //es necesario crear uno nuevo o puedo traerlo desde otra view que lo use?

    }


    public function addResultado()
    {
        if (!isset($_SESSION['usuario_id'])) {
            $this->view->showError("Debes estar logueado para agregar resultados.");
            return;
        }

        if (!isset($_POST['carrera_id'], $_POST['piloto_id'], $_POST['posicion'], $_POST['tiempo'])) {
            $this->view->showError("Faltan datos del formulario.");
            return;
        }

        $carrera_id = filter_var($_POST['carrera_id'], FILTER_VALIDATE_INT);
        $piloto_id = filter_var($_POST['piloto_id'], FILTER_VALIDATE_INT);
        $posicion = filter_var($_POST['posicion'], FILTER_VALIDATE_INT);
        $tiempo = trim($_POST['tiempo']);

        // echo '<pre>';
        // var_dump($_POST);
        // echo '</pre>';
        // die();

        if (!$carrera_id || !$piloto_id || !$posicion || empty($tiempo)) {
            $this->view->showError("Datos inválidos.");
            return;
        }

        $this->model->addResultado($carrera_id, $piloto_id, $posicion, $tiempo);

        header("Location: " . BASE_URL . "carrera/$carrera_id");
        exit;
    }

    public function updateResultado()
    {
        $resultado_id = $_POST['resultado_id'];
        $piloto_id = $_POST['piloto_id'];
        $posicion = $_POST['posicion'];
        $tiempo = $_POST['tiempo'];
        $carrera_id = $_POST['carrera_id'];

        $this->model->updateResultado($resultado_id, $piloto_id, $posicion, $tiempo);

        header("Location: " . BASE_URL . "carrera/$carrera_id");
        exit;
    }

    public function deleteResultado()
    {
        $resultado_id = $_POST['resultado_id'];
        $carrera_id = $_POST['carrera_id'];
        $this->model->deleteResultado($resultado_id);
        header("Location: " . BASE_URL . "carrera/$carrera_id");
        exit;
    }
}
