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
    $izraz = $veza->prepare('
    
    insert into 
    vozilo(proizvodac,model,godiste,gorivo,mjenjac,opis)
    values (:proizvodac,:model,:godiste,:gorivo,:mjenjac,:opis)

    ');
    $izraz->execute([
        'proizvodac'=>$p['proizvodac'],
        'model'=>$p['model'],
        'godiste'=>$p['godiste'],
        'gorivo'=>$p['gorivo'],
        'mjenjac'=>$p['mjenjac'],
        'opis'=>$p['opis']
    ]);
    $sifraVozila = $veza->lastInsertId();

    $izraz = $veza->prepare('
    
    insert into 
    korisnik(ime,prezime,email,broj_mobitela,ime_ulice,grad,drzava,broj_vozacke)
    values(:ime,:prezime,:email,:broj_mobitela,:ime_ulice,:grad,:drzava,:broj_vozacke)

    ');
    $izraz->execute([
        'ime'=>$p['ime'],
        'prezime'=>$p['prezime'],
        'email'=>$p['email'],
        'broj_mobitela'=>$p['broj_mobitela'],
        'ime_ulice'=>$p['ime_ulice'],
        'grad'=>$p['grad'],
        'drzava'=>$p['drzava'],
        'broj_vozacke'=>$p['broj_vozacke']
    ]);
    $sifraKorisnika = $veza->lastInsertId();

    $izraz = $veza->prepare('
    
    insert into 
    lokacija(naziv_ulice,broj_ulice,postanski_broj,grad,broj_mobitela,email)
    values (:naziv_ulice,:broj_ulice,:postanski_broj,:grad,:broj_mobitela,:email)

    ');
    $izraz->execute([
        'naziv_ulice'=>$p['naziv_ulice'],
        'broj_ulice'=>$p['broj_ulice'],
        'postanski_broj'=>$p['postanski_broj'],
        'grad'=>$p['grad'],
        'broj_mobitela'=>$p['broj_mobitela'],
        'email'=>$p['email']
    ]);
    $sifraLokacije = $veza->lastInsertId();

    $izraz = $veza->prepare('
        insert into
        rezervacija(vozilo,cijena,lokacija,datum_preuzimanja,datum_povratka,korisnik,osiguranje)
        values(:vozio,:cijena,:lokacija,:datum_preuzimanja,:datum_povratka,:korisnik,:osiguranje)
    ');
    $izraz->execute([
        'vozilo'=>$sifraVozila,
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

    // CRUD - U
    public static function update($p)
    {
// uskoro
    }

}

