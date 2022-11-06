<?php

class Operater
{
    public static function autoriziraj($email,$lozinka)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from operater where email=:email;
        
        ');
        $izraz->execute([
            'email'=>$email
        ]);
        $operater = $izraz->fetch();
        if($operater==null){
            return null;
        }
        if(!password_verify($lozinka,$operater->lozinka)){
            return null;
        }
        unset($operater->lozinka);
        return $operater;
    }

    public static function verificirajEmail($sifra)
    {
        $veza = DB::getInstance();

        $izraz = $izraz->prepare('
            update operater
            set verificiran = true
            where sifra = :sifra
        ');
        $izraz->execute(['sifra' => $sifra]);
    }

    public static function read()
    {
        $veza = DB::getInstance();

        $izraz = $veza->prepare('
            select * from operater
        ');
        $izraz->execute();

        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza = DB::getInstance();

        $izraz = $izraz->prepare('
            select * from operater
            where sifra = :sifra
        ');
        $izraz->execute(['sifra' => $sifra]);

        return $izraz->fetch();
    }

    public static function create($p)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            insert into operater
            (email, lozinka, ime, prezime, uloga, verificiran)
            values
            (:email, :lozinka, :ime, :prezime, :uloga, false)
        
        ');
        $izraz->execute([
            'email' => $p['email'],
            'lozinka' => $p['lozinka'],
            'ime' => $p['ime'],
            'prezime' => $p['prezime'],
            'uloga' => $p['uloga']
        ]);
    }

    public static function update($p)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            update operater
            set
            email = :email,
            lozinka = :lozinka,
            ime = :ime,
            prezime = :prezime,
            uloga = :uloga
            where sifra = :sifra
        
        ');
        $izraz->execute([
            'email' => $p['email'],
            'lozinka' => $p['lozinka'],
            'ime' => $p['ime'],
            'prezime' => $p['prezime'],
            'uloga' => $p['uloga'],
            'sifra' => $p['sifra']
        ]);
    }

    public static function delete($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            delete from operater where sifra = :sifra
        
        ');
        $izraz->execute(['sifra' => $sifra]);
    }

    public static function getPostojeciEmailovi()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
            select email from operater
        ');
        $izraz->execute();
        $postojeciEmailovi = $izraz->fetchAll();

        foreach ($postojeciEmailovi as $objekt) {
            $nizPostojecihEmailova[] = $objekt->email;
        }

        return $nizPostojecihEmailova;
    }

    public static function getSifraSaEmailom($email)
    {
        $veza = DB::getInstance();
        $izraz = $izraz->prepare('
            select sifra from operater
            where email = :email
        ');
        $izraz->execute(['email' => $email]);
        return $izraz->fetch();
    }
    // Geteri END
}