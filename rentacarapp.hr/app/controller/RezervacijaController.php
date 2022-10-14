<?php

class RezervacijaController extends AutorizacijaController
{

    private $phtmlDir = 'privatno' . 
        DIRECTORY_SEPARATOR . 'rezervacije' .
        DIRECTORY_SEPARATOR;

    private $entitet=null;
    private $poruka='';

    public function index()
    {
        $nf = new NumberFormatter("hr-HR", \NumberFormatter::DECIMAL);
        $this->view->render($this->phtmlDir . 'index',[
            'entiteti'=>Rezervacija::read()
        ]);
    }

    public function nova()
    {
        $novaRezervacija = Rezervacija::create([
            'vozilo'=>'',
            'cijena'=>'',
            'lokacija'=>'',
            'datum_preuzimanja'=>'',
            'datum_povratka'=>'',
            'korisnik'=>'',
            'osiguranje'=>''
        ]);
        header('location: ' . App::config('url') 
                . 'rezervacija/promjena/' . $novaRezervacija);
    }
    
    public function promjena($sifra)
    {
        if(!isset($_POST['vozilo'])){

            $e = Rezervacija::readOne($sifra);
            if($e==null){
                header('location: ' . App::config('url') . 'rezervacija');
            }

            $this->view->render($this->phtmlDir . 'detalji',[
                'e' => $e,
                'poruka' => 'Unesite podatke'
            ]);
            return;
        }

        $this->entitet = (object) $_POST;
        $this->entitet->osiguranje = isset($_POST['osiguranje']);
        $this->entitet->sifra=$sifra;
    
        if($this->kontrola()){
            Rezervacija::update((array)$this->entitet);
            header('location: ' . App::config('url') . 'rezervacija');
            return;
        }

        $this->view->render($this->phtmlDir . 'detalji',[
            'e'=>$this->entitet,
            'poruka'=>$this->poruka
        ]);

    }

    

    private function kontrola()
    {
        return $this->kontrolirajVozilo() && $this->kontrolirajLokaciju() && $this->kontrolirajOsiguranje();
    }
        
    private function kontrolirajVozilo()
    {
        return true;
    }

    private function kontrolirajLokaciju()
    {

        return true;
    }

    private function kontrolirajOsiguranje()
    {

        return true;
    }

    public function brisanje($sifra)
    {
        Rezervacija::delete($sifra);
        header('location: ' . App::config('url') . 'rezervacija');
    }

}