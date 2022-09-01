<?php

class KorisnikController extends Controller
{

    private $putanja='privatno' . DIRECTORY_SEPARATOR . 'korisnik' . DIRECTORY_SEPARATOR;

    public function index()
    {
        //echo 'Hello from Korisnik';
        $this->view->render($this->putanja . 'index',[
            'ime'=>'Antonio',
            'prezime'=>'Majer',
            'rezervacije'=>[987,562,142,111]
        ]);
    } 
}