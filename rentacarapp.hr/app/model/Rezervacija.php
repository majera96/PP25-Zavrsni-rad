<?php

class Rezervacija
{

    public static function brisanje($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from rezervacija where sifra=:sifra
        
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
        select a.sifra,e.ime,e.prezime,e.broj_vozacke,c.grad, c.naziv_ulice ,b.proizvodac ,b.model, a.cijena ,a.datum_preuzimanja,a.datum_povratka ,a.osiguranje 
        from rezervacija a inner join vozilo b 
        on a.vozilo = b.sifra
        inner join lokacija c 
        on a.lokacija = c.sifra 
        inner join korisnik e 
        on a.korisnik = e.sifra
        where a.sifra=:sifra
        
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
        
        select a.sifra,e.ime,e.prezime,e.broj_vozacke,c.grad, c.naziv_ulice, c.broj_ulice 
        ,b.proizvodac ,b.model, a.cijena ,a.datum_preuzimanja,a.datum_povratka ,a.osiguranje 
        from rezervacija a inner join vozilo b 
        on a.vozilo = b.sifra
        inner join lokacija c 
        on a.lokacija = c.sifra 
        inner join korisnik e 
        on a.korisnik = e.sifra
        group by a.sifra,e.ime,e.prezime,e.broj_vozacke,c.grad, c.naziv_ulice ,b.proizvodac 
        ,b.model, a.cijena ,a.datum_preuzimanja,a.datum_povratka ,a.osiguranje
        order by 4,3
        
        ');
        $izraz->execute(); 
        return $izraz->fetchAll();
    }

    public static function create($p)
{
   // ne radi
}

    public static function update($p)
    {
// uskoro
    }

}

