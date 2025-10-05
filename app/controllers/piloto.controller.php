<?php

include_once 'app/models/piloto.model.php';
include_once 'app/models/resultado.model.php';
include_once 'app/views/piloto.view.php';

class PilotoController
{
    private $model;
    private $view;
    private $resultadoModel;

    function __construct()
    {
        $this->model = new PilotoModel();
        $this->view = new PilotoView();
        $this->resultadoModel = new ResultadoModel();
    }

    public function showPilotos()
    {
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';

        if ($search) {
            $pilotos = $this->model->searchPilotos($search);
        } else {
            $pilotos = $this->model->getPilotos();
        }

        require 'templates/pilotos.phtml';
    }

    function showPiloto($id)
    {
        // var_dump($id);die();
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            echo "ID inválido.";
            return;
        }
        $piloto = $this->model->getPiloto($id);
        if (!$piloto) {
            echo "Piloto no encontrado.";
            return;
        }

        $resultados = $this->resultadoModel->getResultadosPiloto($id) ?? [];
        // var_dump($resultados);die();
        $this->view->showPiloto($piloto, $resultados);
    }
}
