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
        'lokacija'=>$sifraLokacije,
        'datum_preuzimanja'=>$p['datum_preuzimanja'],
        'datum_povratka'=>$p['datum_povratka'],
        'korisnik'=>$sifraKorisnika,
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

        $izraz = $veza->prepare('
        
           select vozilo from rezervacija where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$p['sifra']
        ]);
        $sifraVozila = $izraz->fetchColumn();

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
        $izraz->execute([
            'proizvodac'=>$p['proizvodac'],
            'model'=>$p['model'],
            'godiste'=>$p['godiste'],
            'gorivo'=>$p['gorivo'],
            'mjenjac'=>$p['mjenjac'],
            'opis'=>$p['opis'],
            'sifra'=>$sifraVozila
        ]);

        $izraz = $veza->prepare('
        
           select korisnik from rezervacija where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$p['sifra']
        ]);
        $sifraKorisnika = $izraz->fetchColumn();

        $izraz = $veza->prepare('
            
            update korisnik set
            ime=:ime,
            prezime=:prezime,
            email=:email,
            broj_mobitela=:broj_mobitela,
            ime_ulice=:ime_ulice,
            grad=:grad,
            drzava=:drzava,
            broj_vozacke=:broj_vozacke
            where sifra=:sifra

        ');
        $izraz->execute([
            'ime'=>$p['ime'],
            'prezime'=>$p['prezime'],
            'email'=>$p['email'],
            'broj_mobitela'=>$p['broj_mobitela'],
            'ime_ulice'=>$p['ime_ulice'],
            'grad'=>$p['grad'],
            'drzava'=>$p['drzava'],
            'broj_vozacke'=>$p['broj_vozacke'],
            'sifra'=>$sifraKorisnika
        ]);

        $izraz = $veza->prepare('
        
        select lokacija from rezervacija where sifra=:sifra
     
     ');
     $izraz->execute([
         'sifra'=>$p['sifra']
     ]);
     $sifraLokacije = $izraz->fetchColumn();

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
     $izraz->execute([
         'naziv_ulice'=>$p['naziv_ulice'],
         'broj_ulice'=>$p['broj_ulice'],
         'postanski_broj'=>$p['postanski_broj'],
         'grad'=>$p['grad'],
         'broj_mobitela'=>$p['broj_mobitela'],
         'email'=>$p['email'],
         'sifra'=>$sifraLokacije
     ]);

        $izraz = $veza->prepare('
            update rezervacija set
            cijena=:cijena,
            datum_preuzimanja=:datum_preuzimanja,
            datum_povratka=:datum_povratka,
            osiguranje=:osiguranje
            where sifra=:sifra
        ');
        $izraz->execute([
            'cijena'=>$p['cijena'],
            'datum_preuzimanja'=>$p['datum_preuzimanja'],
            'datum_povratka'=>$p['datum_povratka'],
            'osiguranje'=>$p['osiguranje'],
            'sifra'=>$p['sifra']
        ]);


        $veza->commit();

    }

}

