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

       /* if(!isset($_GET['stranica'])){
            $stranica=1;
        }else{
            $stranica=(int)$_GET['stranica'];
        }
        

        if(!isset($_GET['uvjet'])){
            $uvjet='';
        }else{
            $uvjet=$_GET['uvjet'];
        }


        $up = Vozilo::ukupnoVozila($uvjet);
        $ukupnoStranica = ceil($up / App::config('rps'));
        
        if($stranica>$ukupnoStranica){
            $stranica = 1;
        }

        if($stranica==0){
            $stranica=$ukupnoStranica;
        }

        */
        $this->view->render($this->phtmlDir . 'index',[
            'entiteti'=>Vozilo::read(),
          //  'uvjet'=>$uvjet,
            //'stranica' => $stranica,
            //'ukupnoStranica'=>$ukupnoStranica,
           // 'js'=>'<script>
           // let url=\'' . App::config('url') . '\';
           // </script>
            // <script src="' . App::config('url') . 'public/js/indexVozila.js"></script>'
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
        return $this->kontrolirajProizvodac() && $this->kontrolirajGodiste() && $this->kontrolirajModel() && $this->kontrolirajOpis();
    }
        
    private function kontrolirajProizvodac()
    {
        $this->entitet->proizvodac = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->proizvodac))));

        if ($this->entitet->proizvodac == '') {
            $this->poruka = 'Proizvođač vozila je obavezan';
            return false;
        }
        return true;
    }

    private function kontrolirajModel()
    {
        $this->entitet->model = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->model))));

        if ($this->entitet->model == '') {
            $this->poruka = 'Model vozila je obavezan';
            return false;
        }

        return true;
    }

    private function kontrolirajGodiste()
    {

        $this->entitet->godiste = trim(str_replace(' ', '', (str_replace('&nbsp;', '', $this->entitet->godiste))));

        if ($this->entitet->godiste == '') {
            $this->poruka = 'Godište vozila je obavezno';
            return false;
        }

        $godiste = (int)$this->entitet->godiste;

        if($godiste<=2015){
            $this->poruka = 'Godište vozila mora biti maksimalne starosti od 7 godina';
            return false;
        }

        if($godiste>2023) {
            $this->poruka = 'Živimo u 2022. godini';
            return false;
        }

        return true;

    }

    private function kontrolirajOpis()
    {
        $this->entitet->opis=trim(str_replace('&nbsp;', '', $this->entiter->opis));

        if(strlen(($this->entitet->opis))>100) {
            $this->poruka = 'Maksimalno unjeti 100 znakova';
            return false;
        }

        return true;
    }

    public function trazi()
    {
        if(!isset($_GET['term'])){
            return;
        }
        echo json_encode(Vozilo::search($_GET['term']));
    }

    public function brisanje($sifra)
    {
        Vozilo::delete($sifra);
       // $uvjet = isset($_GET['uvjet']) ? $_GET['uvjet'] : '';
        //$stranica = isset($_GET['stranica']) ? $_GET['stranica'] : '';
     //   header('location: ' . App::config('url') . 'vozilo?uvjet=' . $uvjet . '&stranica=' . $stranica);
     header('location: ' . App::config('url') . 'vozilo');
    }

}

