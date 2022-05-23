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
    vozilo int not null,
    putanja int not null
);

alter table rezervacija add foreign key (vozilo) references vozilo(sifra);
alter table rezervacija add foreign key (korisnik) references korisnik(sifra);
alter table rezervacija add foreign key (lokacija) references lokacija(sifra);
alter table slikavozila add foreign key (vozilo) references vozilo(sifra);

# Lokacije poslovnica rent-a-cara
insert into lokacija (sifra,naziv_ulice,broj_ulice,postanski_broj,grad,broj_mobitela,email)
values (null,'Franje Kuhača','26c',31400,'Đakovo',0955218170,'majerdjakovo@rentacar.com'),
 (null,'Ul.Lorenza Jagera','5',31000,'Osijek',0998743213,'majerosijek@rentacar.com');


# 1. Gradska vozila > planiram dodati kategorije pa podjeliti vozila na njih
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,opisvozila)
values (null,'Ford','Fiesta','2021-05-24','benzin','automatik','uskoro'),
(null,'Volkswages','Up','2022-01-19','dizel','automatik','opis'),
(null,'Renault','Twingo','2022-05-01','dizel','automatik','uskoro'),
(null,'Peugeot','108','2020-12-20','dizel','automatik','uskoro'),
(null,'Hyundai','i10','2019-03-22','hybrid','automatik','uskoro'),
(null,'Smart','Fortwo cabrio Brabus','2021-01-4','benzin','automatik','uskoro'),
(null,'Mazda','2','2018-07-05','dizel','Manual','uskoro');

# 2. Limuzine
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,opisvozila)
values (null,'Škoda','Octavia','2021-09-11','dizel','Manual','uskoro'),
(null,'Škoda','Octavia','2022-03-12','dizel','Automatik','uskoro'),
(null,'Volkswagen','Passat','2020-05-21','dizel','Automatski','uskoro'),
(null,'Volkswages','Passat','2022-01-30','dizel','Manual','uskoro'),
(null,'Renault','Talisman','2020-04-10','benzin','Manual','uskoro'),
(null,'Audi','A4','2018-07-07','dizel','Automatik','uskoro');

# 3. Cargo
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,opisvozila)
values (null,'Volkswages','Caddy','2017-12-21','dizel','Manul','Uskoro'),
(null,'Volkswages','Crafter Furgon','2020-10-11','dizel','Manul','Uskoro');

# 4. Luksuzna
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,opisvozila)
values (null,'Audi','A6 50TDI Quattro','2021-12-19','dizel','Automatik','Uskoro'),
(null,'Audi','A5 50TDI Quattro','2020-06-11','dizel','Automatik','Uskoro'),
(null,'BMW','750 xDrive','2022-06-13','dizel','Automatik','Uskoro');

# 5. Karavan
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,opisvozila)
values (null,'Renault','Clio Grandtour','2019-09-14','dizel','Manual','Uskoro'),
(null,'Škoda','Octavia Combi','2018-02-27','benzin','Manual','Uskoro');

# 6. SUV
insert into vozilo (sifra,proizvodac,model,godiste,gorivo,mjenjac,opisvozila)
values (null,'Volkswagen','Tiguan','2021-03-15','dizel','Automatik','Uskoro'),
(null,'Škoda','Kodiaq','2019-03-17','benzin','Manual','Uskoro');

insert into korisnik(sifra,ime,prezime,email,broj_mobitela,ime_ulice,grad,drzava)
values (null,'Martina','Ivankovič','martinai@gmail.com','385912348796','Osječka ulica 78','Osijek','Hrvatska'),
(null,'James','Knowille','knowille@yhotmail.com','07084421483','Amber hill 17b','London','Engleska'),
(null,'Lukas','Mayer','lukasmayer@gmail.com','06431849780','Maffie platz 13','Stuttgart','Njemačka'),
(null,'Luka','Lukić','lukalukić@gmail.com','0955874369','Kralja Tomislava 99a','Zagreb','Hrvatska'),
(null,'Ivan','Ivanović','ivanovic@gmail.com','0998563478','Franje Kuhača 26c','Đakovo','Hrvatska'),
(null,'Tara','Reić','tarareic@gmail.com','0987532687','Put brodarica 95','Split','Hrvatska');

