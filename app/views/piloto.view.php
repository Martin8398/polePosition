<?php

class PilotoView
{
    private $user = null;

    public function showPilotos($pilotos)
    {
        $count = count($pilotos);
        require './templates/pilotos.phtml';
    }

    public function showPiloto($piloto, $resultados)
    {
        require './templates/piloto.phtml';
    }

    public function showError($error)
    {

        require 'templates/error.phtml';
    }

    public function ShowFormPiloto()
    {

        require 'templates/pilotoForm.phtml';
    }

    public function UpdatePiloto($piloto)
    {

        require 'templates/formActualizar.phtml';
    }
}
