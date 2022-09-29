<?php

class VoziloController extends AutorizacijaController
{
    private $phtmlDir = 'privatno' . DIRECTORY_SEPARATOR . 'vozila' . DIRECTORY_SEPARATOR;
    private $vozio = null;
    private $poruka = '';

    public function index()
    {
        $this->view->render(
            $this->phtmlDir . 'index',
            ['vozilo' => Vozilo::read()]
        );
    }

   
 public function unosVozila()
    {
        if (count($_POST) == 0) {
            $this->pripremiVozilo();
            $this->view->render(
                $this->phtmlDir . 'create',
                ['vozilo' => $this->vozilo, 
                'poruka' => 'Ispuniti podatke označene sa *']
            );
            return;
        }

        $this->vozilo = (object) $_POST;

        if ($this->kontrolirajUnosVozila()) {
            Vozilo::create((array)$this->vozilo);
            header('location: ' . App::config('url') . 'vozila');
            return;
        }

        $this->view->render(
            $this->phtmlDir . 'create',
            ['vozilo' => $this->vozilo, 
            'poruka' => $this->poruka]
        );
    }

    private function kontrolirajUnosVozila()
    {
        return $this->kontrolirajProizvodac()
            && $this->kontrolirajModel()
            && $this->kontrolirajGodiste();
    }

    // Izmjena START
    public function promjena($sifra)
    {
        if(count($_POST) == 0){
            $vozio = Vozilo::readOne($sifra);

            if ($vozilo==null) {
                header('location: ' . App::config('url') . 'vozila');
            }

            $this->view->render(
                $this->phtmlDir . 'update',
                ['vozilo' => $vozilo,
                 'poruka' => 'Ispuniti podatke označene sa *']
            );
            return;
        }

        $this->vozilo = (object) $_POST;
        $this->vozilo->sifra = $sifra;

        if($this->kontrolirajPromjenu()) {
            Vozilo::update((array)$this->vozilo);
            header('location: ' . App::config('url') . 'vozila');
            return;
        }

        $this->view->render(
            $this->phtmlDir . 'update',
            ['vozilo' => $this->vozilo, 
            'poruka' => $this->poruka]
        );
    }

    public function kontrolirajPromjenu()
    {
        return $this->kontrolirajProizvodac()
            && $this->kontrolirajModel()
            && $this->kontrolirajGodiste();
    }
    
    public function brisanje($sifra)
    {
        $vozilo = Vozilo::readOne($sifra);

        if ($vozilo == null) {
            header('location: ' . App::config('url') . 'vozila');
        }

        if (!isset($_POST['obrisi'])) {
            $this->view->render(
                $this->phtmlDir . 'delete',
                [
                    'vozilo' => $vozilo,
                    'poruka' => 'Provjerite detalje vozila koji će biti obrisan:'
                ]
            );
            return;
        }

        if (!isset($_POST['potvrdiBrisanje'])) {
            $this->view->render(
                $this->phtmlDirektorij . 'delete',
                [
                    'vozilo' => $vozilo,
                    'poruka' => 'Potvrdite brisanje:'
                ]
            );
            return;
        }

        Vozilo::delete($sifra);
        header('location: ' . App::config('url') . 'vozila');
    }
    
    private function kontrolirajProizvodac($promjenaUnosa = false)
    {
        $this->vozilo->proizvodac = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->vozilo->proizvodac))));

        if ($this->vozilo->proizvodac == '') {
            $this->poruka = 'Proizvodac vozila je obavezan';
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

    private function pripremiUnos()
    {
        $this->vozilo = new stdClass();
        $this->vozilo->proizvodac = '';
        $this->vozilo->model = '';
        $this->vozilo->godiste = '';
        $this->vozilo->gorivo = '';
        $this->vozilo->mjenjac = '';
        $this->vozilo->broj_vrata = '';
        $this->vozilo->maksimalni_broj_putnika = '';
    }
}