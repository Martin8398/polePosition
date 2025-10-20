<?php

include_once './app/models/carrera.model.php';
include_once './app/models/resultado.model.php';
include_once './app/views/carrera.view.php';

class CarreraController
{
    private $model;
    private $resultadoModel;
    private $view;
    private $pilotoModel;


    function __construct()
    {
        $this->model = new CarreraModel();
        $this->resultadoModel = new ResultadoModel();
        $this->pilotoModel = new PilotoModel();
        $this->view = new CarreraView();
    }

    function showCarreras()
    {
        $carreras = $this->model->getCarreras();
        foreach ($carreras as $c) {
            $ganador = $this->model->getGanador($c->carrera_id);
            $c->ganador = $ganador ? $ganador : null;
        }
        $this->view->showCarreras($carreras);
    }

    public function showCarrera($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            echo "ID inválido.";
            return;
        }
        $carrera = $this->model->getCarrera($id);
        if (!$carrera) {
            echo "Carrera no encontrada.";
            return;
        }
        $resultados = $this->resultadoModel->getResultadosCarrera($id) ?? [];
        // var_dump($resultados);die();
        // $this->view->showCarrera($carrera, $resultados);

        $usuarioLogueado = isset($_SESSION['usuario_id']);
        $pilotos = [];
        if ($usuarioLogueado) {
            $pilotos = $this->pilotoModel->getPilotos();
        }
        $this->view->showCarrera($carrera, $resultados, $usuarioLogueado, $pilotos);
    }

    function showFormCarrera()
    {
        $this->view->showFormCarrera();
    }

    public function addCarrera()
    {
        if (!isset($_POST['fecha'], $_POST['vueltas'])) {
            $this->view->showError("Faltan datos del formulario.");
            return;
        }

        $fecha = $_POST['fecha'];
        $vueltas = filter_var($_POST['vueltas'], FILTER_VALIDATE_INT);

        if (empty($fecha) || $vueltas === false || $vueltas <= 0) {
            $this->view->showError("Datos inválidos.");
            return;
        }

        // Guardamos la carrera y obtenemos el ID
        $carrera_id = $this->model->addCarrera($fecha, $vueltas);

        // Redirigimos al formulario de resultados para esta carrera
        header("Location: " . BASE_URL . "carrera/$carrera_id");
        exit;
    }

    public function updateCarrera(){
        if (!isset($_POST['carrera_id'], $_POST['fecha'], $_POST['vueltas'])) {
            $this->view->showError("Faltan datos del formulario.");
            return;
        }

        $carrera_id = filter_var($_POST['carrera_id'], FILTER_VALIDATE_INT);
        $fecha = $_POST['fecha'];
        $vueltas = filter_var($_POST['vueltas'], FILTER_VALIDATE_INT);

        if ($carrera_id === false || empty($fecha) || $vueltas === false || $vueltas <= 0) {
            $this->view->showError("Datos inválidos.");
            return;
        }

        // Actualizamos la carrera
        $this->model->updateCarrera($carrera_id, $fecha, $vueltas);

        // Redirigimos a la lista de carreras
        header("Location: " . BASE_URL . "carreras");
        exit;
    }

    public function deleteCarrera()
    {
        $carrera_id = $_POST['carrera_id'];
        $this->model->deleteCarrera($carrera_id);
        header("Location: " . BASE_URL . "carreras");
        exit;
    }
}
