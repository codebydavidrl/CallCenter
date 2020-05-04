create database callcenter2020 default character set utf8 collate utf8_spanish_ci;
use callcenter2020;

drop table if exists users;

create table if not exists users
(
	id varchar(20) primary key not null,
    name varchar(50) not null,
    idEmployee int not null,
    photo varchar(20) not null,
    password varchar(50) not null
)engine = innodb character set utf8 collate utf8_spanish_ci;

insert into users (id, name, idEmployee, photo, password) values
('jsmith', 'John Smith', 1001, '1001.png', sha1('abc123')),
('mjones', 'Mary Jones', 1002, '1002.png', sha1('mary123')),
('sjohnson', 'Steve Johnson', 1003, '1003.png', sha1('steve1986'));

select name from users where id= 'mjones' and password = sha1('mary123');
