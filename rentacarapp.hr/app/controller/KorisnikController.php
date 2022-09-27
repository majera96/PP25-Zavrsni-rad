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

        $korisnici = Korisnik::read();

        $this->view->render($this->phtmlDir . 'read',[
            'korisnici' => $korisnici
        ]);
    }

    public function promjena($sifra)
    {
        if(!isset($_POST['naziv'])){

            $korisnik = Korisnik::readOne($sifra);
            if($korisnik==null){
                header('location: ' . App::config('url') . 'korisnik');
            }

            $this->view->render($this->phtmlDir . 'update',[
                'korisnik' => $korisnik,
                'poruka' => 'Promjenite podatke'
            ]);
            return;
        }

        $this->korisnik = (object) $_POST;
        $this->korisnik->sifra=$sifra;

        if($this->kontrolaPromjena()){
            Korisnik::update((array)$this->korisnik);
            header('location: ' . App::config('url') . 'korisnik');
            return;
        }

        $this->view->render($this->phtmlDir . 'update',[
            'korisnik'=>$this->korisnik,
            'poruka'=>$this->poruka
        ]);


    }

    public function brisanje($sifra)
    {

        $korisnik = Korisnik::readOne($sifra);
        if($korisnik==null){
            header('location: ' . App::config('url') . 'korisnik');
        }

        if(!isset($_POST['obrisi'])){
            $this->view->render($this->phtmlDir . 'delete',[
                'korisnik' => $korisnik,
                'brisanje'=>Korisnik::brisanje($sifra),
                'poruka' => 'Detalji korisnika za brisanje'
            ]);
            return;
        }

        Korisnik::delete($sifra);
        header('location: ' . App::config('url') . 'korisnik');
        

    }

    public function novi()
    {
        if(!isset($_POST['naziv'])){
            $this->pripremiSmjer();
            $this->view->render($this->phtmlDir . 'create',[
                'korisnik'=>$this->korisnik,
                'poruka'=>'Popunite sve podatke'
            ]);
            return;
        }
         
        $this->korisnik = (object) $_POST;
    
        if($this->kontrolaNovi()){
            Korisnik::create((array)$this->korisnik);
            header('location: ' . App::config('url') . 'korisnik');
            return;
        }

        $this->view->render($this->phtmlDir . 'create',[
            'korisnik'=>$this->korisnik,
            'poruka'=>$this->poruka
        ]);
        
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
        $this->korisnik->drzava='';
        $this->korisnik->broj_vozacke='';
    }

}


