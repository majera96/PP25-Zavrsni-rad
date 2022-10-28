<?php

class Vozilo
{

    public static function ukupnoVozila($uvjet)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select count(*) from vozilo 
            where proizvodac like :uvjet
      
        
        '); 
        $uvjet = '%' . $uvjet . '%';
        $izraz->bindParam('uvjet',$uvjet);
        $izraz->execute();
        return $izraz->fetchColumn();
    }

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
    public static function read($stranica,$uvjet)
    {

        $rps = App::config('rps');
        $od = $stranica * $rps - $rps;


        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
        select * from vozilo
        where proizvodac like :uvjet
        order by 4,3 limit :od, :rps
        
        ');

        $uvjet = '%' . $uvjet . '%';
        $izraz->bindValue('od',$od,PDO::PARAM_INT);
        $izraz->bindValue('rps',$rps,PDO::PARAM_INT);
        $izraz->bindParam('uvjet',$uvjet);
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

    public static function search($uvjet, $vozilo)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
            select * from vozilo
            where concat(proizvodac,\' \', model) like :uvjet
            order by 4,3
            limit 10
        ');
        $izraz->execute([
            'uvjet' => '%' . $uvjet . '%',
            'vozilo' => $vozilo
        ]); 
        return $izraz->fetchAll(); 
    }
}