<?php

class KorisnikController extends AutorizacijaController
{

    private $phtmlDir = 'privatno' . 
        DIRECTORY_SEPARATOR . 'korisnici' .
        DIRECTORY_SEPARATOR;

    private $korisnik=null;
    private $poruka='';

    public function index()
    {
        $nf = new NumberFormatter("hr-HR", \NumberFormatter::DECIMAL);
        $korisnici = Korisnik::read();

        $this->view->render($this->phtmlDir . 'read',[
            'korisnici' => $korisnici
        ]);
    }

    public function promjena()
    {

     }


    public function brisanje($sifra)
    {

  
    }

    private function pripremiSmjer()
    {
        $this->korisnik=new stdClass();
        $this->korisnik->ime='';
        $this->korisnik->prezime='';
        $this->korisnik->email='';
        $this->korisnik->broj_mobitela='';
        $this->korisnik->ime_ulice='';
        $this->korisnik->grad='';
        $this->korisnik->država='';
        $this->korisnik->broj_vozačke='';
    }

}