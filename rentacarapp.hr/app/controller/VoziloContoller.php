<?php

class VoziloController extends AutorizacijaController
{

    private $phtmlDir = 'privatno' . 
        DIRECTORY_SEPARATOR . 'vozila' .
        DIRECTORY_SEPARATOR;

    private $vozilo=null;
    private $poruka='';

    public function index()
    {
        $this->view->render($this->phtmlDir . 'read',[
            'vozilo' => $vozilo
        ]);
    }

    public function promjena($sifra)
    {
        if(!isset($_POST['proizvodac'])){

            $vozilo = Vozilo::readOne($sifra);
            if($vozilo==null){
                header('location: ' . App::config('url') . 'vozilo');
            }

            $this->view->render($this->phtmlDir . 'update',[
                'vozilo' => $vozilo,
                'poruka' => 'Promjenite podatke'
            ]);
            return;
        }

        $this->vozilo = (object) $_POST;
        $this->vozilo->sifra=$sifra;

        if($this->kontrolaPromjena()){
            Vozilo::update((array)$this->vozilo);
            return;
        }

        $this->view->render($this->phtmlDir . 'update',[
            'vozilo'=>$this->vozilo,
            'poruka'=>$this->poruka
        ]);


    }

    public function brisanje($sifra)
    {

        $vozilo = Vozilo::readOne($sifra);
        if($vozilo==null){
            header('location: ' . App::config('url') . 'vozilo');
        }

        if(!isset($_POST['obrisi'])){
            $this->view->render($this->phtmlDir . 'delete',[
                'vozilo' => $vozilo,
                'brisanje'=>Vozilo::brisanje($sifra),
                'poruka' => 'Detalji vozila za brisanje'
            ]);
            return;
        }

        Vozilo::delete($sifra);
        header('location: ' . App::config('url') . 'vozilo');
        

    }

    public function novi()
    {
        if(!isset($_POST['proizvodac'])){
            $this->pripremiVozilo();
            $this->view->render($this->phtmlDir . 'create',[
                'vozilo'=>$this->vozilo,
                'poruka'=>'Popunite sve podatke'
            ]);
            return;
        }
         
        $this->vozilo = (object) $_POST;
    
        if($this->kontrolaNovi()){
            Vozilo::create((array)$this->vozilo);
            header('location: ' . App::config('url') . 'vozilo');
            return;
        }

        $this->view->render($this->phtmlDir . 'create',[
            'vozilo'=>$this->vozilo,
            'poruka'=>$this->poruka
        ]);
        
    }

    private function kontrolaNovi()
    {
        return $this->kontrolirajProizvodac() && $this->kontrolirajGodiste() && kontrolirajModel();
    }

    private function kontrolaPromjena()
    {
        return $this->kontrolaNovi();
    }

    private function kontrolaNaziv()
    {
        
    private function kontrolirajProizvodac()
    {
        $this->vozilo->proizvodac = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->vozilo->proizvodac))));

        if ($this->vozilo->proizvodac == '') {
            $this->poruka = 'Proizvođač vozila je obavezan';
            return false;
        }
    }

    private function kontrolirajModel()
    {
        $this->vozilo->model = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->vozilo->model))));

        if ($this->vozilo->model == '') {
            $this->poruka = 'Model vozila je obavezan';
            return false;
        }

        return true;
    }

    private function kontrolirajGodiste()
    {
        $this->vozilo->godiste = trim(str_replace('&nbsp;', '', $this->vozilo->godiste));

        if ($this->vozilo->godiste == '') {
            $this->poruka = 'Godište vozila je obavezno';
            return false;
        }

        return true;
    }

    private function pripremiVozilo()
    {
        $this->vozilo = new stdClass();
        $this->vozilo->proizvodac = '';
        $this->vozilo->model = '';
        $this->vozilo->godiste = '';
        $this->vozilo->gorivo = '';
        $this->vozilo->mjenjac = '';
        $this->vozilo->opis = '';
    }
}