<?php

class Lokacija
{

    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from lokacija where sifra=:sifra
        
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
        
            select * from lokacija
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function create($lokacija)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            insert into 
            lokacija(naziv_ulice,broj_ulice,postanski_broj,grad,broj_mobitela,email)
            values (:naziv_ulice,:broj_ulice,:postanski_broj,:grad,:broj_mobitela,:email)
        
        ');
        $izraz->execute($lokacija);
        return $veza->lastInsertId();
    }

    public static function update($lokacija)
    {
     
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            update lokacija set
            naziv_ulice=:naziv_ulice,
                broj_ulice=:broj_ulice,
                postanski_broj=:postanski_broj,
                grad=:grad,
                broj_mobitela=:broj_mobitela,
                email=:email
                where sifra=:sifra
        
        ');
        $izraz->execute($lokacija);

    }

     public static function delete($sifra)
     {
         $veza = DB::getInstance();
         $izraz = $veza->prepare('
         
            delete from lokacija where sifra=:sifra 
         
         ');
         $izraz->execute([
             'sifra'=>$sifra
         ]);
         
     }
 
 }