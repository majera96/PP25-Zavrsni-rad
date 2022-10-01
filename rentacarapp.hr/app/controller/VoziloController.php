<?php

class VoziloController extends AutorizacijaController
{

    private $phtmlDir = 'privatno' . 
        DIRECTORY_SEPARATOR . 'vozila' .
        DIRECTORY_SEPARATOR;

    private $entitet=null;
    private $poruka='';

    public function index()
    {
        $this->view->render($this->phtmlDir . 'index',[
            'entiteti'=>Vozilo::read()
        ]);
    }

    public function novi()
    {
        $novaVozila = Vozilo::create([
            'proizvodac'=>'',
            'model'=>'',
            'godiste'=>'',
            'gorivo'=>'',
            'mjenjac'=>'',
            'opis'=>''
        ]);
        header('location: ' . App::config('url') 
                . 'vozilo/promjena/' . $novaVozila);
    }
    
    public function promjena($sifra)
    {
        if(!isset($_POST['proizvodac'])){

            $e = Vozilo::readOne($sifra);
            if($e==null){
                header('location: ' . App::config('url') . 'vozilo');
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
            Vozilo::update((array)$this->entitet);
            header('location: ' . App::config('url') . 'vozilo');
            return;
        }

        $this->view->render($this->phtmlDir . 'detalji',[
            'e'=>$this->entitet,
            'poruka'=>$this->poruka
        ]);

    }

    private function kontrola()
    {
        return $this->kontrolirajProizvodac() && $this->kontrolirajGodiste() && $this->kontrolirajModel();
    }
        
    private function kontrolirajProizvodac()
    {
        return true;
    }

    private function kontrolirajModel()
    {

        return true;
    }

    private function kontrolirajGodiste()
    {

        return true;
    }

    public function brisanje($sifra)
    {
        Vozilo::delete($sifra);
        header('location: ' . App::config('url') . 'vozilo');
    }

}