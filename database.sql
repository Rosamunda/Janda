CREATE DATABASE if not exists janda
 	DEFAULT CHARACTER SET utf8
  	DEFAULT COLLATE utf8_general_ci;

USE janda;

GRANT SELECT, INSERT, UPDATE, DELETE
on janda.*
to jandaAdmin; /* 123456 */

create table if not exists gasto (
	id int unsigned not null auto_increment primary key,
	fecha date not null,
	monto int(10) not null,
	rubro char(20) not null,
	tipoGasto char(20),
	extraordinario char(2)
) DEFAULT CHARACTER SET utf8;

create table if not exists settings (
	id int unsigned not null auto_increment primary key,
	config char(200) not null,
	valor int(10) not null
) DEFAULT CHARACTER SET utf8;

INSERT INTO `janda`.`settings` (`id`, `config`, `valor`) VALUES (NULL, 'montoInicial', '1');
INSERT INTO `janda`.`settings` (`id`, `config`, `valor`) VALUES (NULL, 'diasTotales', '1');
