<?php

$dev=$_SERVER['SERVER_ADDR']=='127.0.0.1';

if($dev){
    return [
        'dev'=>$dev,
        'url'=>'http://rentacarapp.hr/',
        'nazivApp'=>'Rent a car',
        'rps'=>12,
        'baza'=>[
            'server'=>'localhost',
            'baza'=>'RentACar',
            'korisnik'=>'admin',
            'lozinka'=>'admin'
        ]
    ];
}else{
    return [
        'dev'=>$dev,
        'url'=>'https://polaznik02.edunova.hr/',
        'nazivApp'=>'Rent a car',
        'rps'=>12,
        'baza'=>[
            'server'=>'localhost',
            'baza'=>'aurelije_rentacar',
            'korisnik'=>'aurelije_admin',
            'lozinka'=>'s,zA(OEwW].u'
    ]
        ];
}