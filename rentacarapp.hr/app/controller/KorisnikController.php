<?php

class KorisnikController extends AutorizacijaController
{

    private $phtmlDir = 'privatno' . 
        DIRECTORY_SEPARATOR . 'korisnici' .
        DIRECTORY_SEPARATOR;

    private $entitet=null;
    private $poruka='';

    public function index()
    {
        $this->view->render($this->phtmlDir . 'index',[
            'entiteti'=>Korisnik::read()
        ]);
    }

    public function novi()
    {
        $noviKorisnik = Korisnik::create([
            'ime'=>'',
            'prezime'=>'',
            'email'=>'',
            'broj_mobitela'=>'',
            'ime_ulice'=>'',
            'grad'=>'',
            'drzava'=>'',
            'broj_vozacke'=>''
        ]);
        header('location: ' . App::config('url') 
                . 'korisnik/promjena/' . $noviKorisnik);
    }
    
    public function promjena($sifra)
    {
        if(!isset($_POST['ime'])){

            $e = Korisnik::readOne($sifra);
            if($e==null){
                header('location: ' . App::config('url') . 'korisnik');
            }

            $this->view->render($this->phtmlDir . 'detalji',[
                'e' => $e,
                'poruka' => 'Unesite podatke'
            ]);
            return;
        }

        $this->entitet = (object) $_POST;
        $this->entitet->sifra=$sifra;
    
        if($this->kontrola()){
            Korisnik::update((array)$this->entitet);
            header('location: ' . App::config('url') . 'korisnik');
            return;
        }

        $this->view->render($this->phtmlDir . 'detalji',[
            'e'=>$this->entitet,
            'poruka'=>$this->poruka
        ]);

    }

    private function kontrola()
    {
        return $this->kontrolirajIme() && $this->kontrolirajPrezime() && $this->kontrolirajDrzava();
    }
        
    private function kontrolirajIme()
    {
        return true;
    }

    private function kontrolirajPrezime()
    {

        return true;
    }

    private function kontrolirajDrzava()
    {

        return true;
    }

    public function brisanje($sifra)
    {
        Korisnik::delete($sifra);
        header('location: ' . App::config('url') . 'korisnik');
    }

}


