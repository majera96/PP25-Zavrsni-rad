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
            'vozilo'=>1,
            'cijena'=>'',
            'lokacija'=>1,
            'datum_preuzimanja'=>'',
            'datum_povratka'=>'',
            'korisnik'=>1,
            'osiguranje'=>''
        ]);
        header('location: ' . App::config('url') 
                . 'rezervacija/promjena/' . $novaRezervacija);
    }
    
    public function promjena($sifra)
    {

        $korisnici = $this->ucitajKorisnike();
        $lokacije = $this->ucitajLokacije();
        $vozila = $this->ucitajVozila();

        if(!isset($_POST['vozilo'])){

            $e = Rezervacija::readOne($sifra);
            if($e==null){
                header('location: ' . App::config('url') . 'rezervacija');
            }

            $this->detalji($e,$korisnici,$lokacije,$vozila,'Unesite podatke');
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

        $this->detalji($this->entitet,$korisnici,$lokacije,$vozila,$this->poruka);
    }

    private function detalji($e,$korisnici,$lokacije,$vozila,$poruka)
    {
        $this->view->render($this->phtmlDir . 'detalji', [
            'e'=>$e,
            'lokacije'=>$lokacije,
            'vozila'=>$vozila,
            'korisnici'=>$korisnici,
            'poruka'=>$poruka
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

    private function ucitajVozila()
    {
        $vozila = [];
        $v = new stdClass();
        $v->sifra = 0;
        $v->proizvodac = 'Odaberi';
        $v->model = 'Vozilo';
        $vozila[] = $v;
        foreach(Vozilo::read() as $vozilo){
            $vozila[]=$vozilo;
        }
        return $vozila;
    }

    private function ucitajKorisnike()
    {
        $korisnici = [];
        $k = new stdClass();
        $k->sifra = 0;
        $k->ime = 'Odaberi';
        $k->prezime = 'korisnika';
        $korisnici[] = $k;
        foreach(Korisnik::read() as $korisnik){
            $korisnici[]=$korisnik;
        }
        return $korisnici;
    }

    private function ucitajLokacije()
    {
        $lokacije = [];
        $l = new stdClass();
        $l->sifra = 0;
        $l->grad = 'Odaberi';
        $l->naziv_ulice = 'poslovnicu';
        $lokacije[] = $l;
        foreach(Lokacija::read() as $lokacija){
            $lokacije[]=$lokacija;
        }
        return $lokacije;
    }


    public function brisanje($sifra)
    {
        Rezervacija::delete($sifra);
        header('location: ' . App::config('url') . 'rezervacija');
    }

}