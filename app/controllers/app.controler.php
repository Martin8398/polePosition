<?php

include_once './app/views/app.view.php';

class AppController
{
    private $view;

    function __construct()
    {
        $this->view = new AppView();
    }

    function showHome()
    {
        $this->view->showHome();
    }
}
