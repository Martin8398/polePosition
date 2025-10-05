<?php

include_once './app/models/carrera.model.php';
include_once './app/models/resultado.model.php';
include_once './app/views/carrera.view.php';

class CarreraController
{
    private $model;
    private $resultadoModel;
    private $view;

    function __construct()
    {
        $this->model = new CarreraModel();
        $this->resultadoModel = new ResultadoModel();
        $this->view = new CarreraView();
    }

    function showCarreras()
    {
        $carreras = $this->model->getCarreras();

        // Agregar la propiedad 'ganador' a cada carrera

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
        $this->view->showCarrera($carrera, $resultados);
    }


    public function addCarrera()
    {

        if (isset($_POST['fecha'], $_POST['vueltas'])) {
            $fecha   = filter_var($_POST['fecha'], FILTER_SANITIZE_STRING);
            $vueltas = filter_var($_POST['vueltas'], FILTER_VALIDATE_INT);

            if ($fecha && $vueltas > 0) {
                $carrera_id = $this->model->addCarrera($fecha, $vueltas);

                // si además mandás pilotos y posiciones en el form
                if (isset($_POST['pilotos'], $_POST['posiciones'])) {
                    foreach ($_POST['pilotos'] as $i => $piloto_id) {
                        $posicion = $_POST['posiciones'][$i];
                        $this->model->addResultado($carrera_id, $piloto_id, $posicion);
                    }
                }
            } else {
                echo "Datos inválidos.";
                return;
            }
        }
        header("Location: " . BASE_URL);
    }
}
