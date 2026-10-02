create database my_gaming;

use my_gaming;

create table genres (
	id int not null primary key identity (1, 1),
	genre varchar(50) not null
);

create table game_statuses (
	id int not null primary key identity (1, 1),
	game_status varchar(50) not null
);

create table games (
	id int not null primary key identity (1, 1),
	title varchar(50) not null,
	genre_id int not null foreign key (genre_id) references genres(id),
	status_id int not null foreign key (status_id) references game_statuses(id)
);

insert into genres(genre) values ('MMORPG');
insert into genres(genre) values ('co-op survival');
insert into genres(genre) values ('open-world RPG');
insert into genres(genre) values ('sandbox');
insert into genres(genre) values ('gacha');

insert into game_statuses(game_status) values ('playing');
insert into game_statuses(game_status) values ('in plans');
insert into game_statuses(game_status) values ('abandoned');
insert into game_statuses(game_status) values ('completed');

insert into games(title, genre_id, status_id) values ('genshin impact', 5, 1);
insert into games(title, genre_id, status_id) values ('honkai: star rail', 5, 3);
insert into games(title, genre_id, status_id) values ('minecraft', 4, 1);
insert into games(title, genre_id, status_id) values ('where winds meet', 3, 2);
insert into games(title, genre_id, status_id) values ('R.E.P.O.', 2, 2);