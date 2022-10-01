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
        return true;

    }

    /*
    private function kontrolaIme()
    {
        if(strlen($this->entitet->ime)===0){
            $this->poruka = 'Ime obavezno';
            return false;
        }
        return true;
    }
    private function kontrolaPrezime()
    {
        if(strlen($this->entitet->prezime)===0){
            $this->poruka = 'Prezime obavezno';
            return false;
        }
        return true;
    }
*/

    public function brisanje($sifra)
    {
        Lokacija::delete($sifra);
        header('location: ' . App::config('url') . 'lokacija');
    }

}