<?php

class Korisnik
{
    public static function readOne($sifra)
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

    public static function create($korisnik)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            insert into 
            korisnik(ime,prezime,email,broj_mobitela,ime_ulice,grad,drzava,broj_vozacke)
            values(:ime,:prezime,:email,:broj_mobitela,:ime_ulice,:grad,:drzava,:broj_vozacke)
        
        ');
        $izraz->execute($korisnik);
        return $veza->lastInsertId();
    }

    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
           delete from korisnik where sifra=:sifra 
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        
    }   

    public static function update($korisnik)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            update korisnik set
                ime=:ime,
                prezime=:prezime,
                email=:email,
                broj_mobitela=:broj_mobitela,
                grad=:grad,
                drzava=:drzava,
                broj_vozacke=:broj_vozacke
                where sifra=:sifra
        
        ');
        $izraz->execute($korisnik);
    }

}
