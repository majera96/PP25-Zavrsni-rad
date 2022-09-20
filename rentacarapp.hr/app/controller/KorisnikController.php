<?php

class KorisnikController extends AutorizacijaController
{
    private $phtmlDir = 'privatno' . DIRECTORY_SEPARATOR . 
                'korisnici' . DIRECTORY_SEPARATOR;

    private $korisnik=null;

    public function index()
    {
        $nf = new NumberFormatter("hr-HR", \NumberFormatter::DECIMAL);
        $korisnici = Korisnik::read();

        $this->view->render($this->phtmlDir . 'read',['
        korisnici'=>$korisnici]);
    }

    
}
