create database bakery default character set utf8 collate utf8_unicode_ci;
use bakery;
create user ubakery@localhost identified by 'cbakery';

grant all on bakery.* to ubakery@localhost;

flush privileges;

use bakery;

create table if not exists member (
    id bigint(20) not null auto_increment primary key,
    login varchar (40) not null unique,
    password varchar (255) not null
) engine = innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists client (
    id bigint(20) not null auto_increment primary key,
    name varchar (40) not null,
    surname varchar (60) not null,
    tin varchar (20) not null,
    address varchar (100) not null,
    location varchar (100) null,
    postalCode varchar (5) null,
    province varchar (30) null,
    email varchar (100) null,
    unique (name, surname, tin)
) engine = innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists family (
    id bigint(20) not null auto_increment primary key,
    family varchar (100) not null unique
) engine = innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists product (
    id bigint(20) not null auto_increment primary key,
    idFamily bigint (20) not null,
    product varchar (100) not null,
    price decimal(10,2) not null,
    description text null,

    unique (idFamily, product),
    foreign key (idFamily) references family(id) on delete restrict
) engine = innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists ticket (
    id bigint(20) not null auto_increment primary key,
    date datetime not null default current_timestamp on update current_timestamp ,
    
    idMember bigint(20) not null,
    idClient bigint(20) null,
    
    foreign key (idMember) references member(id) on delete restrict,
    foreign key (idClient) references client(id) on delete restrict
) engine = innodb default character set = utf8 collate utf8_unicode_ci;

create table if not exists ticketDetail (
    id bigint(20) not null auto_increment primary key,
    idTicket bigint(20) not null,
    idProduct bigint(20) not null,
    quantity tinyint(4) not null,
    price decimal (10,2) not null,
    
    foreign key (idTicket) references ticket(id) on delete restrict,
    foreign key (idProduct) references product(id) on delete restrict,
    unique(idTicket, idProduct)
) engine = innodb default character set = utf8 collate utf8_unicode_ci;

