<?php

class LokacijaController extends AutorizacijaController
{

    private $phtmlDir = 'privatno' . 
        DIRECTORY_SEPARATOR . 'lokacije' .
        DIRECTORY_SEPARATOR;

    private $entitet=null;
    private $poruka='';

    public function index()
    {
        $this->view->render($this->phtmlDir . 'index',[
            'entiteti'=>Lokacija::read()
        ]);
    }

    public function nova()
    {
        $novaLokacija = Lokacija::create([
            'naziv_ulice'=>'',
            'broj_ulice'=>'',
            'postanski_broj'=>'',
            'grad'=>'',
            'broj_mobitela'=>'',
            'email'=>''
        ]);
        header('location: ' . App::config('url') 
                . 'lokacija/promjena/' . $novaLokacija);
    }
    
    public function promjena($sifra)
    {
        if(!isset($_POST['naziv_ulice'])){

            $e = Lokacija::readOne($sifra);
            if($e==null){
                header('location: ' . App::config('url') . 'lokacija');
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
            Lokacija::update((array)$this->entitet);
            header('location: ' . App::config('url') . 'lokacija');
            return;
        }

        $this->view->render($this->phtmlDir . 'detalji',[
            'e'=>$this->entitet,
            'poruka'=>$this->poruka
        ]);
    }

    private function kontrola()
    {
        return $this->kontrolirajNazivulice() && $this->kontrolirajBrojulice() && $this->kontrolirajGrad() && $this->kontrolirajKontakt();
    }
        
    private function kontrolirajNazivulice()
    {
        $this->entitet->naziv_ulice = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->naziv_ulice))));

        if ($this->entitet->naziv_ulice == '') {
            $this->poruka = 'Naziv ulice je obavezan';
            return false;
        }
        return true;
    }

    private function kontrolirajBrojulice()
    {
        $this->entitet->broj_ulice = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->broj_ulice))));

        if ($this->entitet->broj_ulice == '') {
            $this->poruka = 'Broj ulice je obavezan';
            return false;
        }

        return true;
    }

    private function kontrolirajGrad()
    {

        $this->entitet->grad = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->grad))));

        if ($this->entitet->grad == '') {
            $this->poruka = 'Grad je obavezan';
            return false;
        }

        return true;

    }

    private function kontrolirajKontakt()
    {
        $this->entitet->email = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->email))));

        if ($this->entitet->email == '') {
            $this->poruka = 'Kontakt mail je obavezan';
            return false;
        }

        return true;
    }

    public function brisanje($sifra)
    {
        Lokacija::delete($sifra);
        header('location: ' . App::config('url') . 'lokacija');
    }

}