#Izrada ERD za zavrsni projekt

#C:\xampp\mysql\bin\mysql -uroot --default_character_set=utf8 < C:\Users\Korisnik\Documents\GitHub\Projekt\rentacar.sql

drop database if exists RentACar;
create database RentACar default charset utf8mb4;
use RentACar;

create table vozilo(
    sifra int not null primary key auto_increment,
    proizvodac varchar(30),
    model varchar(30),
    godiste datetime,
    gorivo varchar(30),
    mjenjac varchar(20),
    opisvozila text
);

create table rezervacija(
    sifra int not null primary key auto_increment,
    vozilo int not null,
    cijena decimal(18,2),
    lokacija int not null,
    datum_preuzimanja datetime,
    datum_povratka datetime,
    korisnik int not null,
    osiguranje boolean
);

create table lokacija(
    sifra int not null primary key auto_increment,
    naziv_ulice varchar(50),
    broj_ulice varchar(10),
    postanski_broj char(5),
    grad varchar(30),
    broj_mobitela varchar(20),
    email varchar(100)
);

create table korisnik(
    sifra int not null primary key auto_increment,
    ime varchar(30) not null,
    prezime varchar(50) not null,
    email varchar(100),
    broj_mobitela varchar(20),
    ime_ulice varchar(100),
    grad varchar(50),
    drzava varchar(30)

);

create table slikavozila(
    sifra int not null primary key auto_increment,
    vozilo int not null
);

alter table rezervacija add foreign key (vozilo) references vozilo(sifra);
alter table rezervacija add foreign key (korisnik) references korisnik(sifra);
alter table rezervacija add foreign key (lokacija) references lokacija(sifra);
alter table slikavozila add foreign key (vozilo) references vozilo(sifra);