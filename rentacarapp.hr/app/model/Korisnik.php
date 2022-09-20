<?php

class Korisnik
{
    public static function  readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from korisnik where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetch();
    }

    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select * from korisnik
        
    ');
    $izraz->execute();
    return $izraz->fetchAll();
    }

    public static function create()
    {
    
    }

    public static function delete()
    {

    }

    public static function update()
    {

    }

    
}
