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
        select a.*, e.ime,e.prezime,e.broj_vozacke,c.grad, c.naziv_ulice ,b.proizvodac ,b.model, a.cijena ,a.datum_preuzimanja,a.datum_povratka ,a.osiguranje 
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

    // CRUD - C
    public static function create($p)
{
    $veza = DB::getInstance();

// Rezervacija
$izraz = $veza->prepare(' 
    insert into
    rezervacija(vozilo,lokacija,korisnik,cijena,datum_preuzimanja,datum_povratka,osiguranje)
    values(:vozilo,:lokacija,:korisnik,:cijena,:datum_preuzimanja,:datum_povratka,:osiguranje)');

$izraz->execute(
[
    'vozilo'=>$p['vozilo'],
    'cijena'=>$p['cijena'],
    'lokacija'=>$p['lokacija'],
    'datum_preuzimanja'=>$p['datum_preuzimanja'],
    'datum_povratka'=>$p['datum_povratka'],
    'korisnik'=>$p['korisnik'],
    'osiguranje'=>$p['osiguranje'] ?? 0,
]);

return $veza->lastInsertId();
}

    public static function update($p)
    {
        $veza = DB::getInstance();
       // $veza->beginTransaction();

// Rezervacija
$izraz = $veza->prepare('
        
    update rezervacija set 
    vozilo=:vozilo,
    cijena=:cijena,
    lokacija=:lokacija,
    datum_preuzimanja=:datum_preuzimanja,
    datum_povratka=:datum_povratka,
    korisnik=:korisnik,
    osiguranje=:osiguranje
    where sifra=:sifra

');

$izraz->execute(
[
    'vozilo'=>$p['vozilo'],
    'cijena'=>$p['cijena'],
    'lokacija'=>$p['lokacija'],
    'datum_preuzimanja'=>$p['datum_preuzimanja'],
    'datum_povratka'=>$p['datum_povratka'],
    'korisnik'=>$p['korisnik'],
    'osiguranje'=>$p['osiguranje'] ?? 0,
    'sifra'=>$p['sifra']
]);

//$veza->commit();

}

}