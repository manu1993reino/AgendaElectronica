create table if not exists agenda(
	codigo int(6) auto_increment not null,
    nombre varchar(50) not null,
    telefono varchar(12) not null,
    correo varchar(25),
    fechaNac date not null,
    constraint pk_codigo primary key(codigo)
);

insert ignore into `agenda` (`codigo`, `nombre`, `telefono`, `correo`, `fechaNac`) VALUES
(1, "Manuel", 608210200, 'manuel@gmail.com', '1993-03-26'),
(2, "Mireya", 680451245, 'mireya@gmail.com', '1994-09-09'),
(3, "Paola", 68952148, 'paola@gmail.com', '1984-10-27'),
(4, "Juan", 632587415, 'juan@gmail.com', '1983-04-12'),
(5, "Dolores", 606119697, 'dolores@gmail.com', '1956-04-10'),
(6, "Rosie", 623579852, 'rosie@gmail.com', '1960-05-05'),
(7, "Carlos", 666554488, 'carlos@gmail.com', '1968-10-13'),
(8, "Magdalena", 656985632, 'magdalena@gmail.com', '1935-08-17'),
(9, "Luis", 646468521, 'luis@gmail.com', '1920-01-26'),
(10, "Eduardo", 623012450, 'eduardo@gmail.com', '1988-06-16'),
(11, "Antonio", 685974521, 'antonio@gmail.com', '1993-08-28'),
(12, "Belen", 654888885, 'belen@gmail.com', '1990-12-12'),
(13, "Enrique", 666452000, 'enrique@gmail.com', '1978-02-14'),
(14, "Diego", 6313518456, 'diego@gmail.com', '1993-06-25'),
(15, "Miguel", 658899889, 'miguel@gmail.com', '1974-09-19'),
(16, "Vicente", 601247778, 'vicente@gmail.com', '1993-05-15'),
(17, "Alberto", 654565456, 'alberto@gmail.com', '1966-07-04'),
(18, "Marea", 636963696, 'marea@gmail.com', '2021-10-19'),
(19, "Romeo", 624862486, 'romeo@gmail.com', '2021-12-16'),
(20, "Valentina", 647147147, 'valentina@gmail.com', '2017-05-18');

create table if not exists usuarios(
	usuario varchar(25) not null,
    contrasena varchar(255) not null,
    constraint pk_usuario primary key(usuario)
);
use agenda;
select * from agenda;

select * from usuarios;

select * from agenda where codigo in (1,2,3);
drop database agenda;