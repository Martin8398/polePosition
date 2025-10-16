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

    public function showPilotos($vista = 'pilotos.phtml')
    {
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';

        if ($search) {
            $pilotos = $this->model->searchPilotos($search);
        } else {
            $pilotos = $this->model->getPilotos();
        }

        // Validar que el template exista
        $templatePath = 'templates/' . $vista;
        if (!file_exists($templatePath)) {
            die("Template no encontrado: $vista");
        }

        require $templatePath;
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

    function showFormPiloto()
    {
        $this->view->showFormPiloto();
    }

    function createPiloto()
    {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $foto = null;

        if (empty($nombre) || empty($apellido)) {
            die("Faltan datos obligatorios.");
        }

        if (!empty($_FILES['foto']['name'])) {
            $targetDir = "assets/pilotos/";
            $fileName = uniqid() . '_' . basename($_FILES['foto']['name']);
            $targetFile = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileType, $allowedTypes)) {
                die("Tipo de archivo no permitido. Solo JPG, JPEG, PNG o GIF.");
            }


            if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                die("La imagen es demasiado grande. Máximo 2MB.");
            }

            $check = getimagesize($_FILES['foto']['tmp_name']);
            if ($check === false) {
                die("El archivo no es una imagen válida.");
            }

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
                $foto = $fileName; // Guardamos solo el nombre del archivo en la BD
            } else {
                die("Error al subir la imagen.");
            }
        }

        $id = $this->model->addPiloto($nombre, $apellido, $foto);

        header("Location: " . BASE_URL . "piloto/" . $id);
        exit;
    }
}
