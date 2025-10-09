<?php

class AppView
{
    private $user = null;

    public function showHome()
    {
        require './templates/index.phtml';
    }
}
