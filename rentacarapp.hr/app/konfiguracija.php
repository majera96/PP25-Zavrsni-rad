<?php

$dev=$_SERVER['SERVER_ADDR']=='127.0.0.1';

if($dev){
    return [
        'dev'=>$dev,
        'url'=>'http://rentacarapp.hr/',
        'nazivApp'=>'Rent a car',
        'baza'=>[
            'server'=>'localhost',
            'baza'=>'rentacar',
            'korisnik'=>'admin',
            'lozinka'=>'admin'
        ]
    ];
}else{
    // PRODUKCIJA
    return [
        'dev'=>$dev,
        'url'=>'http://rentacarapp.hr/',
        'nazivApp'=>'Rent a car',
        'baza'=>[
            'server'=>'localhost',
            'baza'=>'rentacar',
            'korisnik'=>'admin',
            'lozinka'=>'admin'
    ]
        ];
}