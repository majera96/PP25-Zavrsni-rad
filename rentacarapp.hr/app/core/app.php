<?php

class App
{
    public static function start()
    {
        $ruta = Request::getRuta();

        $dijelovi=explode('/', $ruta);

        $klasa = '';
        if(!isset($dijelovi[1]) || $dijelovi[1]===''){
            $klasa = 'Index Controller';
        } else {
            $klasa = ucfirst($dijelovi[1]) . 'Controller';
        }
 
        $metoda = '';
        if(!isset($dijelovi[2]) || $dijelovi[2]===''){
            $metoda = 'index';
        } else{
            $metoda = $dijelovi[2];
        }

        if(class_exists($klasa) && method_exists($klasa,$metoda)){
            $instanca = new $klasa();
            $instanca->$metoda();
        } else {
            $view = new View();
            $view->render('errorKlasaMetoda',[
                'klasa'=>$klasa,
                'metoda'=>$metoda
            ]);
        }
    }

    public static function config($kljuc)
    {
        $configFile = BP_APP . 'konfiguracija.php';
        if(!file_exist($configFile)){
            return 'Datoteka' . $configFile . ' ne postoji. Kreiraj ju';
        }
        $config = require $configFile;
        if(isset($config))


    }
}