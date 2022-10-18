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

    // CRUD - C
    public static function create($p)
{
    $veza = DB::getInstance();
    $veza->beginTransaction();
    //Vozilo
    $izraz = $veza->prepare('
    
        select sifra from vozilo
        where proizvodac=:proizvodac and model=:model
    
    ');
    $izraz->execute(
        [
            'proizvodac'=>$p['proizvodac'],
            'model'=>$p['model']
        ]
    );
    $sifraVozilo = $izraz->fetchColumn();
    // Lokacija
    $izraz = $veza->prepare('
    
        select sifra from lokacija
        where grad=:grad,naziv_ulice=:naziv_ulice and broj_ulice=:broj_ulice
    
    ');
    $izraz->execute(
        [
            'grad'=>$p['grad'],
            'naziv_ulice'=>$p['naziv_ulice'],
            'broj_ulice'=>$p['broj_ulice']
        ]
    );
    $sifraLokacija = $izraz->fetchColumn();
    // Korisnik
    $izraz = $veza->prepare('
    
    select sifra from korisnik
    where ime=:ime,prezime=:prezime and broj_vozacke=:broj_vozacke

');
$izraz->execute(
    [
        'ime'=>$p['ime'],
        'prezime'=>$p['prezime'],
        'broj_vozacke'=>$p['broj_vozacke']
    ]
);
$sifraKorisnik = $izraz->fetchColumn();
// Rezervacija
$izraz = $veza->prepare('
        
    insert into
    rezervacija(vozilo,cijena,lokacija,datum_preuzimanja,datum_povratka,korisnik,osiguranje)
    values(:vozio,:cijena,:lokacija,:datum_preuzimanja,:datum_povratka,:korisnik,:osiguranje)
');
$izraz->execute(
[
    'vozilo'=>$sifraVozilo,
    'cijena'=>$p['cijena'],
    'lokacija'=>$sifraLokacija,
    'datum_preuzimanja'=>$p['datum_preuzimanja'],
    'datum_povratka'=>$p['datum_povratka'],
    'korisnik'=>$sifraKorisnik,
    'osiguranje'=>$p['osiguranje'],
]);

$sifraRezervacija = $veza->lastInsertId();
$veza->commit();
return $sifraRezervacija;
}

    public static function update($p)
    {
        $veza = DB::getInstance();
        $veza->beginTransaction();

    //Vozilo
    $izraz = $veza->prepare('
    
        select sifra from vozilo
        where proizvodac=:proizvodac and model=:model;
    
    ');
    $izraz->execute(
        [
            'proizvodac'=>$p['proizvodac'],
            'model'=>$p['model']
        ]
    );
    $sifraVozilo = $izraz->fetchColumn();
    // Lokacija
    $izraz = $veza->prepare('
    
        select sifra from lokacija
        where grad=:grad,naziv_ulice=:naziv_ulice and broj_ulice=:broj_ulice;
    
    ');
    $izraz->execute(
        [
            'grad'=>$p['grad'],
            'naziv_ulice'=>$p['naziv_ulice'],
            'broj_ulice'=>$p['broj_ulice']
        ]
    );
    $sifraLokacija = $izraz->fetchColumn();
    // Korisnik
    $izraz = $veza->prepare('
    
    select sifra from korisnik
    where ime=:ime,prezime=:prezime and broj_vozacke=:broj_vozacke;

');
$izraz->execute(
    [
        'ime'=>$p['ime'],
        'prezime'=>$p['prezime'],
        'broj_vozacke'=>$p['broj_vozacke']
    ]
);
$sifraKorisnik = $izraz->fetchColumn();
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
    'vozilo'=>$sifraVozilo,
    'cijena'=>$p['cijena'],
    'lokacija'=>$sifraLokacija,
    'datum_preuzimanja'=>$p['datum_preuzimanja'],
    'datum_povratka'=>$p['datum_povratka'],
    'korisnik'=>$sifraKorisnik,
    'osiguranje'=>$p['osiguranje'],
    'sifra'=>$p['sifra']
]);

$veza->commit();

}

}