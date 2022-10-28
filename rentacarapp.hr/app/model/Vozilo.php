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
        
        select b.*, a.vozilo as slika
        from slikavozila a right join vozilo b 
        on a.vozilo=b.sifra
        where b.sifra=:sifra;
        
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
        
        select b.* , a.vozilo as DodajSliku
        from slikavozila a right join vozilo b 
        on a.vozilo=b.sifra
        order by 4,3
        
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

    public static function search($uvjet, $rezervacija)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
            select a.sifra, a.vozilo,
            b.proizvodac, b.model, b.godiste, 
            b.mjenjac from 
            rezervacija a inner join
            vozilo b on a.vozilo =b.sifra 
            where concat(b.proizvodac,\' \', b.model) like :uvjet
            order by 4,3
            limit 10
        ');
        $izraz->execute([
            'uvjet' => '%' . $uvjet . '%',
            'rezervacija' => $rezervacija
        ]); 
        return $izraz->fetchAll(); 
    }
}