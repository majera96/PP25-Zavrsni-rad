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
        return $this->kontrolirajIme() && $this->kontrolirajPrezime() && $this->kontrolirajDrzava() && $this->kontrolirajKontakt()&& $this->kontrolirajBrojVozacke();
    }
        
    private function kontrolirajIme()
    {
        $this->entitet->ime = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->ime))));

        if ($this->entitet->ime == '') {
            $this->poruka = 'Ime korisnika obavezno';
            return false;
        }
        return true;
    }

    private function kontrolirajPrezime()
    {
        $this->entitet->prezime = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->prezime))));

        if($this->entitet->prezime == '') {
            $this->poruka = 'Prezime korisnika obavezno';
            return false;
        }

        return true;
    }

    private function kontrolirajDrzava()
    {

        $this->entitet->drzava = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->drzava))));

        if($this->entitet->drzava == '') {
            $this->poruka = 'Odaberite državu';
            return false;
        }

        return true;

    }

    private function kontrolirajKontakt()
    {
        $this->entitet->email = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->email))));

        if($this->entitet->email == '') {
            $this->poruka = 'Kontakt mail je obavezan';
            return false;
        }

        return true;
    }

    private function kontrolirajBrojVozacke($promjenaUnosa = false)
    {
        $this->entitet->broj_vozacke = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->broj_vozacke))));

        if($this->entitet->drzava !== 'Croatia') {
            if($this->entitet->broj_vozacke == '') {
                $this->poruka = 'Broj vozačke je obavezan za strane državljane';
                return false;
            }

            $postojeciBrojeviVozacke = Korisnik::getPostojeciBrojeviVozacke();
            if(in_array($this->entitet->broj_vozacke, $postojeciBrojeviVozacke)) {
                if (!$promjenaUnosa) {
                    $this->poruka = 'Broj vozačke već postoji';
                    return false;
                }
            }
        }

        return true;
    }

    public function brisanje($sifra)
    {
        Korisnik::delete($sifra);
        header('location: ' . App::config('url') . 'korisnik');
    }

}


