<?php

class Vozilo
{

    public static function brisanje($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from vozilo where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        $izraz->fetchColumn();
    }

    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from vozilo where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetch(); 
    }

    // CRUD - R
    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from vozilo order by proizvodac
        
        ');
        $izraz->execute(); 
        return $izraz->fetchAll();
    }

    // CRUD - C
    public static function create($vozilo)
    {
        $veza = DB::getInstance();

        $izraz = $veza->prepare('
        
            insert into 
            vozilo(proizvodac,model,godiste,gorivo,mjenjac,opis)
            values (:proizvodac,:model,:godiste,:gorivo,:mjenjac,:opis)
        
        ');
        $izraz->execute($vozilo);
        return $veza->lastInsertId();
    }

    // CRUD - U
    public static function update($vozilo)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            update vozilo set
                proizvodac=:proizvodac,
                model=:model,
                godiste=:godiste,
                gorivo=:gorivo,
                mjenjac=:mjenjac,
                opis=:opis
                where sifra=:sifra
        
        ');
        $izraz->execute($vozilo);
    }

     // CRUD - D
    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from vozilo where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
    }
}