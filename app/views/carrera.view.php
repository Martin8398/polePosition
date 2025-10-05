<?php

class CarreraView
{
    private $user = null;

    public function showCarreras($carreras)
    {
        $count = count($carreras);
         require './templates/carreras.phtml';
    }

    public function showCarrera($carrera, $resultados)
    {
        require './templates/carrera.phtml';
    }

    public function showError($error)
    {

        require 'templates/error.phtml';
    }

    public function verFormAgregar($piloto)
    {

        require 'templates/formAgregar.phtml';
    }

    public function verFormActualizar($carrera, $piloto)
    {

        require 'templates/formActualizar.phtml';
    }
}
