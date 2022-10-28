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
        header('location: ' . App::config('url') . 'rezervacija/promjena/');
    }
    
    public function promjena($sifra = false)
    {
        $korisnici = $this->ucitajKorisnike();
        
        $lokacije = $this->ucitajLokacije();

        $vozila = $this->ucitajVozila();

        if (isset($_POST['nova']) && $_POST['nova'] === '1' ) {
            Rezervacija::create($_POST);
            header('location: ' . App::config('url') . 'rezervacija');
            return;
        }

        if(!$sifra){
            //prazna forma
            $this->detalji(false,$korisnici,$lokacije,$vozila,'Unesite podatke');
            return;
        }

        $this->entitet = (object) $_POST;
        $this->entitet->osiguranje = isset($_POST['osiguranje']);
        $this->entitet->sifra=$sifra;
        
        /*
        if($this->kontrola()){
            Rezervacija::update((array)$this->entitet);
            header('location: ' . App::config('url') . 'rezervacija');
            return;
        }
        */
    
        $entitet = Rezervacija::readOne($sifra);

        if (!$entitet instanceof stdClass || $entitet->sifra != true) {
            header('location: ' . App::config('url') . 'rezervacija');
        }

        if (isset($_POST['nova']) && $_POST['nova'] === '0' ) {
            $_POST['sifra'] = $sifra;
            Rezervacija::update($_POST);
            header('location: ' . App::config('url') . 'rezervacija');
            return;
        }
    

        $this->detalji($entitet,$korisnici,$lokacije,$vozila,$this->poruka);
    }

    private function detalji($e,$korisnici,$lokacije,$vozila,$poruka)
    {
        $this->view->render($this->phtmlDir . 'detalji', [
            'e'=>$e,
            'lokacije'=>$lokacije,
            'vozila'=>$vozila,
            'korisnici'=>$korisnici,
            'poruka'=>$poruka,
            'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">',
            'js'=>'<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
            <script>
                let url=\'' .  App::config('url') .  '\';
                let + vozilo;
            </script>
            <script src="'. App::config('url') . 'public/js/detaljiVozila.js"></script>
            '
        ]);
    }

    /*private function kontrola()
    {
        return $this->kontrolirajVozilo() && $this->kontrolirajLokaciju() && $this->kontrolirajKorisnik();
    }
        
    private function kontrolirajVozilo()
        {
            $this->entitet->vozilo = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->vozilo))));
    
            if ($this->entitet->vozilo == '') {
                $this->poruka = 'Vozilo je obavezano';
                return false;
            }
            return true;
        }

    private function kontrolirajLokaciju()
    {
        $this->entitet->lokacija = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->lokacija))));

        if ($this->entitet->lokacija == '') {
            $this->poruka = 'Lokacija je obavezna';
            return false;
        }
        return true;
    }

    private function kontrolirajKorisnik()
    {
        $this->entitet->korisnik = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->korisnik))));

        if ($this->entitet->korisnik == '') {
            $this->poruka = 'Korisnik je obavezan';
            return false;
        }
        return true;
    }

        private function kontrolaCijena()
    {
        $this->entitet->cijena = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->cijena))));
                
            if($this->entitet->cijena<=0){
                $this->poruka='Ako unosite cijenu, mora biti decimalni broj veći od 0, unijeli ste: ' 
            . $this->entitet->cijena;
            return false;
            }
            return true;
        }

    */

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
        $l->broj_ulice = '';
        $lokacije[] = $l;
        foreach(Lokacija::read() as $lokacija){
            $lokacije[]=$lokacija;
        }
        return $lokacije;
    }


    public function brisanje($sifra)
    {
        Rezervacija::brisanje($sifra);
        header('location: ' . App::config('url') . 'rezervacija');
    }

    public function dodajvozilo()
    {
        if(!isset($_GET['rezervacija']) || !isset($_GET['vozilo'])){
            return;
        }
        Rezervacija::dodajvozilo($_GET['rezervacija'],$_GET['vozilo']);
    }

    public function obrisivozilo()
    {
        if(!isset($_GET['rezervacija']) || !isset($_GET['vozilo'])){
            return;
        }
        Rezervacija::obrisivozilo($_GET['rezervacija'],$_GET['vozilo']);
    }

}