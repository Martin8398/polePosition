<?php

class ResultadoView
{
    private $user = null;

    public function showError($error)
    {
        require 'templates/error.phtml';
    }
}
