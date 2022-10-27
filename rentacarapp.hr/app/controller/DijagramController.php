<?php

class DijagramController extends Controller
{
    private $phtmlDir = 'privatno' . 
        DIRECTORY_SEPARATOR . 'dijagram' .
        DIRECTORY_SEPARATOR;
        
    public function index()
    {
        $this->view->render($this->phtmlDir . 'index');
    }
}