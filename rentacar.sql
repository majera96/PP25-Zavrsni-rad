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
    broj_vrata decimal(8,5),
    maksimalni_broj_putnika decimal(8,5)
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
    drzava varchar(30),
    broj_vozacke decimal(18,9)
);

create table slikavozila(
    sifra int not null primary key auto_increment,
    vozilo int not null,
    putanja int not null
);

alter table rezervacija add foreign key (vozilo) references vozilo(sifra);
alter table rezervacija add foreign key (korisnik) references korisnik(sifra);
alter table rezervacija add foreign key (lokacija) references lokacija(sifra);
alter table slikavozila add foreign key (vozilo) references vozilo(sifra);

# Lokacije poslovnica rent-a-cara
insert into lokacija (sifra,naziv_ulice,broj_ulice,postanski_broj,grad,broj_mobitela,email)
values (null,'Ul.Franje Kuhača','26c',31400,'Đakovo',0955551111,'rentacardj@rentacar.com'),
    (null,'Vukovarska ulica','67',31207,'Klisa',0955551112,'rentacaros@rentacar.com'),
    (null,'Valtursko polje','210',52100,'Pula',0955551113,'rentacarpu@rentacar.com'),
    (null,'Ul. Rudolfa Fizira','21',10150,'Zagreb',0955551114,'rentacarzg@rentacar.com'),
    (null,'Cesta Dr. Franje Tuđmana','1270',21217,'Kaštel Štafilić',0955551115,'rentacarst@rentacar.com');


# 1. Gradska vozila > planiram dodati kategorije pa podjeliti vozila na njih
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,broj_vrata,maksimalni_broj_putnika)
values (null,'Ford','Fiesta','2021-05-24','benzin','automatik',3,2),
(null,'Volkswages','Up','2022-01-19','dizel','automatik',3,2),
(null,'Renault','Twingo','2022-05-01','dizel','automatik',5,4),
(null,'Peugeot','108','2020-12-20','dizel','automatik',3,2),
(null,'Hyundai','i10','2019-03-22','hybrid','automatik',3,2),
(null,'Smart','Fortwo cabrio Brabus','2021-01-4','benzin','manual',3,2),
(null,'Mazda','2','2018-07-05','dizel','manual',3,2);

# 2. Limuzine
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,broj_vrata,maksimalni_broj_putnika)
values (null,'Škoda','Octavia','2021-09-11','dizel','manual',5,5),
(null,'Škoda','Octavia','2022-03-12','dizel','automatik',5,5),
(null,'Volkswagen','Passat','2020-05-21','dizel','automatik',5,5),
(null,'Volkswages','Passat','2022-01-30','dizel','manual'5,5),
(null,'Renault','Talisman','2020-04-10','benzin','manual',5,5),
(null,'Audi','A4','2018-07-07','dizel','automatik'5,5);

# 3. Cargo
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,broj_vrata,maksimalni_broj_putnika)
values (null,'Volkswages','Caddy','2017-12-21','dizel','manul',3,3),
(null,'Volkswages','Crafter Furgon','2020-10-11','dizel','manul',3,3);

# 4. Luksuzna
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,broj_vrata,maksimalni_broj_putnika)
values (null,'Audi','A6 50TDI Quattro','2021-12-19','dizel','automatik',5,5),
(null,'Audi','A5 50TDI Quattro','2020-06-11','dizel','automatik',5,5),
(null,'BMW','750 xDrive','2022-06-13','dizel','automatik',5,5);

# 5. Karavan
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,broj_vrata,maksimalni_broj_putnika)
values (null,'Renault','Clio Grandtour','2019-09-14','dizel','manual',5,5),
(null,'Škoda','Octavia Combi','2018-02-27','benzin','manual'5,5);

# 6. SUV
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,broj_vrata,maksimalni_broj_putnika)
values (null,'Volkswagen','Tiguan','2021-03-15','dizel','automatik',5,5),
(null,'Škoda','Kodiaq','2019-03-17','benzin','manual',5,5);


#Unos korisnika
insert into korisnik(sifra,ime,prezime,email,broj_mobitela,ime_ulice,grad,drzava,broj_vozacke)
values (null,'Martina','Ivankovič','martinai@gmail.com','385912348796','Osječka ulica 78','Osijek','Hrvatska','1258796521'),
(null,'James','Knowille','knowille@yhotmail.com','07084421483','Amber hill 17b','London','Engleska','55655214'),
(null,'Lukas','Mayer','lukasmayer@gmail.com','06431849780','Maffie platz 13','Stuttgart','Njemačka','5502255'),
(null,'Luka','Lukić','lukalukić@gmail.com','0955874369','Kralja Tomislava 99a','Zagreb','Hrvatska','125478522'),
(null,'Ivan','Ivanović','ivanovic@gmail.com','0998563478','Franje Kuhača 26c','Đakovo','Hrvatska','151515'),
(null,'Tara','Reić','tarareic@gmail.com','0987532687','Put brodarica 95','Split','Hrvatska','548456256');

#Unos rezervacija
insert into rezervacija (sifra,vozilo,cijena,lokacija,datum_preuzimanja,datum_povratka,korisnik,osiguranje)
values (null,5,1150.00,4,'2022-05-02','2022-05-05',1,true),
(null,13,2100.00,1,'2022-03-12','2022-03-16',3,true), 
(null,18,28900.00,3,'2021-12-15','2022-1-15',2,true),
(null,1,350.00,2,'2022-04-04','2022-04-05',4,false),
(null,22,5500.00,4,'2022-05-22','2022-05-26',5,true),
(null,12,11000.00,2,'2022-01-02','2022-01-10',6,true);

#Prikazati imena i prezimena korisnika te proizvodača i modela vozila koja su rezervirali
select a.ime,a.prezime,b.vozilo,c.proizvodac,c.model
from korisnik a inner join rezervacija b 
on a.sifra = b.korisnik
inner join vozilo c 
on b.vozilo = c.sifra;

#Prikaz iznosa dnevne zarade ukoliko su sva vozila iznajmljena u jednom danu
#Iznos: 49.000
select sum(cijena) from rezervacija;





