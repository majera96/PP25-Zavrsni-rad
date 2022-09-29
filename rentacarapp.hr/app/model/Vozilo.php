<?php

class Vozilo
{
    public static function create($vozilo)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            insert into vozilo
            (proizvodac,model,godiste,gorivo,mjenjac,broj_vrata,maksimalni_broj_putnika)
            values
            (:proizvodac,:model,:godiste,:gorivo,:mjenjac,:broj_vrata,:maksimalni_broj_putnika)
        
        ');
        $izraz->execute($vozilo);
    }

    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
            select * from vozilo where sifra = :sifra
        ');
        $izraz->execute(['sifra' => $sifra]);
        return $izraz->fetch();
    }

    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
            select * from vozilo
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function update($vozilo)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            update vozilo
            set
            proizvodac = :proizvodac,
            model = :model,
            godiste = :godiste,
            mjenjac = :mjenjac,
            broj_vrata = :broj_vrata,
            maksimalni_broj_putnika = :maksimalni_broj_putnika
            where sifra = :sifra
        
        ');
        $izraz->execute($vozilo);
    }

    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from vozilo where sifra = :sifra
        
        ');
        $izraz->execute(['sifra' => $sifra]);
    }

}